<?php

use App\Http\Controllers\API\home\AsociacionController;
use App\Http\Controllers\API\home\EmprendedorController;
use App\Http\Controllers\API\home\ImagenSliderController;
use App\Http\Controllers\API\home\ImgAsociacionController;
use App\Http\Controllers\API\home\MunicipalidadController;
use App\Http\Controllers\API\home\MunicipalidadDescripcionController;
use App\Http\Controllers\API\home\SliderMuniController;
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

// Rutas de Logueo y Registro
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// SOLO VER MUNICIPALIDAD RUTA LIBRE
Route::get('/municipalidad_listar', [MunicipalidadController::class, 'index']);
Route::get('/municipalidad_descripcions', [MunicipalidadDescripcionController::class, 'index']);


// Rutas de Login
Route::middleware('auth:api')->group(function () {
    Route::get('/perfil', [AuthController::class, 'perfil']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('permission:editar_perfil')->put('/editar-datos', [UserController::class, 'editar']);

    Route::middleware('role:admin|admin_familia')->group(function () {
        Route::put('/usuarios/{id}', [UserController::class, 'update']);
    });

    Route::middleware('role:admin')->prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });
});

Route::middleware(['auth:api', 'role:admin|admin_familia|usuario'])->group(function () {
    // Rutas ParentModuleController
    Route::prefix('parent-module')->group(function () {
        Route::get('/page', [ParentModuleController::class, 'listPaginate']);  // Listar con paginación
        Route::get('/list', [ParentModuleController::class, 'list']);  // Listar sin paginación
        Route::get('/listar', [ParentModuleController::class, 'listar']);  // Otra lista
        Route::get('/list-detail-module-list', [ParentModuleController::class, 'listDetailModuleList']);  // Detalles de módulos
        Route::post('/', [ParentModuleController::class, 'store']);  // Crear nuevo módulo padre
        Route::get('/{id}', [ParentModuleController::class, 'show']);  // Mostrar módulo padre específico
        Route::put('/{id}', [ParentModuleController::class, 'update']);  // Actualizar módulo padre
        Route::delete('/{id}', [ParentModuleController::class, 'destroy']);  // Eliminar módulo padre
    });

    // Rutas ModuleController
    Route::prefix('module')->group(function () {
        Route::get('/page', [ModuleController::class, 'index']); // Ruta para paginación
        Route::get('/menu', [ModuleController::class, 'menu']);  // Obtener menú
        Route::post('/', [ModuleController::class, 'store']);  // Crear nuevo módulo
        Route::get('/{id}', [ModuleController::class, 'show']);  // Ver módulo específico
        Route::put('/{id}', [ModuleController::class, 'update']);  // Actualizar módulo
        Route::delete('/{id}', [ModuleController::class, 'destroy']);  // Eliminar módulo
    });
});


Route::middleware(['auth:api', 'role:admin|admin_familia|usuario'])->group(function () {
    // Prefijo 'municipalidad'
    Route::prefix('municipalidad')->group(function () {
        // Rutas para la municipalidad
        Route::middleware('permission:editar_municipalidad')->post('/crear', [MunicipalidadController::class, 'store']);
        Route::middleware('permission:editar_municipalidad')->put('/{id}', [MunicipalidadController::class, 'update']);
        Route::middleware('permission:editar_municipalidad')->delete('/{id}', [MunicipalidadController::class, 'destroy']);
        Route::get('/{id}', [MunicipalidadController::class, 'show']);
        Route::get('/code/{codigo}', [MunicipalidadController::class, 'searchByCode']);

        // Rutas para descripciones de la municipalidad

        Route::post('/descripcion/{municipalidadId}', [MunicipalidadDescripcionController::class, 'store']);
        Route::get('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'show']);
        Route::put('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'update']);
        Route::delete('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'destroy']);
    });
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
