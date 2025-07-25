<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;

class RolesController extends Controller
{
    public function listar()
    {
        $response = new ResultResponse();
        $response->setData(Roles::all());
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Listado de roles');
        return response()->json($response, 200);
    }

    public function guardar(Request $request)
    {
        $response = new ResultResponse();

        $validated = $request->validate([
            'id_organizacion' => 'required|exists:organizacion,id',
            'id_jerarquia' => 'required|exists:jerarquia,id',
            'nombre_rol' => 'required|string|max:100',
            'jefe_imediato' => 'nullable|boolean',
            'eliminado' => 'nullable|boolean'
        ]);

        $rol = Roles::create($validated);

        $response->setData($rol);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Rol creado correctamente');
        return response()->json($response, 201);
    }

    public function ver($id)
    {
        $response = new ResultResponse();
        $rol = Roles::find($id);

        if (!$rol) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Rol no encontrado');
            return response()->json($response, 404);
        }

        $response->setData($rol);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Rol encontrado');
        return response()->json($response, 200);
    }

    public function actualizar(Request $request, $id)
    {
        $response = new ResultResponse();
        $rol = Roles::find($id);

        if (!$rol) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Rol no encontrado');
            return response()->json($response, 404);
        }

        $rol->update($request->all());

        $response->setData($rol);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Rol actualizado');
        return response()->json($response, 200);
    }

    public function eliminar($id)
    {
        $response = new ResultResponse();
        $rol = Roles::find($id);

        if (!$rol) {
            $response->setStatusCode(ResultResponse::ERROR_ELEMENT_NOT_FOUND_CODE);
            $response->setMessage('Rol no encontrado');
            return response()->json($response, 404);
        }

        $rol->delete();

        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Rol eliminado');
        return response()->json($response, 200);
    }
}
