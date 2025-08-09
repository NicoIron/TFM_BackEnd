<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JerarquiaRol;
use App\Utils\ResultResponse;

class JerarquiaRolController extends Controller
{
    public function asignar(Request $request)
    {
        $response = new ResultResponse();

        $validated = $request->validate([
            'id_rol' => 'required|exists:roles,id',
            'id_rol_superior' => 'required|exists:roles,id|different:id_rol',
        ]);

        $jerarquia = JerarquiaRol::updateOrCreate(
            ['id_rol' => $validated['id_rol']],
            ['id_rol_superior' => $validated['id_rol_superior']]
        );

        $response->setData($jerarquia);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Jerarquía asignada correctamente');
        return response()->json($response, 200);
    }

    public function obtener()
    {
        $response = new ResultResponse();
        $jerarquias = JerarquiaRol::with(['rol', 'rolSuperior'])->get();

        $response->setData($jerarquias);
        $response->setStatusCode(ResultResponse::SUCCESS_CODE);
        $response->setMessage('Listado de jerarquías');
        return response()->json($response, 200);
    }
}
