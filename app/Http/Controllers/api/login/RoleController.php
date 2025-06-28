<?php

namespace App\Http\Controllers\API\login;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Module;

/**
 * @OA\Info(
 *     title="Roles",
 *     version="1.0",
 *     description="Documentación para gestionar roles y asignar permisos a los usuarios en el sistema Capachica"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     @OA\Property(property="id", type="integer", description="ID del rol"),
 *     @OA\Property(property="name", type="string", description="Nombre del rol")
 * )
 */
class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/roles",
     *     summary="Obtener todos los roles",
     *     tags={"Roles"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de roles obtenida correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="roles", type="array", @OA\Items(ref="#/components/schemas/Role")),
     *             @OA\Property(property="count", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener roles"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $pageSize = $request->get('size', 10);
        $page = $request->get('page', 0);
        $name = $request->get('name'); // 🔍 Variable de búsqueda

        // Construir query con filtro si hay búsqueda
        $query = Role::query();

        if (!empty($name)) {
            $query->where('name', 'like', '%' . $name . '%')
                ->orWhere('description', 'like', '%' . $name . '%'); // Permitir búsqueda por ID
        }

        // Paginar
        $roles = $query->paginate($pageSize, ['*'], 'page', $page + 1); // base 1 para Laravel

        return response()->json([
            'content' => $roles->items(),
            'totalElements' => $roles->total(),
            'currentPage' => $roles->currentPage() - 1,
            'totalPages' => $roles->lastPage()
        ]);
    }



    /**
     * @OA\Post(
     *     path="/roles",
     *     summary="Crear un nuevo rol",
     *     tags={"Roles"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rol creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        return response()->json([
            'role' => $role,
            'message' => 'Rol creado exitosamente',
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/roles/{id}",
     *     summary="Actualizar un rol",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol a actualizar",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="editor")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Rol no encontrado',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $validated['name']]);

        return response()->json([
            'role' => $role,
            'message' => 'Rol actualizado exitosamente',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/role/{id}",
     *     summary="Obtener un rol por ID",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol a obtener",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol obtenido correctamente",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        // Buscar el rol por su id
        $role = Role::find($id);

        if (!$role) {
            // Si el rol no existe, devolver error 404
            return response()->json([
                'message' => 'Rol no encontrado',
            ], 404);
        }

        // Si el rol es encontrado, devolver el rol en la respuesta
        return response()->json([
            'role' => $role,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/roles/{id}",
     *     summary="Eliminar un rol",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol a eliminar",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Rol no encontrado',
            ], 404);
        }

        $role->delete();

        return response()->json([
            'message' => 'Rol eliminado exitosamente',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/roles/{userId}/assign",
     *     summary="Asignar un rol a un usuario",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         required=true,
     *         description="ID del usuario al que se le asignará el rol",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rol asignado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *             @OA\Property(property="role", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Rol inválido"
     *     )
     * )
     */
    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado',
            ], 404);
        }

        $role = Role::findByName($request->role);
        $user->assignRole($role);

        return response()->json([
            'message' => 'Rol asignado exitosamente',
            'user' => $user,
            'role' => $role->name,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/roles/{roleId}/assign-modules",
     *     summary="Asignar módulos a un rol usando IDs UUID",
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         name="roleId",
     *         in="path",
     *         required=true,
     *         description="ID del rol al que se le asignarán los módulos",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"modules"},
     *             @OA\Property(property="modules", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Módulos asignados exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="role", ref="#/components/schemas/Role"),
     *             @OA\Property(property="modules", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rol no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Módulos inválidos"
     *     )
     * )
     */
    public function assignModulesToRole(Request $request, $roleId)
    {
        // Validación de los módulos enviados (verificar que sean UUIDs válidos)
        $request->validate([
            'modules' => 'required|array',
            'modules.*' => 'uuid|exists:modules,id', // Validar que cada módulo sea un UUID y exista en la tabla 'modules'
        ]);

        // Buscar el rol por ID
        $role = Role::find($roleId);

        if (!$role) {
            Log::error("Rol con ID $roleId no encontrado.");
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        // Log para saber que rol estamos actualizando
        Log::info("Rol con ID $roleId encontrado. Asignando módulos.");

        // Obtener los módulos por sus IDs UUID
        $modules = Module::whereIn('id', $request->modules)->get();

        if ($modules->isEmpty()) {
            Log::error("No se encontraron módulos con los IDs proporcionados.");
            return response()->json(['message' => 'Módulos no encontrados'], 422);
        }

        // Asignar los módulos al rol
        foreach ($modules as $module) {
            // Sincronizar los módulos con el rol (sin eliminar los módulos previamente asignados)
            $role->modules()->syncWithoutDetaching([$module->id]);
            Log::info("Módulo con ID {$module->id} asignado al rol con ID $roleId.");
        }

        // Devolver la respuesta con los módulos asignados
        return response()->json([
            'message' => 'Módulos asignados exitosamente',
            'role' => $role,
            'modules' => $modules->pluck('id'),
        ]);
    }
}
