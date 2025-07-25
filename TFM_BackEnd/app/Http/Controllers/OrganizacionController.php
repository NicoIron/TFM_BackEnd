<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use Illuminate\Http\Request;
use App\Utils\ResultResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrganizacionController extends Controller
{
    public function listar()
    {
        return response()->json([
            'success' => true,
            'data' => Organizacion::all(),
            'message' => 'Listado de organizaciones'
        ]);
    }

    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $organizacion = Organizacion::create($validated);

        return response()->json([
            'success' => true,
            'data' => $organizacion,
            'message' => 'Organización creada correctamente'
        ], 201);
    }

    public function ver($id)
    {
        $organizacion = Organizacion::find($id);

        if (!$organizacion) {
            return response()->json([
                'success' => false,
                'message' => 'Organización no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $organizacion,
            'message' => 'Organización encontrada'
        ]);
    }

    public function actualizar(Request $request, $id)
    {
        $organizacion = Organizacion::find($id);

        if (!$organizacion) {
            return response()->json([
                'success' => false,
                'message' => 'Organización no encontrada'
            ], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $organizacion->update($validated);

        return response()->json([
            'success' => true,
            'data' => $organizacion,
            'message' => 'Organización actualizada'
        ]);
    }

    public function eliminar($id)
    {
        $organizacion = Organizacion::find($id);

        if (!$organizacion) {
            return response()->json([
                'success' => false,
                'message' => 'Organización no encontrada'
            ], 404);
        }

        $organizacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Organización eliminada'
        ]);
    }
}
