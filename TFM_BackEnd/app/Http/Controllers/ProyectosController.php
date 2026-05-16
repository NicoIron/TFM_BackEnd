<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Proyectos;
use App\Models\ProyectoUsuario;
use App\Models\Usuario;

class ProyectosController extends Controller
{
    // Listar todos los proyectos de una organización
    public function listar($id_organizacion)
    {
        $response = new ResultResponse();

        try {
            $proyectos = Proyectos::where('id_organizacion', $id_organizacion)
                ->whereNull('deleted_at')
                ->get();

            $response->setData($proyectos);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Proyectos obtenidos correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener proyectos: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    // Listar proyectos asignados a un usuario
    public function listarPorUsuario($id_usuario)
    {
        $response = new ResultResponse();

        try {
            $proyectos = Proyectos::whereHas('usuarios', function ($q) use ($id_usuario) {
                $q->where('proyecto_usuarios.id_usuario', $id_usuario);
            })
                ->whereNull('deleted_at')
                ->get();

            if ($proyectos->isEmpty()) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('No se encontraron proyectos para el usuario');
                return response()->json($response, $response->getStatusCode());
            }

            $response->setData($proyectos);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Proyectos del usuario obtenidos correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al obtener proyectos del usuario: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    // Crear proyecto
    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_proyecto'     => 'required|string|max:50|unique:proyectos,id_proyecto',
            'id_organizacion' => 'required|string|exists:organizacion,id_organizacion',
            'nombre_proyecto' => 'required|string|max:100',
            'descripcion'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $proyecto = Proyectos::create($request->all());

            $response->setData($proyecto);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Proyecto creado correctamente');
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al crear el proyecto: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    // Asignar usuario a proyecto
    public function asignarUsuario(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_proyecto'     => 'required|string|exists:proyectos,id_proyecto',
            'id_usuario'      => 'required|string|exists:usuarios,id_usuario',
            'id_organizacion' => 'required|string|exists:organizacion,id_organizacion',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            // Verificar si ya está asignado
            $existe = ProyectoUsuario::where('id_proyecto', $request->id_proyecto)
                ->where('id_usuario', $request->id_usuario)
                ->whereNull('deleted_at')
                ->first();

            if ($existe) {
                $response->setStatusCode(ResultResponse::ERROR_CONFLICT_CODE);
                $response->setMessage('El usuario ya está asignado a este proyecto');
                return response()->json($response, $response->getStatusCode());
            }

            $asignacion = ProyectoUsuario::create($request->all());

            $response->setData($asignacion);
            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Usuario asignado al proyecto correctamente');
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al asignar usuario: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }

    // Quitar usuario de proyecto
    public function quitarUsuario(Request $request)
    {
        $response = new ResultResponse();

        $validator = Validator::make($request->all(), [
            'id_proyecto' => 'required|string|exists:proyectos,id_proyecto',
            'id_usuario'  => 'required|string|exists:usuarios,id_usuario',
        ]);

        if ($validator->fails()) {
            $response->setStatusCode(ResultResponse::ERROR_VALIDATION_CODE);
            $response->setMessage('Error en la validación.');
            $response->setData($validator->errors());
            return response()->json($response, $response->getStatusCode());
        }

        try {
            $asignacion = ProyectoUsuario::where('id_proyecto', $request->id_proyecto)
                ->where('id_usuario', $request->id_usuario)
                ->whereNull('deleted_at')
                ->first();

            if (!$asignacion) {
                $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
                $response->setMessage('El usuario no está asignado a este proyecto');
                return response()->json($response, $response->getStatusCode());
            }

            $asignacion->delete();

            $response->setStatusCode(ResultResponse::SUCCESS_CODE);
            $response->setMessage('Usuario removido del proyecto correctamente');
        } catch (\Exception $e) {
            $response->setStatusCode(ResultResponse::ERROR_INTERNAL_SERVER);
            $response->setMessage('Error al remover usuario: ' . $e->getMessage());
        }

        return response()->json($response, $response->getStatusCode());
    }
}
