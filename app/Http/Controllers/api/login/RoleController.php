<?php

namespace App\Http\Controllers\API\Login;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Obtener todos los roles
     */
    public function index()
    {
        // Obtener todos los roles
        $roles = Role::all();

        return response()->json([
            'roles' => $roles,
            'count' => $roles->count(), // Contar la cantidad de roles
        ]);
    }

    /**
     * Crear un nuevo rol
     */
    public function store(Request $request)
    {
        // Validación de los datos entrantes
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
        ]);

        // Crear un nuevo rol
        $role = Role::create(['name' => $validated['name']]);

        return response()->json([
            'role' => $role,
            'message' => 'Rol creado exitosamente',
        ], 201);
    }

    /**
     * Actualizar un rol existente
     */
    public function update(Request $request, $id)
    {
        // Buscar el rol
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Rol no encontrado',
            ], 404);
        }

        // Validación de los datos entrantes
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        // Actualizar el rol
        $role->update(['name' => $validated['name']]);

        return response()->json([
            'role' => $role,
            'message' => 'Rol actualizado exitosamente',
        ]);
    }

    /**
     * Eliminar un rol
     */
    public function destroy($id)
    {
        // Buscar el rol
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Rol no encontrado',
            ], 404);
        }

        // Eliminar el rol
        $role->delete();

        return response()->json([
            'message' => 'Rol eliminado exitosamente',
        ]);
    }
}
