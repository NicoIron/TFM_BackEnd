<?php

namespace App\Http\Controllers;

use App\Models\TiposProductos;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;

class TiposProductosController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();
        try {
            $tipos = TiposProductos::whereNull('deleted_at')->get();
            $response->setData($tipos);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Listado de tipos de producto');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al listar los tipos de producto: ' . $e->getMessage());
        }
        return response()->json($response);
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_producto'     => 'required|string|max:50|unique:tipo_productos,id_producto',
            'id_organizacion' => 'required|string|exists:organizacion,id_organizacion',
            'nombre_producto' => 'required|string|max:100',
            'descripcion'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $tipo = TiposProductos::create($request->all());
            $response->setData($tipo);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Tipo de producto creado correctamente');
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el tipo de producto: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
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

        try {
            $tipo = TiposProductos::find($id);

            if (!$tipo) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Tipo de producto no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $tipo->delete(); // ← soft delete correcto
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Tipo de producto eliminado correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el tipo de producto: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
