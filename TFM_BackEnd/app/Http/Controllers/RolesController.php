<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();

        try {
            $roles = Roles::with(['organizacion', 'jerarquia'])->get();
            $response->setData($roles);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Lista de roles obtenida correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener la lista de roles: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_rol'          => 'required|unique:roles,id_rol',
            'nombre_rol'      => 'required|string|max:100',
            'nivel'           => 'nullable|integer|min:1', // Nuevo campo para jerarquía
            'id_organizacion' => 'required|exists:organizacion,id_organizacion',
            'id_jerarquia'    => 'required|exists:jerarquia_inicial,id',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $rol = Roles::create($request->only([
                'id_rol',
                'nombre_rol',
                'nivel',
                'id_organizacion',
                'id_jerarquia',
            ]));

            $response->setData($rol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol creado correctamente');
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el rol: ' . $e->getMessage());
            return response()->json($response, $response->getStatusCode());
        }
    }

    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            $rol = Roles::with(['organizacion', 'jerarquia'])->find($id);

            if (!$rol) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Rol no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $response->setData($rol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol obtenido correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener el rol: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_rol'          => "required|unique:roles,id_rol,$id",
            'nombre_rol'      => 'required|string|max:100',
            'nivel'           => 'nullable|integer|min:1',
            'id_organizacion' => 'required|exists:organizacion,id_organizacion',
            'id_jerarquia'    => 'required|exists:jerarquia_inicial,id',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $rol = Roles::find($id);

            if (!$rol) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Rol no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $rol->update($request->only([
                'id_rol',
                'nombre_rol',
                'nivel',
                'id_organizacion',
                'id_jerarquia',
            ]));

            $response->setData($rol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol actualizado correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el rol: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();

        try {
            $rol = Roles::find($id);

            if (!$rol) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Rol no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $rol->delete();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol eliminado correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el rol: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
