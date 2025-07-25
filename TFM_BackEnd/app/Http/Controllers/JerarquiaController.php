<?php

namespace App\Http\Controllers;

use App\Models\Jerarquia;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;

class JerarquiaController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();
        $response->setData(Jerarquia::all());
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Listado de jerarquías');
        return response()->json($response);
    }

    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'id_organizacion' => 'required|integer|exists:organizacion,id',
            'cargo' => 'required|string|max:100',
            'eliminado' => 'required|boolean',
        ]);

        $jerarquia = Jerarquia::create($validated);

        $response = new ResultResponse();
        $response->setData($jerarquia);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Jerarquía creada correctamente');

        return response()->json($response, 201);
    }

    public function ver($id)
    {
        $jerarquia = Jerarquia::find($id);

        if (!$jerarquia) {
            $response = new ResultResponse();
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Jerarquía no encontrada');
            return response()->json($response, 404);
        }

        $response = new ResultResponse();
        $response->setData($jerarquia);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Jerarquía encontrada');

        return response()->json($response);
    }

    public function actualizar(Request $request, $id)
    {
        $jerarquia = Jerarquia::find($id);

        if (!$jerarquia) {
            $response = new ResultResponse();
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Jerarquía no encontrada');
            return response()->json($response, 404);
        }

        $validated = $request->validate([
            'id_organizacion' => 'sometimes|integer|exists:organizacion,id',
            'cargo' => 'sometimes|string|max:100',
            'eliminado' => 'sometimes|boolean',
        ]);

        $jerarquia->update($validated);

        $response = new ResultResponse();
        $response->setData($jerarquia);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Jerarquía actualizada');

        return response()->json($response);
    }

    public function eliminar($id)
    {
        $jerarquia = Jerarquia::find($id);

        if (!$jerarquia) {
            $response = new ResultResponse();
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Jerarquía no encontrada');
            return response()->json($response, 404);
        }

        $jerarquia->delete();

        $response = new ResultResponse();
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Jerarquía eliminada');

        return response()->json($response);
    }
}
