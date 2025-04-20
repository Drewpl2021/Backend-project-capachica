<?php

namespace App\Http\Controllers\API\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use App\Traits\TokenHelper;
use App\Traits\ValidatorTrait;
use App\Traits\RolePermissions;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *     title="API de Autenticación",
 *     version="1.0",
 *     description="Documentación de autenticación para el sistema Capachica"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000"
 * )
 */
class AuthController extends Controller
{
    use RolePermissions, ApiResponseTrait, TokenHelper, ValidatorTrait;
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Registrar un nuevo usuario",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "password"},
     *             @OA\Property(property="name", type="string", example="juanito"),
     *             @OA\Property(property="email", type="string", example="juanito@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario registrado correctamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos inválidos"
     *     )
     * )
     */
    public function register(Request $request)
    {
        // Validación de los datos con el trait ValidatorTrait
        $validation = $this->validateRequest($request, [
            'username'     => 'required|string|max:255|unique:users', // Validamos que el nombre sea único
            'email'    => 'nullable|string|email|max:255|unique:users', // El email es opcional
            'password' => 'required|string', // La contraseña es obligatoria
        ]);

        // Si hay un error en la validación, retornamos el mensaje de error
        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->errors());
        }


        // Crear el usuario y guardarlo en la base de datos
        $user = User::create([
            'username'     => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password), // Encriptamos la contraseña
        ]);

        // Asignar rol al usuario (por defecto es 'usuario')
        $rol = $request->get('rol', 'usuario');
        $this->assignRoleToUser($user, $rol);

        // Crear token con el trait TokenHelper
        $token = $this->createToken($user);

        // Respuesta exitosa con los datos del usuario
        return $this->successResponse([
            'token' => $token,
            'user' => $user->only(['id', 'username', 'email']),
            'roles' => $user->getRoleNames(),
        ], 'Usuario registrado correctamente', 201);
    }
    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Iniciar sesión",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="email", type="string", example="juanito@example.com"),
     *             @OA\Property(property="name", type="string", example="juanito"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario iniciado sesión correctamente"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales incorrectas"
     *     )
     * )
     */
    public function login(Request $request)
    {
        // Buscar al usuario por nombre o email
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->username) // Permitimos iniciar sesión con 'name' o 'email'
            ->first();

        // Si el usuario no existe o la contraseña no coincide, retornamos error
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Credenciales incorrectas', 401);
        }

        // Crear token con el trait TokenHelper
        $token = $this->createToken($user);

        // Si no tiene rol, asignarle por defecto
        if (!$user->hasAnyRole(['admin', 'admin_familia', 'usuario'])) {
            $user->assignRole('usuario');
        }

        // Respuesta exitosa con token, datos de usuario, roles y permisos
        return $this->successResponse([
            'token' => $token,
            'username' => $user->only(['id', 'username', 'email', 'imagen_url']),
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('username'),
        ], 'Usuario iniciado sesión correctamente', 200);
    }

    /**
     * @OA\Get(
     *     path="/perfil",
     *     summary="Obtener perfil del usuario autenticado",
     *     tags={"Auth"},
     *     security={{"passport":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario autenticado"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function perfil(Request $request)
    {
        // Retornamos la información del usuario autenticado
        return $this->success($request->user());
    }
    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Cerrar sesión",
     *     tags={"Auth"},
     *     security={{"passport":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        // Revocar el token usando el trait TokenHelper
        $this->revokeToken($request->user());
        return $this->successResponse(null, 'Sesión cerrada');
    }
}
