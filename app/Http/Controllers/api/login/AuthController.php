<?php

namespace App\Http\Controllers\api\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use App\Traits\TokenHelper;
use App\Traits\ValidatorTrait;
use App\Traits\RolePermissions;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

/**
 * @OA\Info(
 *     title="Autenticación",
 *     version="1.0",
 *     description="Documentación para gestionar autenticaciones"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000"
 * )
 */

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", description="ID del usuario"),
 *     @OA\Property(property="username", type="string", description="Nombre del usuario"),
 *     @OA\Property(property="email", type="string", description="Correo electrónico del usuario"),
 *     @OA\Property(property="imagen_url", type="string", description="URL de la imagen del usuario")
 * )
 */
class AuthController extends Controller
{
    use RolePermissions, ApiResponseTrait, TokenHelper, ValidatorTrait, HasRoles;

    /**
     * @OA\Post(
     *     path="/register",
     *     operationId="registerUserssssssssssssssssss",
     *     tags={"Auth"},
     *     summary="Registrar un nuevo usuario",
     *     description="Registrar un nuevo usuario en el sistema",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="juanito"),
     *             @OA\Property(property="email", type="string", example="juanito@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
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
            'name'     => 'required|string|max:255|unique:users',
            'last_name'     => 'required|string|max:255|unique:users',
            //'code'     => 'required|string|max:255|unique:users',
            'username'     => 'required|string|max:255|unique:users',
            'email'    => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->errors());
        }

        // Crear el usuario y guardarlo en la base de datos
        $user = User::create([
            'name'     => $request->name,
            'last_name'    => $request->last_name,
            //'code'    => $request->code,
            'username'     => $request->username,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Asignar rol al usuario (por defecto es 'usuario')
        $rol = $request->get('rol', 'usuario');
        $this->assignRoleToUser($user, $rol);

        // Crear token con JWT
        $token = JWTAuth::fromUser($user);

        return $this->successResponse([
            'token' => $token,
            'user' => $user->only(['id', 'username', 'email']),
            'roles' => $user->getRoleNames(),
        ], 'Usuario registrado correctamente', 201);
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="Iniciar sesión",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(property="email", type="string", example="juanito@example.com"),
     *             @OA\Property(property="username", type="string", example="juanito"),
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
        $user = User::where('email', $request->email)
            ->orWhere('username', $request->username)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Credenciales incorrectas', 401);
        }

        // Obtén el token actual
        $token = JWTAuth::getToken();

        // Verificar si el token se obtiene correctamente
        if ($token) {
            // Invalidar el token
            JWTAuth::invalidate($token);
            Log::info('Token invalidado');
        }

        try {
            // Generamos un nuevo token
            $newToken = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return $this->error('No se pudo crear el token', 500);
        }

        return $this->successResponse([
            'token' => $newToken,
            'expires_at' => now()->addMinutes(config('jwt.ttl'))->toDateTimeString(),
            'username' => $user->only(['id', 'name', 'last_name', 'username', 'email']),
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ], 'Usuario iniciado sesión correctamente', 200);
    }

    public function getCurrentUser(Request $request)
    {
        try {
            // Intentar obtener token del header
            $token = $request->bearerToken();

            if (!$token) {
                // No hay token, no autorizado
                return $this->error('No autorizado - no hay token', 401);
            }

            // Intentar autenticar usuario con el token recibido
            if (!$user = JWTAuth::setToken($token)->authenticate()) {
                return $this->error('No autorizado - usuario no encontrado', 401);
            }

            // Token y usuario válidos, responder con datos y token
            return $this->successResponse([
                'token' => $token,
                'username' => $user->only(['id', 'username', 'email', 'name', 'last_name']),
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ], 'Sesión activa', 200);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->error('Token expirado, inicia sesión de nuevo', 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $this->error('Token inválido', 401);
        } catch (\Exception $e) {
            // Cualquier otro error
            return $this->error('No autorizado', 401);
        }
    }







    /**
     * @OA\Get(
     *     path="/perfil",
     *     operationId="perfil",
     *     summary="Obtener perfil del usuario autenticado",
     *     tags={"Auth"},
     *     security={{"passport":{}}},  // Puedes modificar esto a "jwt" si es necesario
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
        $user = $request->user();
        return $this->successResponse([
            'username' => $user->username,
            'email' => $user->email,
        ], 'Datos del usuario obtenidos correctamente');
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     operationId="logout",
     *     summary="Cerrar sesión",
     *     tags={"Auth"},
     *     security={{"passport":{}}},  // Similar, podrías cambiar a JWT si prefieres usar seguridad JWT
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
        // Revocar el token del usuario
        JWTAuth::invalidate(JWTAuth::getToken());  // Esto invalidará el token que está en uso.
        return $this->successResponse(null, 'Sesión cerrada');
    }
}
