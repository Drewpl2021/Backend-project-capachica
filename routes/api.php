<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Login\AuthController;
use App\Http\Controllers\API\Login\UserController;
use App\Http\Controllers\API\Modules\ModuleController;
use App\Http\Controllers\API\Modules\ParentModuleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas autenticadas
Route::middleware('auth:api')->group(function () {

    // Perfil y logout para cualquier usuario autenticado
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas con permisos (solo los usuarios con el permiso de editar perfil pueden hacerlo)
    Route::middleware('permission:editar_perfil')->put('/editar-datos', [UserController::class, 'editar']);

    // Rutas accesibles para admins y admin_familia
    Route::middleware('role:admin|admin_familia')->group(function () {
        Route::put('/usuarios/{id}', [UserController::class, 'update']);
    });

    // Rutas exclusivas para administradores (admin)
    Route::middleware('role:admin')->prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
});

// Rutas protegidas solo para admin y admin_familia
Route::middleware(['auth:api', 'role:admin|admin_familia|usuario'])->group(function () {

    // Rutas ParentModuleController
    Route::prefix('parent-module')->group(function () {
        Route::get('/', [ParentModuleController::class, 'listPaginate']);
        Route::get('/list', [ParentModuleController::class, 'list']);
        Route::get('/list-detail-module-list', [ParentModuleController::class, 'listDetailModuleList']);
        Route::post('/', [ParentModuleController::class, 'store']);
        Route::get('/{id}', [ParentModuleController::class, 'show']);
        Route::put('/{id}', [ParentModuleController::class, 'update']);
        Route::delete('/{id}', [ParentModuleController::class, 'destroy']);
    });

    // Rutas ModuleController
    Route::prefix('module')->group(function () {
        Route::get('/', [ModuleController::class, 'index']);
        Route::get('/menu', [ModuleController::class, 'menu']);
        Route::post('/', [ModuleController::class, 'store']);
        Route::get('/{id}', [ModuleController::class, 'show']);
        Route::put('/{id}', [ModuleController::class, 'update']);
        Route::delete('/{id}', [ModuleController::class, 'destroy']);

        Route::get('/modules-selected/roleId/{roleId}/parentModuleId/{parentModuleId}', [ModuleController::class, 'modulesSelected']);
    });
});
