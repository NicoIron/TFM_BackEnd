<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;

class OrganizacionController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();

        try {
            $organizaciones = Organizacion::all();
            $response->setData($organizaciones);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Listado de organizaciones');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener las organizaciones: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_organizacion'      => 'required|string|max:50|unique:organizacion,id_organizacion',
            'nombre_organizacion'  => 'required|string|max:100|unique:organizacion,nombre_organizacion',
            'descripcion'          => 'nullable|string',
        ], [
            'nombre_organizacion.unique' => 'El nombre de la organización ya existe.',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación o datos repetidos.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $organizacion = Organizacion::create($request->all());
            $response->setData($organizacion);
            $response->setStatusCode(201); // Created
            $response->setMessage('Organización creada correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear la organización: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            $organizacion = Organizacion::find($id);

            if (!$organizacion) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Organización no encontrada');
                return response()->json($response, $response->getStatusCode());
            }

            $response->setData($organizacion);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Organización encontrada');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener la organización: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_organizacion'      => 'sometimes|required|string|max:50|unique:organizacion,id_organizacion,' . $id,
            'nombre_organizacion'  => 'sometimes|required|string|max:100|unique:organizacion,nombre_organizacion,' . $id,
            'descripcion'          => 'nullable|string',
        ], [
            'nombre_organizacion.unique' => 'El nombre de la organización ya existe.',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $organizacion = Organizacion::find($id);

            if (!$organizacion) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Organización no encontrada');
                return response()->json($response, $response->getStatusCode());
            }

            $organizacion->update($request->all());
            $response->setData($organizacion);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Organización actualizada');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar la organización: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();

        try {
            $organizacion = Organizacion::find($id);

            if (!$organizacion) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Organización no encontrada');
                return response()->json($response, $response->getStatusCode());
            }

            $organizacion->delete();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Organización eliminada');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar la organización: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
