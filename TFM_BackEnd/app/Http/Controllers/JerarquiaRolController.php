<?php

namespace App\Http\Controllers;

use App\Models\JerarquiaRol;
use App\Models\Roles;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;

class JerarquiaRolController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();

        try {
            $jerarquiaRoles = JerarquiaRol::with(['jerarquia', 'rol', 'rolSuperior'])->get();
            $response->setData($jerarquiaRoles);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Lista de jerarquías y roles obtenida correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener la lista: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_jerarquia'    => 'required|exists:jerarquia_inicial,id',
            'id_rol'          => 'required|exists:roles,id|different:id_rol_superior',
            'id_rol_superior' => 'required|exists:roles,id|different:id_rol',
        ], [
            'id_rol.different' => 'El rol y el rol superior no pueden ser el mismo.',
            'id_rol_superior.required' => 'El rol superior es obligatorio.',
            'id_rol_superior.different' => 'El rol superior debe ser diferente al rol.',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        // --- Validación clave: el jefe inmediato debe tener un nivel jerárquico superior (menor número) al rol ---
        $rol = Roles::find($request->id_rol);
        $jefe = Roles::find($request->id_rol_superior);

        if (!$rol || !$jefe) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Rol o jefe inmediato no encontrado para validación de niveles.');
            return response()->json($response, $response->getStatusCode());
        }

        if ($jefe->nivel >= $rol->nivel) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('El jefe inmediato debe tener un nivel jerárquico superior (menor número) al rol.');
            return response()->json($response, $response->getStatusCode());
        }
        // --- Fin validación niveles ---

        try {
            $jerarquiaRol = JerarquiaRol::create($request->all());
            $response->setData($jerarquiaRol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Jerarquía y rol creado correctamente');
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el registro: ' . $e->getMessage());
            return response()->json($response, $response->getStatusCode());
        }
    }

    public function ver($id)
    {
        $response = new ResultResponse();

        try {
            $jerarquiaRol = JerarquiaRol::with(['jerarquia', 'rol', 'rolSuperior'])->find($id);

            if (!$jerarquiaRol) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Registro no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $response->setData($jerarquiaRol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Registro obtenido correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener el registro: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_jerarquia'    => 'required|exists:jerarquia_inicial,id',
            'id_rol'          => 'required|exists:roles,id|different:id_rol_superior',
            'id_rol_superior' => 'required|exists:roles,id|different:id_rol',
        ], [
            'id_rol.different' => 'El rol y el rol superior no pueden ser el mismo.',
            'id_rol_superior.required' => 'El rol superior es obligatorio.',
            'id_rol_superior.different' => 'El rol superior debe ser diferente al rol.',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        // --- Validación clave en actualización ---
        $rol = Roles::find($request->id_rol);
        $jefe = Roles::find($request->id_rol_superior);

        if (!$rol || !$jefe) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Rol o jefe inmediato no encontrado para validación de niveles.');
            return response()->json($response, $response->getStatusCode());
        }

        if ($jefe->nivel >= $rol->nivel) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('El jefe inmediato debe tener un nivel jerárquico superior (menor número) al rol.');
            return response()->json($response, $response->getStatusCode());
        }
        // --- Fin validación niveles ---

        try {
            $jerarquiaRol = JerarquiaRol::find($id);

            if (!$jerarquiaRol) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('Registro no encontrado');
                return response()->json($response, $response->getStatusCode());
            }

            $jerarquiaRol->update($request->all());
            $response->setData($jerarquiaRol);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Registro actualizado correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al actualizar el registro: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

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

            $jerarquiaRol->delete();
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Registro eliminado correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al eliminar el registro: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
