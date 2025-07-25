<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Database\QueryException;

class TicketsController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();
        $response->setData(Tickets::all());
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Listado de tickets');

        return response()->json($response, 200);
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validated = $request->validate([
            'id_organizacion'   => 'required|exists:organizacion,id',
            'id_ticket'         => 'required|string|unique:tickets,id_ticket',
            'id_rol'            => 'required|exists:roles,id',
            'id_usuario'        => 'required|exists:usuarios,id',
            'id_tipo_producto'  => 'required|exists:tipo_productos,id',
            'monto'             => 'required|numeric',
            'proyecto'          => 'required|string|max:255',
            'desc_compra'       => 'required|string',
            'gestor'            => 'required|string|max:255',
            'estado_solicitud'  => 'nullable|boolean',
            'fecha_limite'      => 'required|date',
        ]);

        try {
            $ticket = Tickets::create($validated);
            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket creado correctamente');

            return response()->json($response, 201); // HTTP 201 Created
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Error al crear el ticket. Verifique los datos ingresados.');

            return response()->json($response, 409); // HTTP 409 Conflict
        }
    }

    public function ver($id)
    {
        $response = new ResultResponse();
        $ticket = Tickets::find($id);

        if (!$ticket) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Ticket no encontrado');

            return response()->json($response, 404); // HTTP 404 Not Found
        }

        $response->setData($ticket);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Ticket encontrado');

        return response()->json($response, 200);
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();
        $ticket = Tickets::find($id);

        if (!$ticket) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Ticket no encontrado');

            return response()->json($response, 404);
        }

        try {
            $ticket->update($request->all());
            $response->setData($ticket);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket actualizado correctamente');

            return response()->json($response, 200);
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Error al actualizar el ticket. Verifique los datos ingresados.');

            return response()->json($response, 409);
        }
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();
        $ticket = Tickets::find($id);

        if (!$ticket) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Ticket no encontrado');

            return response()->json($response, 404);
        }

        try {
            $ticket->eliminado = true;
            $ticket->save();

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Ticket eliminado lógicamente');

            return response()->json($response, 200);
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('No se pudo eliminar el ticket. Verifica si tiene relaciones activas.');

            return response()->json($response, 409);
        }
    }
}
