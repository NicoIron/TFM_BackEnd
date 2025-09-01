<?php

namespace App\Http\Controllers;

use App\Models\TicketsLogs;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Utils\ResultResponse;

class TicketsLogsController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();
        $response->setData(TicketsLogs::all());
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Listado de logs de tickets');
        return response()->json($response, 200);
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validated = $request->validate([
            'id_ticket_log' => 'required|string|max:50|unique:tickets_logs,id_ticket_log',
            'id_ticket' => 'required|exists:tickets,id_ticket',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'estado_anterior' => 'nullable|string|max:50',
            'estado_nuevo' => 'required|string|max:50',
            'fecha_cambio' => 'nullable|date'
        ]);

        try {
            $log = TicketsLogs::create($validated);

            $response->setData($log);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Log creado correctamente');
            return response()->json($response, 201);
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Error al crear el log: ' . $e->getMessage());
            return response()->json($response, 409);
        }
    }

    public function ver($id)
    {
        $response = new ResultResponse();

        $log = TicketsLogs::find($id);
        if (!$log) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Log no encontrado');
            return response()->json($response, 404);
        }

        $response->setData($log);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Log encontrado');
        return response()->json($response, 200);
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();
        $log = TicketsLogs::find($id);

        if (!$log) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Log no encontrado');
            return response()->json($response, 404);
        }

        $validated = $request->validate([
            'id_ticket' => 'sometimes|exists:tickets,id_ticket',
            'id_usuario' => 'sometimes|exists:usuarios,id_usuario',
            'estado_anterior' => 'nullable|string|max:50',
            'estado_nuevo' => 'sometimes|string|max:50',
            'fecha_cambio' => 'nullable|date',
        ]);

        try {
            $log->update($validated);

            $response->setData($log);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Log actualizado');
            return response()->json($response, 200);
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Error al actualizar el log: ' . $e->getMessage());
            return response()->json($response, 409);
        }
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();
        $log = TicketsLogs::find($id);

        if (!$log) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Log no encontrado');
            return response()->json($response, 404);
        }

        try {
            $log->eliminado = true;
            $log->save();

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Log eliminado lógicamente');
            return response()->json($response, 200);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Error al eliminar el log: ' . $e->getMessage());
            return response()->json($response, 409);
        }
    }
}
