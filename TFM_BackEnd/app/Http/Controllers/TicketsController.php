<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TicketsController extends Controller
{
    // Variable para almacenar el controlador de logs de tickets
    protected $ticketsLogsController;

    // Constructor que recibe el controlador de logs por inyección de dependencias
    public function __construct(TicketsLogsController $ticketsLogsController)
    {
        // Asignar el controlador de logs a la variable local
        $this->ticketsLogsController = $ticketsLogsController;
    }

    // Método para obtener todos los tickets
    public function listar()
    {
        // Crear nueva instancia de respuesta
        $response = new ResultResponse();

        try {
            // Obtener todos los tickets con sus relaciones
            $tickets = Tickets::with(['organizacion', 'usuario', 'tipoProducto'])->get();

            // Configurar respuesta exitosa
            $response->setData($tickets);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Lista de tickets obtenida correctamente');
        } catch (\Exception $e) {
            // Configurar respuesta de error
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener la lista de tickets: ' . $e->getMessage());
        }

        // Devolver respuesta en formato JSON
        return response()->json($response, $response->getStatusCode());
    }

    // Método para crear un nuevo ticket
    public function guardar(Request $request)
    {
        // Crear nueva instancia de respuesta
        $response = new ResultResponse();

        // Validar los datos recibidos del request
        $validator = Validator::make($request->all(), [
            'id_ticket'        => 'required|unique:tickets,id_ticket',
            'id_organizacion'  => 'required|exists:organizacion,id_organizacion',
            'id_usuario'       => 'required|exists:usuarios,id_usuario',
            'id_tipo_producto' => 'required|exists:tipo_productos,id_producto',
            'monto'            => 'nullable|numeric|min:0',
            'proyecto'         => 'nullable|string',
            'descr_compra'     => 'nullable|string',
        ]);

        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            // Extraer solo los campos necesarios del request
            $ticketData = $request->only([
                'id_ticket',
                'id_organizacion',
                'id_usuario',
                'id_tipo_producto',
                'monto',
                'proyecto',
                'descr_compra',
            ]);

            // Establecer valores por defecto
            $ticketData['estado_ticket'] = 'pendiente'; // Estado inicial
            $ticketData['fecha_cierre'] = null; // Fecha de cierre nula inicialmente

            // Crear el ticket en la base de datos
            $ticket = Tickets::create($ticketData);

            // PREPARAR LOG: Crear request para registrar el log de creación
            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(), // Generar ID único para el log
                'id_ticket' => $ticket->id_ticket, // ID del ticket creado
                'id_usuario' => $request->id_usuario, // Usuario que creó el ticket
                'estado_anterior' => null, // No hay estado anterior (creación)
                'estado_nuevo' => 'pendiente', // Estado inicial del ticket
                'fecha_cambio' => now() // Fecha y hora actual
            ]);

            // LLAMAR AL CONTROLADOR DE LOGS: Registrar el log de creación
            $this->ticketsLogsController->guardar($logRequest);

            // Configurar respuesta exitosa
            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket creado correctamente');
            return response()->json($response, 201);

        } catch (\Exception $e) {
            // Configurar respuesta de error
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el ticket: ' . $e->getMessage());
            return response()->json($response, $response->getStatusCode());
        }
    }

    // Método para obtener un ticket específico por ID
    public function ver($id)
    {
        // Crear nueva instancia de respuesta
        $response = new ResultResponse();

        try {
            // Buscar el ticket por ID con sus relaciones
            $ticket = Tickets::with(['organizacion', 'usuario', 'tipoProducto'])->find($id);

            // Si el ticket no existe, retornar error
            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // PREPARAR LOG: Crear request para registrar el log de consulta
            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(), // Generar ID único para el log
                'id_ticket' => $ticket->id_ticket, // ID del ticket consultado
                'id_usuario' => 'sistema', // Usuario sistema (consulta automática)
                'estado_anterior' => 'consultado', // Estado para registro de consulta
                'estado_nuevo' => 'consultado', // Mismo estado para consulta
                'fecha_cambio' => now()
            ]);

            // LLAMAR AL CONTROLADOR DE LOGS: Registrar el log de consulta
            $this->ticketsLogsController->guardar($logRequest);

            // Configurar respuesta exitosa
            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket encontrado');

        } catch (\Exception $e) {
            // Configurar respuesta de error
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener el ticket: ' . $e->getMessage());
        }

        // Devolver respuesta en formato JSON
        return response()->json($response, $response->getStatusCode());
    }

    // Método para actualizar un ticket existente
    public function actualizar(Request $request, $id)
    {
        // Crear nueva instancia de respuesta
        $response = new ResultResponse();

        // Validar los datos recibidos del request
        $validator = Validator::make($request->all(), [
            'id_ticket'        => "sometimes|unique:tickets,id_ticket,$id", // ID único excepto para este ticket
            'id_organizacion'  => 'sometimes|exists:organizacion,id_organizacion', // Organización existente
            'id_usuario'       => 'sometimes|exists:usuarios,id_usuario', // Usuario existente
            'id_tipo_producto' => 'sometimes|exists:tipo_productos,id_producto', // Tipo de producto existente
            'monto'            => 'sometimes|numeric|min:0', // Monto numérico opcional
            'proyecto'         => 'sometimes|string', // Proyecto opcional
            'descr_compra'     => 'sometimes|string', // Descripción opcional
        ]);

        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            // Buscar el ticket por ID
            $ticket = Tickets::find($id);

            // Si el ticket no existe, retornar error
            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Guardar el estado anterior para el log
            $estadoAnterior = $ticket->estado_ticket;

            // Actualizar el ticket con los nuevos datos
            $ticket->update($request->all());

            // PREPARAR LOG: Crear request para registrar el log de actualización
            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(), // Generar ID único para el log
                'id_ticket' => $ticket->id_ticket, // ID del ticket actualizado
                'id_usuario' => 'sistema', // Usuario sistema (actualización automática)
                'estado_anterior' => $estadoAnterior, // Estado antes de la actualización
                'estado_nuevo' => $ticket->estado_ticket, // Estado después de la actualización
                'fecha_cambio' => now() // Fecha y hora actual
            ]);

            // LLAMAR AL CONTROLADOR DE LOGS: Registrar el log de actualización
            $this->ticketsLogsController->guardar($logRequest);

            // Configurar respuesta exitosa
            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket actualizado correctamente');

        } catch (\Exception $e) {
            // Configurar respuesta de error
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el ticket: ' . $e->getMessage());
        }

        // Devolver respuesta en formato JSON
        return response()->json($response, $response->getStatusCode());
    }

    // Método para actualizar el estado de un ticket
    public function actualizarEstado(Request $request, $id)
    {
        // Crear nueva instancia de respuesta
        $response = new ResultResponse();

        // Validar los datos recibidos del request
        $validator = Validator::make($request->all(), [
            'estado_ticket' => 'required|in:pendiente,en_revision,aprobado,rechazado,completado', // Estado válido
            'fecha_cierre' => 'nullable|date|required_if:estado_ticket,completado,rechazado', // Fecha requerida para ciertos estados
            'id_usuario_accion' => 'required|exists:usuarios,id_usuario' // Usuario que realiza la acción
        ]);

        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            // Buscar el ticket por ID
            $ticket = Tickets::find($id);

            // Si el ticket no existe, retornar error
            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Guardar el estado anterior para el log
            $estadoAnterior = $ticket->estado_ticket;

            // Actualizar el estado del ticket
            $ticket->estado_ticket = $request->estado_ticket;

            // Si el estado es completado o rechazado, establecer fecha de cierre
            if (in_array($request->estado_ticket, ['completado', 'rechazado'])) {
                $ticket->fecha_cierre = $request->fecha_cierre ?? now(); // Usar fecha proporcionada o actual
            } else {
                $ticket->fecha_cierre = null; // Limpiar fecha de cierre
            }

            // Guardar los cambios en la base de datos
            $ticket->save();

            // PREPARAR LOG: Crear request para registrar el log de cambio de estado
            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(), // Generar ID único para el log
                'id_ticket' => $ticket->id_ticket, // ID del ticket modificado
                'id_usuario' => $request->id_usuario_accion, // Usuario que cambió el estado
                'estado_anterior' => $estadoAnterior, // Estado antes del cambio
                'estado_nuevo' => $request->estado_ticket, // Nuevo estado
                'fecha_cambio' => now() // Fecha y hora actual
            ]);

            // LLAMAR AL CONTROLADOR DE LOGS: Registrar el log de cambio de estado
            $this->ticketsLogsController->guardar($logRequest);

            // Configurar respuesta exitosa
            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Estado del ticket actualizado correctamente');

        } catch (\Exception $e) {
            // Configurar respuesta de error
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el ticket: ' . $e->getMessage());
        }

        // Devolver respuesta en formato JSON
        return response()->json($response, $response->getStatusCode());
    }

    // Método para eliminar un ticket
    public function eliminar($id)
    {
        // Crear nueva instancia de respuesta
        $response = new ResultResponse();

        try {
            // Buscar el ticket por ID
            $ticket = Tickets::find($id);

            // Si el ticket no existe, retornar error
            if (!$ticket) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Ticket no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Guardar información para el log antes de eliminar
            $idTicket = $ticket->id_ticket;
            $estadoAnterior = $ticket->estado_ticket;

            // Eliminar el ticket de la base de datos
            $ticket->delete();

            // PREPARAR LOG: Crear request para registrar el log de eliminación
            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(), // Generar ID único para el log
                'id_ticket' => $idTicket, // ID del ticket eliminado
                'id_usuario' => 'sistema', // Usuario sistema (eliminación automática)
                'estado_anterior' => $estadoAnterior, // Estado antes de eliminar
                'estado_nuevo' => 'eliminado', // Estado de eliminación
                'fecha_cambio' => now() // Fecha y hora actual
            ]);

            // LLAMAR AL CONTROLADOR DE LOGS: Registrar el log de eliminación
            $this->ticketsLogsController->guardar($logRequest);

            // Configurar respuesta exitosa
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket eliminado correctamente');

        } catch (\Exception $e) {
            // Configurar respuesta de error
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el ticket: ' . $e->getMessage());
        }

        // Devolver respuesta en formato JSON
        return response()->json($response, $response->getStatusCode());
    }
}
