<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();

        try {
            $roles = Roles::with(['organizacion', 'jerarquia'])->get();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Listado de roles');
            $response->setData($roles);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener roles: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_rol'         => 'required|string|max:50|unique:roles,id_rol',
            'nombre_rol'     => 'required|string|max:100',
            'jefe_inmediato' => 'nullable|string|max:100',
            'id_organizacion'=> 'required|string|max:50|exists:organizacion,id_organizacion',
            'id_jerarquia'   => 'required|integer|exists:jerarquia_inicial,id',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $role = Roles::create($validator->validated());
            $response->setStatusCode(201);
            $response->setMessage('Rol creado correctamente');
            $response->setData($role);
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Conflicto al crear el rol: ' . $e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error interno: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            $role = Roles::with(['organizacion', 'jerarquia'])->find($id);

            if (!$role) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Rol no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol encontrado');
            $response->setData($role);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener el rol: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        $role = Roles::find($id);
        if (!$role) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Rol no encontrado');
            return response()->json($response, $response->getStatusCode());
        }

        $validator = Validator::make($request->all(), [
            'id_rol'         => 'sometimes|string|max:50|unique:roles,id_rol,' . $id,
            'nombre_rol'     => 'sometimes|string|max:100',
            'jefe_inmediato' => 'nullable|string|max:100',
            'id_organizacion'=> 'sometimes|string|max:50|exists:organizacion,id_organizacion',
            'id_jerarquia'   => 'sometimes|integer|exists:jerarquia_inicial,id',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $role->update($validator->validated());
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol actualizado correctamente');
            $response->setData($role);
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Conflicto al actualizar el rol: ' . $e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error interno: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();

        $role = Roles::find($id);
        if (!$role) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Rol no encontrado');
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $role->delete();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol eliminado correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el rol: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
