<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Roles;
use App\Models\JerarquiaInicial;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class UsuarioController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();

        try {
            $usuarios = Usuario::with(['rol', 'organizacion', 'jerarquia'])->get();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Listado de usuarios obtenido correctamente');
            $response->setData($usuarios);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener los usuarios: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_usuario'      => 'required|string|max:50|unique:usuarios,id_usuario',
            'nombre'          => 'required|string|max:100',
            'apellido'        => 'required|string|max:100',
            'email'           => 'required|email|max:150|unique:usuarios,email',
            'password_hash'   => 'required|string',
            'username'        => 'required|string|unique:usuarios,username',
            'id_rol'          => 'required|integer|exists:roles,id',
            'id_organizacion' => 'required|string|max:50|exists:organizacion,id_organizacion',
            'id_jerarquia'    => 'required|integer|exists:jerarquia_inicial,id',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        // Validación extra para asegurar que id_rol y id_jerarquia correspondan
        // Esto evita inconsistencias entre el rol asignado y la jerarquía del usuario
        $rol = Roles::find($request->id_rol);
        $jerarquia = JerarquiaInicial::find($request->id_jerarquia);

        if (!$rol || !$jerarquia) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Rol o jerarquía no encontrados.');
            return response()->json($response, $response->getStatusCode());
        }

        // Validación que el nombre del rol coincida con el cargo de la jerarquía
        if (strcasecmp($rol->nombre_rol, $jerarquia->cargo) !== 0) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('El rol y la jerarquía asignados no coinciden.');
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $usuario = Usuario::create($request->all());
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Usuario creado correctamente');
            $response->setData($usuario);
            return response()->json($response, 201);
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Error de conflicto: ' . $e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el usuario: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            $usuario = Usuario::with(['rol', 'organizacion', 'jerarquia'])->find($id);

            if (!$usuario) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Usuario no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Usuario encontrado');
            $response->setData($usuario);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener el usuario: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_usuario'      => 'sometimes|string|max:50|unique:usuarios,id_usuario,' . $id,
            'nombre'          => 'sometimes|string|max:100',
            'apellido'        => 'sometimes|string|max:100',
            'email'           => 'sometimes|email|max:150|unique:usuarios,email,' . $id,
            'password_hash'   => 'sometimes|string',
            'username'        => 'sometimes|string|unique:usuarios,username,' . $id,
            'id_rol'          => 'sometimes|integer|exists:roles,id',
            'id_organizacion' => 'sometimes|string|max:50|exists:organizacion,id_organizacion',
            'id_jerarquia'    => 'sometimes|integer|exists:jerarquia_inicial,id',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $usuario = Usuario::find($id);

            if (!$usuario) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Usuario no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Si actualizan id_rol o id_jerarquia, validar que coincidan
            if ($request->has('id_rol') || $request->has('id_jerarquia')) {
                $rolId = $request->get('id_rol', $usuario->id_rol);
                $jerarquiaId = $request->get('id_jerarquia', $usuario->id_jerarquia);

                $rol = Roles::find($rolId);
                $jerarquia = JerarquiaInicial::find($jerarquiaId);

                if (!$rol || !$jerarquia) {
                    $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                    $response->setMessage('Rol o jerarquía no encontrados.');
                    return response()->json($response, $response->getStatusCode());
                }

                if (strcasecmp($rol->nombre_rol, $jerarquia->cargo) !== 0) {
                    $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                    $response->setMessage('El rol y la jerarquía asignados no coinciden.');
                    return response()->json($response, $response->getStatusCode());
                }
            }

            $usuario->update($request->all());
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Usuario actualizado correctamente');
            $response->setData($usuario);
        } catch (QueryException $e) {
            $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
            $response->setMessage('Error de conflicto: ' . $e->getMessage());
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el usuario: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();

        try {
            $usuario = Usuario::find($id);

            if (!$usuario) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Usuario no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $usuario->delete();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Usuario eliminado correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el usuario: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
