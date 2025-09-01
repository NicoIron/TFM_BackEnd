<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
            $tickets = Tickets::with(['organizacion', 'usuario', 'tipoProducto'])->get();
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

            $ticket = Tickets::create($ticketData);

            // Registrar log llamando a  TicketsLogsController
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

            // Registrar log usando TicketsLogsController
            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(),
                'id_ticket' => $ticket->id_ticket,
                'id_usuario' => 'sistema',
                'estado_anterior' => 'consultado',
                'estado_nuevo' => 'consultado',
                'fecha_cambio' => now()
            ]);

            $this->ticketsLogsController->guardar($logRequest);

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

            // Registrar log usando TicketsLogsController
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

            // Registrar log usando TicketsLogsController
            $logRequest = new Request([
                'id_ticket_log' => 'LOG-' . uniqid(),
                'id_ticket' => $ticket->id_ticket,
                'id_usuario' => $request->id_usuario_accion,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $request->estado_ticket,
                'fecha_cambio' => now()
            ]);

            $this->ticketsLogsController->guardar($logRequest);

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

            // Registrar log usando TicketsLogsController
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
}
