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
     * GET /module?page=&size=&name=
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 20);
        $name = $request->input('name');

        $query = Module::with('parentModule');

        if ($name) {
            $query->where('title', 'like', "%$name%");
        }

        $data = $query->paginate($size);

        return response()->json([
            'items' => $data->items(),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
        ]);
    }

    /**
     * GET /module/menu
     * Simula lo que sería un DTO de tipo menú
     */
    public function menu()
    {
        $modules = ParentModule::with('modules')->get();

        $menu = $modules->map(function ($parent) {
            return [
                'title' => $parent->title,
                'icon' => $parent->icon,
                'modules' => $parent->modules->map(function ($mod) {
                    return [
                        'id' => $mod->id,
                        'title' => $mod->title,
                        'link' => $mod->link,
                        'icon' => $mod->icon,
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
