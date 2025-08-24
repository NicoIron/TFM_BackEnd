<?php

namespace App\Http\Controllers;

use App\Models\JerarquiaRol;
use App\Models\Roles;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;

class JerarquiaRolController extends Controller
{
    // Método para listar todas las jerarquías de roles
    public function listar()
    {
        $response = new ResultResponse();

        try {
            // Se obtienen los registros junto con sus relaciones: jerarquía, rol e inmediato superior
            $jerarquiaRoles = JerarquiaRol::with(['jerarquia', 'rol', 'rolSuperior'])->get();
            $response->setData($jerarquiaRoles);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Lista de jerarquías y roles obtenida correctamente');
        } catch (\Exception $e) {
            // Manejo de error en caso de fallo en la consulta
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener la lista: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    // Método para guardar una nueva relación de jerarquía y rol
    // Método para guardar una nueva relación de jerarquía y rol
    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        // PRIMERO obtener el rol para saber si es Comité Operativo
        $rol = Roles::find($request->id_rol);

        if (!$rol) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Rol no encontrado.');
            return response()->json($response, $response->getStatusCode());
        }

        // Validación CONDICIONAL de datos de entrada
        if ($rol->nombre_rol === "Comite Operativo") {
            // Para Comité Operativo: no requiere rol superior
            $validator = Validator::make($request->all(), [
                'id_jerarquia'    => 'required|exists:jerarquia_inicial,id',
                'id_rol'          => 'required|exists:roles,id',
                'id_rol_superior' => 'nullable|different:id_rol', // nullable en lugar de required
            ], [
                'id_rol_superior.different' => 'El rol superior debe ser diferente al rol.',
            ]);
        } else {
            // Para otros roles: validación original
            $validator = Validator::make($request->all(), [
                'id_jerarquia'    => 'required|exists:jerarquia_inicial,id',
                'id_rol'          => 'required|exists:roles,id|different:id_rol_superior',
                'id_rol_superior' => 'required|exists:roles,id|different:id_rol',
            ], [
                'id_rol.different' => 'El rol y el rol superior no pueden ser el mismo.',
                'id_rol_superior.required' => 'El rol superior es obligatorio.',
                'id_rol_superior.different' => 'El rol superior debe ser diferente al rol.',
            ]);
        }

        if ($validator->fails()) {
            // Si la validación falla, se devuelve error con detalles
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        // --- Validación de jerarquía ---
        // Para Comité Operativo, saltar validación de niveles
        if ($rol->nombre_rol !== "Comite Operativo") {
            // Se busca el jefe inmediato (solo para roles que no son Comité Operativo)
            $rolSuperior = Roles::find($request->id_rol_superior);

            // Verificar que exista en BD
            if (!$rolSuperior) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('Jefe inmediato no encontrado para validación de niveles.');
                return response()->json($response, $response->getStatusCode());
            }

            // Validación clave: el jefe inmediato debe tener un nivel más alto (menor número)
            if ($rolSuperior->nivel >= $rol->nivel) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('El jefe inmediato debe tener un nivel jerárquico superior (menor número) al rol.');
                return response()->json($response, $response->getStatusCode());
            }
        }
        // --- Fin validación niveles ---

        try {
            // Para Comité Operativo, forzar id_rol_superior a null
            if ($rol->nombre_rol === "Comite Operativo") {
                $request->merge(['id_rol_superior' => null]);
            }

            // Se guarda el registro
            $jerarquiaRol = JerarquiaRol::create($request->all());
            $response->setData($jerarquiaRol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Jerarquía y rol creado correctamente');
            return response()->json($response, 201);
        } catch (\Exception $e) {
            // Manejo de error en la inserción
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el registro: ' . $e->getMessage());
            return response()->json($response, $response->getStatusCode());
        }
    }

    // Método para ver un registro por ID
    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            // Se busca el registro con sus relaciones
            $jerarquiaRol = JerarquiaRol::with(['jerarquia', 'rol', 'rolSuperior'])->find($id);

            if (!$jerarquiaRol) {
                // Si no existe, error de elemento no encontrado
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Registro no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Si existe, se devuelve correctamente
            $response->setData($jerarquiaRol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Registro obtenido correctamente');
        } catch (\Exception $e) {
            // Manejo de error en la consulta
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener el registro: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    // Método para actualizar un registro existente
    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        // Validación de entrada (los campos son opcionales pero deben ser válidos si se envían)
        $validator = Validator::make($request->all(), [
            'id_jerarquia'    => 'sometimes|required|exists:jerarquia_inicial,id',
            'id_rol'          => 'sometimes|required|exists:roles,id|different:id_rol_superior',
            'id_rol_superior' => 'sometimes|required|exists:roles,id|different:id_rol',
        ], [
            'id_rol.different' => 'El rol y el rol superior no pueden ser el mismo.',
            'id_rol_superior.required' => 'El rol superior es obligatorio.',
            'id_rol_superior.different' => 'El rol superior debe ser diferente al rol.',
        ]);

        if ($validator->fails()) {
            // Error de validación
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        // Validar niveles SOLO si vienen ambos campos
        if ($request->has('id_rol') && $request->has('id_rol_superior')) {
            $rol = Roles::find($request->id_rol);
            $jefe = Roles::find($request->id_rol_superior);

            if (!$rol || !$jefe) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('Rol o jefe inmediato no encontrado para validación de niveles.');
                return response()->json($response, $response->getStatusCode());
            }

            // Validación de jerarquía (el jefe debe estar en nivel superior)
            if ($jefe->nivel >= $rol->nivel) {
                $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
                $response->setMessage('El jefe inmediato debe tener un nivel jerárquico superior (menor número) al rol.');
                return response()->json($response, $response->getStatusCode());
            }
        }

        try {
            // Se busca el registro
            $jerarquiaRol = JerarquiaRol::find($id);

            if (!$jerarquiaRol) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Registro no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Se actualiza con los nuevos datos
            $jerarquiaRol->update($request->all());
            $response->setData($jerarquiaRol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Registro actualizado correctamente');
        } catch (\Exception $e) {
            // Error en la actualización
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el registro: ' . $e->getMessage());
        }
        return response()->json($response, $response->getStatusCode());
    }

    // Método para eliminar un registro por ID
    public function eliminar($id)
    {
        $response = new ResultResponse();

        try {
            $jerarquiaRol = JerarquiaRol::find($id);

            if (!$jerarquiaRol) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Registro no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            // Eliminación física del registro
            $jerarquiaRol->delete();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Registro eliminado correctamente');
        } catch (\Exception $e) {
            // Manejo de error en eliminación
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el registro: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
