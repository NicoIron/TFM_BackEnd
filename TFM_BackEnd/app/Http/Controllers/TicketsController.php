<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario;
use App\Http\Controllers\TicketsLogsController;
use Illuminate\Support\Facades\Log;
use App\Models\JerarquiaRol;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Ticket;
use App\Models\TicketsLogs;
use App\Http\Controllers\NotificacionesController;

class TicketsController extends Controller
{
    protected $ticketsLogsController;

    public function __construct(TicketsLogsController $ticketsLogsController)
    {
        $this->ticketsLogsController = $ticketsLogsController;
    }

    public function listar()
    {
        $response = new ResultResponse();

        try {
            $tickets = Tickets::with(['organizacion', 'usuario', 'tipoProducto', 'aprobador'])->get();

            $response->setData($tickets);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Lista de tickets obtenida correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener la lista de tickets: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_ticket'        => 'required|unique:tickets,id_ticket',
            'id_organizacion'  => 'required|exists:organizacion,id_organizacion',
            'id_usuario'       => 'required|exists:usuarios,id_usuario',
            'id_tipo_producto' => 'required|exists:tipo_productos,id_producto',
            'monto'            => 'nullable|numeric|min:0',
            'proyecto'         => 'nullable|string',
            'descr_compra'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $ticketData = $request->only([
                'id_ticket',
                'id_organizacion',
                'id_usuario',
                'id_tipo_producto',
                'monto',
                'proyecto',
                'descr_compra',
            ]);

            $ticketData['estado_ticket'] = 'pendiente';
            $ticketData['fecha_cierre'] = null;

            // LÓGICA PARA ASIGNAR APROBADOR CON ESCALAMIENTO POR NIVEL
            $usuario = Usuario::with('rol')->where('id_usuario', $request->id_usuario)->first();

            if ($usuario && $usuario->rol) {
                Log::info("Usuario encontrado: {$usuario->id_usuario}, Rol: {$usuario->rol->nombre_rol} (Nivel: {$usuario->rol->nivel})");

                $aprobadorAsignado = false;
                $nivelUsuario = $usuario->rol->nivel;

                // PASO 1: Intentar asignar según jerarquía específica (id_rol_superior)
                $relacionJerarquica = JerarquiaRol::where('id_rol', $usuario->rol->id)->first();

                if ($relacionJerarquica && $relacionJerarquica->id_rol_superior) {
                    Log::info("Buscando usuario con rol superior específico ID: {$relacionJerarquica->id_rol_superior}");

                    $aprobador = Usuario::where('id_rol', $relacionJerarquica->id_rol_superior)
                        ->where('id_organizacion', $request->id_organizacion)
                        ->whereNull('deleted_at')
                        ->first();

                    if ($aprobador) {
                        $ticketData['id_aprobador'] = $aprobador->id_usuario;
                        Log::info("Aprobador asignado (jerarquía específica): {$aprobador->id_usuario} ({$aprobador->nombre})");
                        $aprobadorAsignado = true;
                    } else {
                        Log::warning("No se encontró usuario con rol superior específico. Buscando por nivel.");
                    }
                }

                // PASO 2: Si no se encontró por jerarquía específica, buscar por NIVEL
                if (!$aprobadorAsignado) {
                    for ($nivelBuscado = $nivelUsuario - 1; $nivelBuscado >= 1; $nivelBuscado--) {
                        Log::info("Buscando usuarios con nivel {$nivelBuscado}");

                        $aprobador = Usuario::whereHas('rol', function($query) use ($nivelBuscado) {
                            $query->where('nivel', $nivelBuscado);
                        })
                        ->where('id_organizacion', $request->id_organizacion)
                        ->whereNull('deleted_at')
                        ->first();

                        if ($aprobador) {
                            $ticketData['id_aprobador'] = $aprobador->id_usuario;
                            Log::info("Aprobador asignado (por nivel {$nivelBuscado}): {$aprobador->id_usuario} ({$aprobador->nombre})");
                            $aprobadorAsignado = true;
                            break;
                        } else {
                            Log::warning("No se encontró usuario con nivel {$nivelBuscado}. Continuando búsqueda.");
                        }
                    }
                }

                // PASO 3: Si aún no se encontró, asignar a Comité Operativo
                if (!$aprobadorAsignado) {
                    Log::warning("No se encontró aprobador en ningún nivel. Asignando a Comité Operativo.");
                    $comiteOperativo = Usuario::whereHas('rol', function($query) {
                        $query->where('nombre_rol', 'Comite Operativo');
                    })
                    ->where('id_organizacion', $request->id_organizacion)
                    ->whereNull('deleted_at')
                    ->first();

                    if ($comiteOperativo) {
                        $ticketData['id_aprobador'] = $comiteOperativo->id_usuario;
                        Log::info("Ticket asignado al Comité Operativo (fallback): {$comiteOperativo->id_usuario}");
                    } else {
                        Log::error("ERROR CRÍTICO: No se encontró Comité Operativo. Ticket sin aprobador.");
                    }
                }
            }

            $ticket = Tickets::create($ticketData);

            // NOTIFICAR AL APROBADOR ASIGNADO
            if (isset($ticketData['id_aprobador'])) {
                NotificacionesController::crearNotificacion([
                    'id_usuario' => $ticketData['id_aprobador'],
                    'id_organizacion' => $ticket->id_organizacion,
                    'tipo' => 'ticket_asignado',
                    'titulo' => 'Nuevo ticket asignado',
                    'mensaje' => "Se te ha asignado el ticket {$ticket->id_ticket} para aprobación. Solicitante: {$usuario->nombre} {$usuario->apellido}",
                    'id_ticket' => $ticket->id_ticket
                ]);
            }

            // Registrar log de creación
            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(),
                'id_ticket' => $ticket->id_ticket,
                'id_usuario' => $request->id_usuario,
                'estado_anterior' => null,
                'estado_nuevo' => 'pendiente',
                'fecha_cambio' => now()
            ]);

            $this->ticketsLogsController->guardar($logRequest);

            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket creado correctamente');
            return response()->json($response, 201);

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el ticket: ' . $e->getMessage());
            return response()->json($response, $response->getStatusCode());
        }
    }

    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            $ticket = Tickets::with(['organizacion', 'usuario', 'tipoProducto'])->find($id);

            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket encontrado');

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener el ticket: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_ticket'        => "sometimes|unique:tickets,id_ticket,$id",
            'id_organizacion'  => 'sometimes|exists:organizacion,id_organizacion',
            'id_usuario'       => 'sometimes|exists:usuarios,id_usuario',
            'id_tipo_producto' => 'sometimes|exists:tipo_productos,id_producto',
            'monto'            => 'sometimes|numeric|min:0',
            'proyecto'         => 'sometimes|string',
            'descr_compra'     => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $ticket = Tickets::find($id);

            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $estadoAnterior = $ticket->estado_ticket;
            $ticket->update($request->all());

            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(),
                'id_ticket' => $ticket->id_ticket,
                'id_usuario' => 'sistema',
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $ticket->estado_ticket,
                'fecha_cambio' => now()
            ]);

            $this->ticketsLogsController->guardar($logRequest);

            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket actualizado correctamente');

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el ticket: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function actualizarEstado(Request $request, $id)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'estado_ticket' => 'required|in:pendiente,en_revision,aprobado,rechazado,completado',
            'fecha_cierre' => 'nullable|date|required_if:estado_ticket,completado,rechazado',
            'id_usuario_accion' => 'required|exists:usuarios,id_usuario'
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $ticket = Tickets::find($id);

            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $estadoAnterior = $ticket->estado_ticket;
            $ticket->estado_ticket = $request->estado_ticket;

            if (in_array($request->estado_ticket, ['completado', 'rechazado'])) {
                $ticket->fecha_cierre = $request->fecha_cierre ?? now();
            } else {
                $ticket->fecha_cierre = null;
            }

            $ticket->save();

            // NOTIFICAR AL CREADOR DEL TICKET
            $mensaje = $request->estado_ticket === 'aprobado'
                ? "Tu ticket {$ticket->id_ticket} ha sido aprobado"
                : "Tu ticket {$ticket->id_ticket} ha sido rechazado";

            NotificacionesController::crearNotificacion([
                'id_usuario' => $ticket->id_usuario,
                'id_organizacion' => $ticket->id_organizacion,
                'tipo' => "ticket_{$request->estado_ticket}",
                'titulo' => $request->estado_ticket === 'aprobado' ? 'Ticket Aprobado' : 'Ticket Rechazado',
                'mensaje' => $mensaje,
                'id_ticket' => $ticket->id_ticket
            ]);

            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(),
                'id_ticket' => $ticket->id_ticket,
                'id_usuario' => $request->id_usuario_accion,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $request->estado_ticket,
                'fecha_cambio' => now()
            ]);

            $this->ticketsLogsController->guardar($logRequest);

            Log::info("Ticket {$ticket->id_ticket} actualizado a {$request->estado_ticket}");

            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Estado del ticket actualizado correctamente');

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el ticket: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();

        try {
            $ticket = Tickets::find($id);

            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $idTicket = $ticket->id_ticket;
            $estadoAnterior = $ticket->estado_ticket;

            $ticket->delete();

            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(),
                'id_ticket' => $idTicket,
                'id_usuario' => 'sistema',
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => 'eliminado',
                'fecha_cambio' => now()
            ]);

            $this->ticketsLogsController->guardar($logRequest);

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket eliminado correctamente');

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el ticket: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function obtenerTicketsPorUsuario($id_usuario)
    {
        $response = new ResultResponse();

        try{
            $tickets = Tickets::with(['organizacion', 'tipoProducto'])
                ->where('id_usuario', $id_usuario)
                ->get();

            if($tickets->isEmpty()) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('No se encontraron tickets para el usuario especificado');
                return response()->json($response, $response->getStatusCode());
            }
            $response->setData($tickets);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Tickets del usuario obtenidos correctamente');
        }catch(\Exception $e){
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener los tickets del usuario: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function escalar(Request $request, $id)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_usuario_actual' => 'required|exists:usuarios,id_usuario',
            'motivo' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $ticket = Tickets::find($id);

            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            if ($ticket->id_aprobador !== $request->id_usuario_actual) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('Solo el aprobador asignado puede escalar este ticket');
                return response()->json($response, $response->getStatusCode());
            }

            $usuarioActual = Usuario::with('rol')->where('id_usuario', $request->id_usuario_actual)->first();

            if (!$usuarioActual || !$usuarioActual->rol) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('Usuario actual no encontrado o sin rol asignado');
                return response()->json($response, $response->getStatusCode());
            }

            $nivelActual = $usuarioActual->rol->nivel;

            $nuevoAprobador = Usuario::whereHas('rol', function($query) use ($nivelActual) {
                $query->where('nivel', '<', $nivelActual);
            })
            ->where('id_organizacion', $ticket->id_organizacion)
            ->whereNull('deleted_at')
            ->orderBy(function($query) {
                $query->select('nivel')
                    ->from('roles')
                    ->whereColumn('roles.id', 'usuarios.id_rol')
                    ->orderBy('nivel', 'desc')
                    ->limit(1);
            })
            ->first();

            if (!$nuevoAprobador) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('No hay usuarios de nivel superior disponibles para escalar');
                return response()->json($response, $response->getStatusCode());
            }

            $estadoAnterior = $ticket->estado_ticket;
            $aprobadorAnterior = $ticket->id_aprobador;

            $ticket->estado_ticket = 'en_revision';
            $ticket->id_aprobador = $nuevoAprobador->id_usuario;
            $ticket->save();

            // NOTIFICAR AL NUEVO APROBADOR
            NotificacionesController::crearNotificacion([
                'id_usuario' => $nuevoAprobador->id_usuario,
                'id_organizacion' => $ticket->id_organizacion,
                'tipo' => 'ticket_escalado',
                'titulo' => 'Ticket escalado a ti',
                'mensaje' => "Se te ha escalado el ticket {$ticket->id_ticket} para aprobación",
                'id_ticket' => $ticket->id_ticket
            ]);

            // NOTIFICAR AL CREADOR DEL TICKET
            NotificacionesController::crearNotificacion([
                'id_usuario' => $ticket->id_usuario,
                'id_organizacion' => $ticket->id_organizacion,
                'tipo' => 'ticket_escalado_info',
                'titulo' => 'Tu ticket fue escalado',
                'mensaje' => "Tu ticket {$ticket->id_ticket} fue escalado a un nivel superior de aprobación",
                'id_ticket' => $ticket->id_ticket
            ]);

            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(),
                'id_ticket' => $ticket->id_ticket,
                'id_usuario' => $request->id_usuario_actual,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => 'en_revision',
                'fecha_cambio' => now()
            ]);

            $this->ticketsLogsController->guardar($logRequest);

            Log::info("Ticket {$ticket->id_ticket} escalado de {$aprobadorAnterior} a {$nuevoAprobador->id_usuario}");

            $response->setData([
                'ticket' => $ticket,
                'nuevo_aprobador' => [
                    'id_usuario' => $nuevoAprobador->id_usuario,
                    'nombre' => $nuevoAprobador->nombre . ' ' . $nuevoAprobador->apellido
                ]
            ]);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket escalado correctamente');

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al escalar el ticket: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function obtenerTicketsPorAprobador($id_aprobador)
    {
        $response = new ResultResponse();

        try {
            $tickets = Tickets::with(['organizacion', 'usuario', 'tipoProducto', 'aprobador'])
                ->where('id_aprobador', $id_aprobador)
                ->whereIn('estado_ticket', ['pendiente', 'en_revision'])
                ->orderBy('created_at', 'desc')
                ->get();

            $response->setData($tickets);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Tickets del aprobador obtenidos correctamente');

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener los tickets: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function obtenerEstadisticas(Request $request, $id_usuario)
    {
        $response = new ResultResponse();

        try {
            $validator = Validator::make($request->all(), [
                'fecha_inicio' => 'nullable|date',
                'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio'
            ]);

            if ($validator->fails()) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('Fechas inválidas');
                $response->setData($validator->errors());
                return response()->json($response, $response->getStatusCode());
            }

            $fecha_inicio = $request->query('fecha_inicio');
            $fecha_fin = $request->query('fecha_fin');

            $queryTicketsCreados = Tickets::where('id_usuario', $id_usuario);
            if ($fecha_inicio && $fecha_fin) {
                $queryTicketsCreados->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
            }
            $ticketsCreados = $queryTicketsCreados->count();

            $queryTicketsAsignados = Tickets::where('id_aprobador', $id_usuario);
            if ($fecha_inicio && $fecha_fin) {
                $queryTicketsAsignados->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
            }
            $ticketsAsignados = $queryTicketsAsignados->count();

            $ticketsPendientes = Tickets::where('id_aprobador', $id_usuario)
                ->whereIn('estado_ticket', ['pendiente', 'en_revision'])
                ->count();

            $queryLogs = TicketsLogs::where('id_usuario', $id_usuario);
            if ($fecha_inicio && $fecha_fin) {
                $queryLogs->whereBetween('fecha_cambio', [$fecha_inicio, $fecha_fin]);
            }

            $ticketsAprobados = (clone $queryLogs)
                ->where('estado_nuevo', 'aprobado')
                ->distinct()
                ->count('id_ticket');

            $ticketsRechazados = (clone $queryLogs)
                ->where('estado_nuevo', 'rechazado')
                ->distinct()
                ->count('id_ticket');

            $ticketsEscalados = (clone $queryLogs)
                ->where('estado_nuevo', 'en_revision')
                ->where(function($query) {
                    $query->where('estado_anterior', '!=', 'en_revision')
                          ->orWhereNull('estado_anterior');
                })
                ->distinct()
                ->count('id_ticket');

            $estadisticas = [
                'tickets_creados' => $ticketsCreados,
                'tickets_asignados' => $ticketsAsignados,
                'tickets_pendientes' => $ticketsPendientes,
                'tickets_aprobados' => $ticketsAprobados,
                'tickets_rechazados' => $ticketsRechazados,
                'tickets_escalados' => $ticketsEscalados,
                'total_gestionados' => $ticketsAprobados + $ticketsRechazados + $ticketsEscalados,
                'periodo' => $fecha_inicio && $fecha_fin
                    ? ['desde' => $fecha_inicio, 'hasta' => $fecha_fin]
                    : 'tiempo_real'
            ];

            $response->setData($estadisticas);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Estadísticas obtenidas correctamente');

        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener estadísticas: ' . $e->getMessage());
            Log::error('Error en estadísticas: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
