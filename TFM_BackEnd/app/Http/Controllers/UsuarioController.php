<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Muestra un listado de todos los usuarios.
     * GET /usuarios
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     * GET /usuarios/create
     */
    public function create()
    {
        // Normalmente se retorna una vista, en APIs no se usa.
        return response()->json(['message' => 'Mostrar formulario de creación']);
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     * POST /usuarios
     */
    public function store(Request $request)
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

        // Crea el usuario
        $usuario = Usuario::create($validated);

        return response()->json($usuario, 201);
    }

    /**
     * Muestra los detalles de un solo usuario.
     * GET /usuarios/{id}
     */
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json($usuario);
    }

    /**
     * Muestra el formulario para editar un usuario.
     * GET /usuarios/{id}/edit
     */
    public function edit($id)
    {
        // Normalmente se retorna una vista, en APIs no se usa.
        return response()->json(['message' => "Mostrar formulario de edición para el usuario $id"]);
    }

    /**
     * Actualiza un usuario existente.
     * PUT/PATCH /usuarios/{id}
     */
    public function update(Request $request, $id)
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

        return response()->json($usuario);
    }

    /**
     * Elimina un usuario de forma lógica (marcar como eliminado).
     * DELETE /usuarios/{id}
     */
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->eliminado = true;
        $usuario->save();

        return response()->json(['message' => 'Usuario eliminado lógicamente']);
    }
}
