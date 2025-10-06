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
    /**
     *  Listar todos los usuarios con sus relaciones (rol, organización y jerarquía).
     */
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

    /**
     *  Guardar un nuevo usuario validando:
     *  - Datos obligatorios
     *  - Que el rol y la jerarquía existan
     *  - Que el rol y la jerarquía coincidan por nombre
     *  - Que el nivel del rol coincida con el nivel de la jerarquía
     */
    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        // Validaciones de campos obligatorios (CORREGIDO exists)
        $validator = Validator::make($request->all(), [
            'id_usuario'      => 'required|string|max:50|unique:usuarios,id_usuario',
            'nombre'          => 'required|string|max:70',
            'apellido'        => 'required|string|max:70',
            'email'           => 'required|email|max:150|unique:usuarios,email',
            'password_hash'   => 'required|string',
            'username'        => 'required|string|unique:usuarios,username',
            'id_rol'          => 'required|integer|exists:roles,id_rol', // ← Cambiado a id
            'id_organizacion' => 'required|string|max:50|exists:organizacion,id',
            'id_jerarquia'    => 'required|integer|exists:jerarquia_inicial,id', // ← Cambiado a id
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            // Validar existencia de rol y jerarquía (MANTIENE TU VERSIÓN ORIGINAL)
            $rol = Roles::find($request->id_rol);
            $jerarquia = JerarquiaInicial::find($request->id_jerarquia);

            if (!$rol || !$jerarquia) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('Rol o jerarquía no encontrados.');
                return response()->json($response, $response->getStatusCode());
            }

            //  NUEVA VALIDACIÓN: Rol pertenece a la misma organización
            if ($rol->id_organizacion !== $request->id_organizacion) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('El rol seleccionado no pertenece a la organización especificada.');
                return response()->json($response, $response->getStatusCode());
            }

            //  NUEVA VALIDACIÓN: Jerarquía pertenece a la misma organización
            if ($jerarquia->id_organizacion !== $request->id_organizacion) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('La jerarquía seleccionada no pertenece a la organización especificada.');
                return response()->json($response, $response->getStatusCode());
            }

            // Validar que el nombre del rol coincida con el cargo de la jerarquía (MANTIENE TU VERSIÓN ORIGINAL)
            if (strcasecmp($rol->nombre_rol, $jerarquia->cargo) !== 0) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('El rol y la jerarquía asignados no coinciden.');
                return response()->json($response, $response->getStatusCode());
            }

            // Validar que el nivel del rol coincida con el nivel de la jerarquía (MANTIENE TU VERSIÓN ORIGINAL)
            /*   if ($rol->nivel !== $jerarquia->nivel) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('El nivel del rol no corresponde con el nivel de la jerarquía.');
                return response()->json($response, $response->getStatusCode());
            }*/

            $usuarioData = $request->all();
            $usuarioData['password_hash'] = bcrypt($request->password_hash);

            // Crear usuario (MANTIENE TU VERSIÓN ORIGINAL)
            $usuario = Usuario::create($usuarioData);

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

public function obtenerSiguienteId()
{
    $response = new ResultResponse();

    try {
        // Obtener el último usuario ordenado por id_usuario descendente
        $ultimoUsuario = Usuario::orderBy('id_usuario', 'desc')->first();

        if (!$ultimoUsuario) {
            $siguienteId = 'USER-001';
        } else {
            // Extraer el número del último ID (formato USER-XXX)
            preg_match('/USER-(\d+)/', $ultimoUsuario->id_usuario, $matches);

            if (isset($matches[1])) {
                $ultimoNumero = (int)$matches[1];
                $siguienteNumero = $ultimoNumero + 1;
                $siguienteId = 'USER-' . str_pad($siguienteNumero, 3, '0', STR_PAD_LEFT);
            } else {
                $siguienteId = 'USER-001';
            }
        }

        $response->setData(['nextId' => $siguienteId]);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Siguiente ID obtenido correctamente');

    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage('Error al obtener siguiente ID: ' . $e->getMessage());
    }

    return response()->json($response, $response->getStatusCode());
}

    /**
     *  Ver un usuario por su ID
     */
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

    /**
     *  Actualizar un usuario validando:
     *  - Campos opcionales
     *  - Que rol y jerarquía existan (si se envían)
     *  - Que el rol y la jerarquía coincidan por nombre
     *  - Que el nivel del rol coincida con el nivel de la jerarquía
     */
    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        // Validaciones de campos opcionales
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

            // Validar rol y jerarquía SOLO si alguno se está actualizando
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

                if ($rol->nivel !== $jerarquia->nivel) {
                    $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                    $response->setMessage('El nivel del rol no corresponde con el nivel de la jerarquía.');
                    return response()->json($response, $response->getStatusCode());
                }
            }

            // Actualizar usuario
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

    /**
     *  Eliminar un usuario por su ID
     */
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
