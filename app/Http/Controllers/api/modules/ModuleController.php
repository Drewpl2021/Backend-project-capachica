<?php

namespace App\Http\Controllers\API\Modules;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ParentModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    /**
     * GET /module?page=&size=&name=pastor
     */
    public function index(Request $request)
    {
        // Configuración del tamaño y el nombre para la paginación
        $size = $request->input('size', 20);  // Número de elementos por página
        $name = $request->input('name');       // Filtro por nombre

        // Construir la consulta para obtener los módulos
        $query = Module::with('parentModule');

        // Si se proporciona un nombre, aplicar el filtro
        if ($name) {
            $query->where('title', 'like', "%$name%");
        }

        // Realizar la paginación de la consulta
        $data = $query->paginate($size);

        // Formatear la respuesta
        return response()->json([
            'content' => $data->items(),  // Los elementos de la página actual
            'totalElements' => $data->total(),  // Total de elementos en la base de datos
            'currentPage' => $data->currentPage() - 1,  // Laravel comienza a contar desde 1, pero queremos contar desde 0
            'totalPages' => $data->lastPage(),  // Total de páginas disponibles
        ]);
    }


    /**
     * GET /module/menu
     * Simula lo que sería un DTO de tipo menú
     */
    public function menu()
    {
        // Obtén los módulos principales (padres) con sus submódulos (hijos) relacionados
        $modules = ParentModule::with('modules')->get();

        // Mapea los módulos a la estructura deseada, agregando el campo 'children'
        $menu = $modules->map(function ($parent) {
            return [
                'id' => $parent->id,
                'title' => $parent->title,
                'subtitle' => $parent->subtitle,  // Si existe el campo 'subtitle'
                'type' => $parent->type,          // Tipo de módulo
                'icon' => $parent->icon,
                'link' => $parent->link,          // Si existe el campo 'link'
                'status' => $parent->status,      // Si existe el campo 'status'
                'moduleOrder' => $parent->moduleOrder,
                'createdAt' => $parent->created_at,
                'updatedAt' => $parent->updated_at,
                'deletedAt' => $parent->deleted_at,
                'children' => $parent->modules->map(function ($mod) {
                    return [
                        'id' => $mod->id,
                        'title' => $mod->title,
                        'subtitle' => $mod->subtitle,  // Si existe el campo 'subtitle'
                        'type' => $mod->type,
                        'icon' => $mod->icon,
                        'link' => $mod->link,
                        'status' => $mod->status,
                        'moduleOrder' => $mod->moduleOrder,
                        'createdAt' => $mod->created_at,
                        'updatedAt' => $mod->updated_at,
                        'deletedAt' => $mod->deleted_at,
                        'selected' => $mod->selected, // Si es necesario incluir 'selected'
                    ];
                })
            ];
        });

        return response()->json($menu);
    }


    /**
     * POST /module
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'code' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|boolean',
            'moduleOrder' => 'required|integer',
            'link' => 'required|string|max:500',
            'parent_module_id' => 'required|uuid|exists:parent_modules,id',
        ]);

        $module = Module::create($validated);
        return response()->json($module, 201);
    }

    /**
     * GET /module/{id}
     */
    public function show($id)
    {
        $module = Module::with('parentModule')->findOrFail($id);
        return response()->json($module);
    }

    /**
     * GET /module/modules-selected/roleId/{roleId}/parentModuleId/{parentModuleId}
     */
    public function modulesSelected($roleId, $parentModuleId)
    {
        // Este ejemplo devuelve los módulos del parent con el ID dado
        // En un sistema real, deberías verificar qué módulos están asignados al rol
        $modules = Module::where('parent_module_id', $parentModuleId)->get();

        // Aquí se simula que algunos están seleccionados según lógica de roles
        $response = $modules->map(function ($mod) {
            return [
                'id' => $mod->id,
                'title' => $mod->title,
                'selected' => false, // aquí pondrías la lógica real si usas permisos/roles
            ];
        });

        return response()->json($response);
    }

    /**
     * PUT /module/{id}
     */
    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'subtitle' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'code' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|boolean',
            'moduleOrder' => 'required|integer',
            'link' => 'required|string|max:500',
            'parent_module_id' => 'required|uuid|exists:parent_modules,id',
        ]);

        $module->update($validated);

        return response()->json($module);
    }

    /**
     * DELETE /module/{id}
     */
    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        $data = Module::paginate(20);
        return response()->json([
            'message' => 'Módulo eliminado',
            'items' => $data->items(),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
        ]);
    }
}
