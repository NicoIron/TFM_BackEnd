<?php

namespace App\Http\Controllers;

use App\Models\TiposProductos;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;

class TiposProductosController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();
        $response->setData(TiposProductos::all());
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Listado de tipos de producto');
        return response()->json($response);
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validated = $request->validate([
            'id_organizacion' => 'required|integer',
            'nombre' => 'required|string',
            'descripcion' => 'nullable|string',
            'id_padre' => 'nullable|integer|exists:tipos_producto,id',
        ]);

        $tipo = TiposProductos::create($validated);

        $response->setData($tipo);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Tipo de producto creado correctamente');
        return response()->json($response);
    }

    public function ver($id)
    {
        $response = new ResultResponse();
        $tipo = TiposProductos::find($id);

        if (!$tipo) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Tipo de producto no encontrado');
            return response()->json($response);
        }

        $response->setData($tipo);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Tipo de producto encontrado');
        return response()->json($response);
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();
        $tipo = TiposProductos::find($id);

        if (!$tipo) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Tipo de producto no encontrado');
            return response()->json($response);
        }

        $tipo->update($request->all());

        $response->setData($tipo);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Tipo de producto actualizado');
        return response()->json($response);
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();
        $tipo = TiposProductos::find($id);

        if (!$tipo) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Tipo de producto no encontrado');
            return response()->json($response);
        }

        $tipo->eliminado = true;
        $tipo->save();

        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Tipo de producto eliminado lógicamente');
        return response()->json($response);
    }
}
