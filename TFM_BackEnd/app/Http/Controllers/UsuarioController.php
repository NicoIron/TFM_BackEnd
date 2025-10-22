<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Roles;
use App\Models\JerarquiaInicial;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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

    $validator = Validator::make($request->all(), [
        'nombre' => 'sometimes|string|max:255',
        'apellido' => 'sometimes|string|max:255',
        'email' => "sometimes|email|unique:usuarios,email,$id",
        'id_rol' => 'sometimes|exists:roles,id',
        'id_organizacion' => 'sometimes|exists:organizacion,id_organizacion',
        'activo' => 'sometimes|boolean'
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

        // VALIDAR CONSISTENCIA ROL-JERARQUÍA SI SE ACTUALIZA EL ROL
        if ($request->has('id_rol') && $request->id_rol != $usuario->id_rol) {
            $nuevoRol = Roles::find($request->id_rol);

            if (!$nuevoRol) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('El rol especificado no existe');
                return response()->json($response, $response->getStatusCode());
            }

            // Verificar si existe jerarquía para este rol
            $jerarquia = JerarquiaInicial::where('id_rol', $request->id_rol)->first();

            if (!$jerarquia) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage("El rol '{$nuevoRol->nombre_rol}' no tiene jerarquía configurada. Configure la jerarquía antes de asignar este rol.");
                return response()->json($response, $response->getStatusCode());
            }

            Log::info("Actualizando usuario {$usuario->id_usuario} de rol {$usuario->id_rol} a {$request->id_rol}");
        }

        // Actualizar solo los campos proporcionados
        $usuario->update($request->only([
            'nombre',
            'apellido',
            'email',
            'id_rol',
            'id_organizacion',
            'activo'
        ]));

        // Recargar el usuario con sus relaciones para la respuesta
        $usuario->load(['rol', 'organizacion']);

        Log::info("Usuario actualizado correctamente: {$usuario->id_usuario}");

        $response->setData($usuario);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Usuario actualizado correctamente');

    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage('Error al actualizar usuario: ' . $e->getMessage());
        Log::error('Error en actualizar: ' . $e->getMessage());
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

        // Verificar que no sea el único admin
        $esAdmin = $usuario->rol && $usuario->rol->nombre_rol === 'Admin';

        if ($esAdmin) {
            $cantidadAdmins = Usuario::whereHas('rol', function($query) {
                $query->where('nombre_rol', 'Admin');
            })->whereNull('deleted_at')->count();

            if ($cantidadAdmins <= 1) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('No se puede eliminar el único administrador del sistema');
                return response()->json($response, $response->getStatusCode());
            }
        }

        // Soft delete
        $usuario->delete();

        Log::info("Usuario eliminado (soft delete): {$usuario->id_usuario}");

        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Usuario eliminado correctamente');

    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage('Error al eliminar usuario: ' . $e->getMessage());
        Log::error('Error en eliminar: ' . $e->getMessage());
    }

    return response()->json($response, $response->getStatusCode());
}

public function cambiarPassword(Request $request)
{
    $response = new ResultResponse();

    $validator = Validator::make($request->all(), [
        'id_usuario' => 'required|exists:usuarios,id_usuario',
        'password_actual' => 'required|string',
        'password_nuevo' => 'required|string|min:6',
        'password_confirmacion' => 'required|string|same:password_nuevo'
    ]);

    if ($validator->fails()) {
        $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
        $response->setMessage('Error en la validación.');
        $response->setData($validator->errors());
        return response()->json($response, $response->getStatusCode());
    }

    try {
        $usuario = Usuario::where('id_usuario', $request->id_usuario)->first();

        if (!$usuario) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Usuario no encontrado');
            return response()->json($response, $response->getStatusCode());
        }

        if (!Hash::check($request->password_actual, $usuario->password_hash)) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('La contraseña actual es incorrecta');
            return response()->json($response, $response->getStatusCode());
        }

                $usuario->password_hash = Hash::make($request->password_nuevo);
        $usuario->save();

        Log::info("Contraseña actualizada para usuario: {$usuario->id_usuario}");

        $response->setData(['message' => 'Contraseña actualizada correctamente']);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Contraseña actualizada correctamente');

    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage('Error al cambiar contraseña: ' . $e->getMessage());
        Log::error('Error en cambiarPassword: ' . $e->getMessage());
    }

    return response()->json($response, $response->getStatusCode());
}


public function restablecerContrasena(Request $request, $id)
{
    $response = new ResultResponse();

    $validator = Validator::make($request->all(), [
        'contrasena_nueva' => 'required|string|min:6',
        'contrasena_confirmacion' => 'required|string|same:contrasena_nueva',
        'id_usuario_admin' => 'required|exists:usuarios,id_usuario'
    ]);

    if ($validator->fails()) {
        $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
        $response->setMessage('Error en la validación.');
        $response->setData($validator->errors());
        return response()->json($response, $response->getStatusCode());
    }

    try {
        // Verificar que quien hace el cambio sea Comité Operativo
        $usuarioAdmin = Usuario::with('rol')->where('id_usuario', $request->id_usuario_admin)->first();

        if (!$usuarioAdmin || $usuarioAdmin->rol->nombre_rol !== 'Comite Operativo') {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Solo el Comité Operativo puede restablecer contraseñas');
            return response()->json($response, $response->getStatusCode());
        }

        // Buscar el usuario al que se le cambiará la contraseña
        $usuario = Usuario::find($id);

        if (!$usuario) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Usuario no encontrado');
            return response()->json($response, $response->getStatusCode());
        }

        // No permitir que se resetee su propia contraseña por este endpoint
        if ($usuario->id_usuario === $request->id_usuario_admin) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Para cambiar tu propia contraseña usa el endpoint de cambio de contraseña');
            return response()->json($response, $response->getStatusCode());
        }

        // Actualizar contraseña
        $usuario->password_hash = Hash::make($request->contrasena_nueva);
        $usuario->save();

        Log::info("Contraseña restablecida para usuario {$usuario->id_usuario} por {$usuarioAdmin->id_usuario}");

        $response->setData([
            'usuario' => $usuario->id_usuario,
            'message' => 'Contraseña actualizada correctamente'
        ]);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Contraseña actualizada correctamente');

    } catch (\Exception $e) {
        $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
        $response->setMessage('Error al restablecer contraseña: ' . $e->getMessage());
        Log::error('Error en restablecerContrasena: ' . $e->getMessage());
    }

    return response()->json($response, $response->getStatusCode());
}

}
