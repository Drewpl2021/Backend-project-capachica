<?php

use App\Http\Controllers\API\home\AsociacionController;
use App\Http\Controllers\API\home\EmprendedorController;
use App\Http\Controllers\API\home\ImgAsociacionController;
use App\Http\Controllers\API\home\MunicipalidadController;
use App\Http\Controllers\API\home\MunicipalidadDescripcionController;
use App\Http\Controllers\API\home\SliderMuniController;
use App\Http\Controllers\API\home\SectionController;
use App\Http\Controllers\API\home\SectionDetailController;
use App\Http\Controllers\API\home\SectionDetailEndController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Login\AuthController;
use App\Http\Controllers\API\Login\RoleController;
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
Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});


// Rutas de Logueo y Registro
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// SOLO VER MUNICIPALIDAD RUTA LIBRE
Route::get('/municipalidad', [MunicipalidadController::class, 'index']);
Route::get('/municipalidad/descripcion', [MunicipalidadDescripcionController::class, 'index']);
Route::get('/asociaciones', [AsociacionController::class, 'index']); // Obtener todas las asociaciones
Route::get('/img-asociacionesTotal', [ImgAsociacionController::class, 'index']); // Obtener todas las imágenes


Route::get('/parent-module/test', [ParentModuleController::class, 'listPaginate']);  // Listar con paginación
Route::post('/parent-module/test', [ParentModuleController::class, 'store']);  // Crear nuevo módulo padre
Route::get('/parent-module/test/{id}', [ParentModuleController::class, 'show']);  // Mostrar módulo padre específico
Route::put('/parent-module/test/{id}', [ParentModuleController::class, 'update']);  // Actualizar módulo padre
Route::delete('/parent-module/test/{id}', [ParentModuleController::class, 'destroy']);


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

Route::prefix('role')->middleware('auth:api')->group(function () {
    // Obtener todos los roles y su cantidad
    Route::get('/', [RoleController::class, 'index']);
    Route::get('/{id}', [RoleController::class, 'show']);
    Route::middleware('permission:editar_roles')->post('/', [RoleController::class, 'store']);

    Route::middleware('permission:editar_roles')->put('/{id}', [RoleController::class, 'update']);

    Route::middleware('permission:editar_roles')->delete('/{id}', [RoleController::class, 'destroy']);

    Route::middleware('permission:editar_roles')->post('/assign-role/{userId}', [RoleController::class, 'assignRole']);
});

Route::middleware(['auth:api', 'role:admin|admin_familia|usuario'])->group(function () {
    // Rutas ParentModuleController
    Route::prefix('parent-module')->group(function () {
        Route::get('/', [ParentModuleController::class, 'listPaginate']);  // Listar con paginación
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
        Route::get('/', [ModuleController::class, 'index']); // Ruta para paginación
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
        //BUSCAR MUNICIPALIDAD CON SUS ASOCIACIONES
        Route::get('/asociaciones/{id}', [MunicipalidadController::class, 'asociacionesByMunicipalidad']);


        // Rutas para descripciones de la municipalidad
        Route::post('/descripcion/{municipalidadId}', [MunicipalidadDescripcionController::class, 'store']);
        Route::get('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'show']);
        Route::put('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'update']);
        Route::delete('/descripcion/{id}', [MunicipalidadDescripcionController::class, 'destroy']);
    });

    // Rutas para las asociaciones
    Route::prefix('asociacion')->group(function () {
        Route::post('/', [AsociacionController::class, 'store']); // Crear nueva asociación
        Route::get('/{id}', [AsociacionController::class, 'show']); // Mostrar una asociación específica
        Route::put('/{id}', [AsociacionController::class, 'update']); // Actualizar asociación
        Route::delete('/{id}', [AsociacionController::class, 'destroy']); // Eliminar asociación
        Route::get('/emprendedores/{id}', [AsociacionController::class, 'emprendedoresByAsociacion']);
    });

    // Rutas para los emprendedores
    Route::prefix('emprendedor')->group(function () {
        Route::get('/', [EmprendedorController::class, 'index']); // Obtener todos los emprendedores
        Route::post('/', [EmprendedorController::class, 'store']); // Crear nuevo emprendedor
        Route::get('/{id}', [EmprendedorController::class, 'show']); // Mostrar un emprendedor específico
        Route::put('/{id}', [EmprendedorController::class, 'update']); // Actualizar emprendedor
        Route::delete('/{id}', [EmprendedorController::class, 'destroy']); // Eliminar emprendedor
    });



    // Rutas protegidas para Slider y ImagenSlider
    Route::prefix('slider')->group(function () {
        // Rutas para SliderMuniController
        Route::get('/', [SliderMuniController::class, 'index']);
        Route::post('/', [SliderMuniController::class, 'store']);
        Route::get('/{id}', [SliderMuniController::class, 'show']);
        Route::put('/{id}', [SliderMuniController::class, 'update']);
        Route::delete('/{id}', [SliderMuniController::class, 'destroy']);
    });

    // Rutas para las imágenes de las asociaciones
    Route::prefix('img-asociacion')->group(function () {
        Route::post('/', [ImgAsociacionController::class, 'store']); // Crear nueva imagen
        Route::get('/{id}', [ImgAsociacionController::class, 'show']); // Mostrar imagen específica
        Route::put('/{id}', [ImgAsociacionController::class, 'update']); // Actualizar imagen
        Route::delete('/{id}', [ImgAsociacionController::class, 'destroy']); // Eliminar imagen
        Route::get('/img/{asociacionId}', [ImgAsociacionController::class, 'getImagesByAsociacionId']);
    });
});























// RUTAS PARA ANGULAR LIBRE PARA HACER SECTIONS
// Rutas para las secciones
Route::prefix('sections')->group(function () {
    Route::get('/', [SectionController::class, 'index']);
    Route::get('/{id}', [SectionController::class, 'show']);
    Route::put('/{id}', [SectionController::class, 'update']);
    Route::delete('/{id}', [SectionController::class, 'destroy']);
});
Route::prefix('sectionDetails')->group(function () {
    Route::get('/', [SectionDetailController::class, 'index']);  // Listar todos los detalles
    Route::get('/byId/{section_id}', [SectionDetailController::class, 'getBySectionId']);  // Buscar por section_id
    Route::get('/{id}', [SectionDetailController::class, 'show']);  // Buscar por ID
    Route::put('/{id}', [SectionDetailController::class, 'update']);  // Actualizar un detalle
    Route::delete('/{id}', [SectionDetailController::class, 'destroy']);  // Eliminar un detalle
});
Route::prefix('sectionDetailEnds')->group(function () {
    Route::get('/', [SectionDetailEndController::class, 'index']);  // Listar todos los SectionDetailEnds
    Route::get('/{id}', [SectionDetailEndController::class, 'show']);  // Listar SectionDetailEnd por ID
    Route::get('/byId/{section_detail_id}', [SectionDetailEndController::class, 'getBySectionDetailId']);  // Buscar por section_detail_id
    Route::put('/{id}', [SectionDetailEndController::class, 'update']);  // Actualizar SectionDetailEnd
    Route::delete('/{id}', [SectionDetailEndController::class, 'destroy']);  // Eliminar SectionDetailEnd
});
