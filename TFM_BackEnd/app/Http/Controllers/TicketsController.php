<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\TicketsLogs;
use Illuminate\Support\Facades\Log;

class TicketsController extends Controller
{
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
        //  Removemos 'estado_ticket' y 'fecha_cierre' de la validación
    ]);

    if ($validator->fails()) {
        $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
        $response->setMessage('Error en la validación.');
        $response->setData($validator->errors());
        return response()->json($response, $response->getStatusCode());
    }

    try {
        // Forzar valores por defecto
        $ticketData = $request->only([
            'id_ticket',
            'id_organizacion',
            'id_usuario',
            'id_tipo_producto',
            'monto',
            'proyecto',
            'descr_compra',
        ]);

        // Establecer estado como "pendiente" y fecha_cierre como null
        $ticketData['estado_ticket'] = 'pendiente';
        $ticketData['fecha_cierre'] = null;

        $ticket = Tickets::create($ticketData);

        // REGISTRAR EN LOGS - Crear entrada en tickets_logs
        $this->registrarLogTicket(
            $ticket->id_ticket,
            $request->id_usuario,
            null, // estado_anterior (no existe para creación)
            'pendiente', // estado_nuevo
            'creacion' // acción
        );

        $response->setData($ticket);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Ticket creado correctamente con estado pendiente');
        return response()->json($response, 201);

    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage('Error al crear el ticket: ' . $e->getMessage());
        return response()->json($response, $response->getStatusCode());
    }
}

/**
 * Método para registrar logs de tickets
 */
private function registrarLogTicket($idTicket, $idUsuario, $estadoAnterior, $estadoNuevo)
{
    try {
        // Generar ID único para el log
        $idTicketLog = 'LOG-' . uniqid();
        TicketsLogs::create([
            'id_ticket_log' => $idTicketLog,
            'id_ticket' => $idTicket,
            'id_usuario' => $idUsuario,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'fecha_cambio' => now()
        ]);

        Log::info("Log registrado para ticket: $idTicket, estado: $estadoAnterior -> $estadoNuevo");

    } catch (\Exception $e) {
        Log::error('Error al registrar log de ticket: ' . $e->getMessage()); //  Corregido
        Log::error('Detalles del error: ' . $e->getTraceAsString()); //  Corregido
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
            $response->setMessage('Ticket obtenido correctamente');
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
            'id_ticket'        => "required|unique:tickets,id_ticket,$id",
            'id_organizacion'  => 'required|exists:organizacion,id_organizacion',
            'id_usuario'       => 'required|exists:usuarios,id_usuario',
            'id_tipo_producto' => 'required|exists:tipo_productos,id_producto',
            'monto'            => 'nullable|numeric|min:0',
            'proyecto'         => 'nullable|string',
            'descr_compra'     => 'nullable|string',
            'estado_ticket'    => 'required|string|max:50',
            'fecha_cierre'     => 'nullable|date',
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

            $ticket->update($request->only([
                'id_ticket',
                'id_organizacion',
                'id_usuario',
                'id_tipo_producto',
                'monto',
                'proyecto',
                'descr_compra',
                'estado_ticket',
                'fecha_cierre',
            ]));

            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket actualizado correctamente');
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

            $ticket->delete();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket eliminado correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el ticket: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
