<?php

namespace App\Http\Controllers\API\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

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
class UserController extends Controller
{
    /**
     * Obtener todos los usuarios con roles y permisos
     *
     * @OA\Get(
     *     path="/users",
     *     summary="Obtener todos los usuarios",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios con roles y permisos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     )
     * )
     */
    public function index()
    {
        // Obtener todos los usuarios con sus roles y permisos
        $users = User::with('roles', 'permissions')->get();

        // Mapeamos los resultados para incluir todos los campos necesarios
        $response = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'imagen_url' => $user->imagen_url,
                'roles' => $user->roles->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'guard_name' => $role->guard_name,
                    ];
                }),
                'permissions' => $user->permissions->map(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'guard_name' => $permission->guard_name,
                    ];
                }),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'deleted_at' => $user->deleted_at,
            ];
        });

        // Retornar la respuesta con los datos de los usuarios
        return response()->json($response);
    }


    /**
     * Crear un nuevo usuario
     *
     * @OA\Post(
     *     path="/users",
     *     summary="Crear un nuevo usuario",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "email", "password"},
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="role", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'username'     => 'required',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:6',
            'role'         => 'nullable|string'
        ]);

        // Crear el usuario
        $user = User::create([
            'username'     => $request->username,
            'email'        => $request->email,
            'password'     => bcrypt($request->password),
        ]);

        // Asignar el rol si se proporcionó
        if ($request->role) {
            $user->assignRole($request->role);
        }

        // Retornar la respuesta con el nuevo usuario
        return response()->json($user, 201);
    }

    /**
     * Mostrar un usuario específico
     *
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Mostrar un usuario",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        // Obtener el usuario con sus roles y permisos
        return User::with('roles', 'permissions')->findOrFail($id);
    }

    /**
     * Actualizar un usuario existente
     *
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Actualizar un usuario",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "email"},
     *             @OA\Property(property="username", type="string", example="john_doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="role", type="string", example="user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        // Obtener el usuario
        $user = User::findOrFail($id);

        // Actualizar los datos del usuario
        $user->update($request->only(['username', 'email']));

        // Si se proporciona una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        // Actualizar el rol si se proporciona
        if ($request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        return response()->json($user);
    }

    /**
     * Eliminar un usuario
     *
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Eliminar un usuario",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuario eliminado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        // Eliminar el usuario por ID
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
}
