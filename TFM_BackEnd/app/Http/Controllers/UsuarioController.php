<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;

class UsuarioController extends Controller
{
    public function listar()
    {
        $respuesta = new ResultResponse();
        $respuesta->setData(Usuario::all());
        $respuesta->setMessage('Listado de usuarios');
        $respuesta->setStatusCode(ResultResponse::SUCCESS_CODE);
        return response()->json($respuesta, 200);
    }

    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'id_organizacion' => 'required|integer',
            'id_rol' => 'required|integer',
            'id_usuario' => 'required|string|unique:usuarios',
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'proyecto' => 'nullable|string',
            'id_empleado' => 'nullable|string',
            'correo' => 'required|email|unique:usuarios',
            'contraseña' => 'required|string|min:6',
        ]);

        $usuario = Usuario::create($validated);

        $respuesta = new ResultResponse();
        $respuesta->setData($usuario);
        $respuesta->setMessage('Usuario creado');
        $respuesta->setStatusCode(ResultResponse::SUCCESS_CODE);

        return response()->json($respuesta, 201);
    }

    public function ver($id)
    {
        $usuario = Usuario::findOrFail($id);

        $respuesta = new ResultResponse();
        $respuesta->setData($usuario);
        $respuesta->setMessage('Usuario encontrado');
        $respuesta->setStatusCode(ResultResponse::SUCCESS_CODE);

        return response()->json($respuesta, 200);
    }

    public function actualizar(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $validated = $request->validate([
            'id_organizacion' => 'sometimes|integer',
            'id_rol' => 'sometimes|integer',
            'id_usuario' => 'sometimes|string|unique:usuarios,id_usuario,' . $id,
            'nombre' => 'sometimes|string',
            'apellido' => 'sometimes|string',
            'proyecto' => 'nullable|string',
            'id_empleado' => 'nullable|string',
            'correo' => 'sometimes|email|unique:usuarios,correo,' . $id,
            'contraseña' => 'sometimes|string|min:6',
        ]);

        $usuario->update($validated);

        $respuesta = new ResultResponse();
        $respuesta->setData($usuario);
        $respuesta->setMessage('Usuario actualizado');
        $respuesta->setStatusCode(ResultResponse::SUCCESS_CODE);

        return response()->json($respuesta, 200);
    }

    public function eliminar($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->eliminado = true;
        $usuario->save();

        $respuesta = new ResultResponse();
        $respuesta->setMessage('Usuario eliminado lógicamente');
        $respuesta->setStatusCode(ResultResponse::SUCCESS_CODE);

        return response()->json($respuesta, 200);
    }
}
