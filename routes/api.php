<?php

use App\Http\Controllers\API\home\ImagenSliderController;
use App\Http\Controllers\API\home\MunicipalidadController;
use App\Http\Controllers\API\home\MunicipalidadDescripcionController;
use App\Http\Controllers\API\home\SliderMuniController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Login\AuthController;
use App\Http\Controllers\API\Login\UserController;
use App\Http\Controllers\API\Modules\ModuleController;
use App\Http\Controllers\API\Modules\ParentModuleController;
use App\Http\Controllers\AsociacionController;
use App\Http\Controllers\EmprendedorController;
use App\Http\Controllers\ImgAsociacionController;

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
Route::get('/parent-module/listar', [ParentModuleController::class, 'listar']);


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


// Rutas públicas para Municipalidad y MunicipioDescrip
Route::prefix('municipalidad')->group(function () {
    Route::get('/', [MunicipalidadController::class, 'index']);
    Route::post('/', [MunicipalidadController::class, 'store']);
    Route::get('/{id}', [MunicipalidadController::class, 'show']);
    Route::put('/{id}', [MunicipalidadController::class, 'update']);
    Route::delete('/{id}', [MunicipalidadController::class, 'destroy']);

    Route::get('/{municipalidadId}/descripcion', [MunicipalidadDescripcionController::class, 'index']); // Descripciones de una municipalidad
    Route::post('/{municipalidadId}/descripcion', [MunicipalidadDescripcionController::class, 'store']); // Crear descripción
    Route::get('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'show']); // Obtener una descripción
    Route::put('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'update']); // Actualizar descripción
    Route::delete('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'destroy']); // Eliminar descripción
});

// Rutas protegidas para Slider y ImagenSlider
Route::prefix('slider')->group(function () {
    // Rutas para SliderMuniController
    Route::get('/', [SliderMuniController::class, 'index']);
    Route::post('/', [SliderMuniController::class, 'store']);
    Route::get('/{id}', [SliderMuniController::class, 'show']);
    Route::put('/{id}', [SliderMuniController::class, 'update']);
    Route::delete('/{id}', [SliderMuniController::class, 'destroy']);

    // Rutas para ImagenSliderController
    Route::get('/imagen', [ImagenSliderController::class, 'index']);
    Route::post('/imagen', [ImagenSliderController::class, 'store']);
    Route::get('/imagen/{id}', [ImagenSliderController::class, 'show']);
    Route::put('/imagen/{id}', [ImagenSliderController::class, 'update']);
    Route::delete('/imagen/{id}', [ImagenSliderController::class, 'destroy']);
});

// Rutas para las asociaciones
Route::prefix('asociacion')->group(function () {
    Route::get('/', [AsociacionController::class, 'index']); // Obtener todas las asociaciones
    Route::post('/', [AsociacionController::class, 'store']); // Crear nueva asociación
    Route::get('/{id}', [AsociacionController::class, 'show']); // Mostrar una asociación específica
    Route::put('/{id}', [AsociacionController::class, 'update']); // Actualizar asociación
    Route::delete('/{id}', [AsociacionController::class, 'destroy']); // Eliminar asociación
});

// Rutas para los emprendedores
Route::prefix('emprendedor')->group(function () {
    Route::get('/', [EmprendedorController::class, 'index']); // Obtener todos los emprendedores
    Route::post('/', [EmprendedorController::class, 'store']); // Crear nuevo emprendedor
    Route::get('/{id}', [EmprendedorController::class, 'show']); // Mostrar un emprendedor específico
    Route::put('/{id}', [EmprendedorController::class, 'update']); // Actualizar emprendedor
    Route::delete('/{id}', [EmprendedorController::class, 'destroy']); // Eliminar emprendedor
});
// Rutas para las imágenes de las asociaciones
Route::prefix('img-asociacion')->group(function () {
    Route::get('/', [ImgAsociacionController::class, 'index']); // Obtener todas las imágenes
    Route::post('/', [ImgAsociacionController::class, 'store']); // Crear nueva imagen
    Route::get('/{id}', [ImgAsociacionController::class, 'show']); // Mostrar imagen específica
    Route::put('/{id}', [ImgAsociacionController::class, 'update']); // Actualizar imagen
    Route::delete('/{id}', [ImgAsociacionController::class, 'destroy']); // Eliminar imagen
});
