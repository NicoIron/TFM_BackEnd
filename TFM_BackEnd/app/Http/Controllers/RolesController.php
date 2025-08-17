<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    /**
     * Listar todos los roles con sus relaciones (organización y jerarquía).
     */
    public function listar()
    {
        $response = new ResultResponse();

        try {
            // Obtiene todos los roles con las relaciones definidas en el modelo
            $roles = Roles::with(['organizacion', 'jerarquia'])->get();

            $response->setData($roles);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Lista de roles obtenida correctamente');
        } catch (\Exception $e) {
            // Manejo de errores al listar
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener la lista de roles: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    /**
     * Guardar un nuevo rol en la base de datos.
     */
    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        // Validaciones de entrada
        $validator = Validator::make($request->all(), [
            'id_rol'          => 'required|unique:roles,id_rol',
            'nombre_rol'      => 'required|string|max:100',
            'nivel'           => 'nullable|integer|min:1', // nivel jerárquico
            'id_organizacion' => 'required|exists:organizacion,id_organizacion',
            'id_jerarquia'    => 'required|exists:jerarquia_inicial,id',
        ]);

        if ($validator->fails()) {
            // Respuesta de error de validación
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            // Crea el nuevo rol con los datos recibidos
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
            // Manejo de errores al crear
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el rol: ' . $e->getMessage());
            return response()->json($response, $response->getStatusCode());
        }
    }

    /**
     * Ver un rol en específico según su ID.
     */
    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            // Busca el rol por ID con sus relaciones
            $rol = Roles::with(['organizacion', 'jerarquia'])->find($id);

            if (!$rol) {
                // Si no existe, retorna error
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Rol no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $response->setData($rol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol obtenido correctamente');
        } catch (\Exception $e) {
            // Manejo de errores al buscar
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener el rol: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    /**
     * Actualizar un rol existente.
     */
    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        // Validaciones de entrada
        $validator = Validator::make($request->all(), [
            'id_rol'          => "required|unique:roles,id_rol,$id",
            'nombre_rol'      => 'required|string|max:100',
            'nivel'           => 'nullable|integer|min:1',
            'id_organizacion' => 'required|exists:organizacion,id_organizacion',
            'id_jerarquia'    => 'required|exists:jerarquia_inicial,id',
        ]);

        if ($validator->fails()) {
            // Respuesta de error de validación
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            // Busca el rol por ID
            $rol = Roles::find($id);

            if (!$rol) {
                // Si no existe, retorna error
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Rol no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Actualiza con los datos recibidos
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
            // Manejo de errores al actualizar
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el rol: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    /**
     * Eliminar un rol existente.
     */
    public function eliminar($id)
    {
        $response = new ResultResponse();

        try {
            // Busca el rol por ID
            $rol = Roles::find($id);

            if (!$rol) {
                // Si no existe, retorna error
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Rol no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Elimina el rol
            $rol->delete();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Rol eliminado correctamente');
        } catch (\Exception $e) {
            // Manejo de errores al eliminar
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el rol: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
