<?php

namespace App\Http\Controllers\API\Modules;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\ParentModule;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ModuleController extends Controller
{
    use ApiResponseTrait;
    /**
     * GET /module?page=&size=&name=pastor
     */
    public function index(Request $request)
    {
        $size = $request->input('size', 10);
        $name = $request->input('name');

        $query = Module::with('parentModule');

        if ($name) {
            $query->where('title', 'like', "%$name%");
        }

        $data = $query->paginate($size);

        // Usamos items() para obtener la colección y luego aplicamos map()
        $response = collect($data->items())->map(function ($module) {
            return [
                'id' => $module->id,
                'title' => $module->title,
                'subtitle' => $module->subtitle,
                'type' => $module->type,
                'code' => $module->code,
                'icon' => $module->icon,
                'status' => $module->status,
                'moduleOrder' => $module->moduleOrder,
                'link' => $module->link,
                'createdAt' => $module->created_at,
                'updatedAt' => $module->updated_at,
                'deletedAt' => $module->deleted_at,
                'parentModule' => [
                    'id' => $module->parentModule->id,
                    'title' => $module->parentModule->title,
                    'code' => $module->parentModule->code,
                    'subtitle' => $module->parentModule->subtitle,
                ]
            ];
        });

        return $this->successResponse([
            'content' => $response,
            'totalElements' => $data->total(),
            'currentPage' => $data->currentPage() - 1,
            'totalPages' => $data->lastPage(),
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
                'id' => $parent->id,
                'title' => $parent->title,
                'subtitle' => $parent->subtitle,
                'type' => $parent->type,
                'icon' => $parent->icon,
                'link' => $parent->link,
                'status' => $parent->status,
                'moduleOrder' => $parent->moduleOrder,
                'createdAt' => $parent->created_at,
                'updatedAt' => $parent->updated_at,
                'deletedAt' => $parent->deleted_at,
                'children' => $parent->modules->map(function ($mod) {
                    return [
                        'id' => $mod->id,
                        'title' => $mod->title,
                        'subtitle' => $mod->subtitle,
                        'type' => $mod->type,
                        'icon' => $mod->icon,
                        'link' => $mod->link,
                        'status' => $mod->status,
                        'moduleOrder' => $mod->moduleOrder,
                        'createdAt' => $mod->created_at,
                        'updatedAt' => $mod->updated_at,
                        'deletedAt' => $mod->deleted_at,
                        'selected' => $mod->selected,
                    ];
                })
            ];
        });
        return $this->successResponse($menu);
    }


    /**
     * POST /module
     */
    public function store(Request $request)
    {
        $request->merge([
            'parent_module_id' => $request->input('parentModuleId')
        ]);

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

        return $this->successResponse($module, 'Módulo creado con éxito', 201);
    }


    /**
     * GET /module/{id}
     */
    public function show($id)
    {
        $module = Module::with('parentModule')->findOrFail($id);

        $formattedModule = [
            'id' => $module->id,
            'title' => $module->title,
            'subtitle' => $module->subtitle,
            'type' => $module->type,
            'code' => $module->code,
            'icon' => $module->icon,
            'status' => $module->status,
            'moduleOrder' => $module->moduleOrder,
            'link' => $module->link,
            'createdAt' => $module->created_at,
            'updatedAt' => $module->updated_at,
            'deletedAt' => $module->deleted_at,
            'parentModule' => [
                'id' => $module->parentModule->id,
                'title' => $module->parentModule->title,
                'code' => $module->parentModule->code,
                'subtitle' => $module->parentModule->subtitle,
            ]
        ];

        return $this->successResponse($formattedModule);
    }


    /**
     * GET /module/modules-selected/roleId/{roleId}/parentModuleId/{parentModuleId}
     */
    public function modulesSelected($roleId, $parentModuleId)
    {
        $modules = Module::where('parent_module_id', $parentModuleId)->get();

        $response = $modules->map(function ($mod) {
            return [
                'id' => $mod->id,
                'title' => $mod->title,
                'selected' => false,
            ];
        });

        return $this->successResponse($response);
    }

    /**
     * PUT /module/{id}
     */
    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);

        $request->merge([
            'parent_module_id' => $request->input('parentModuleId')
        ]);

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

        return $this->successResponse($module);
    }

    /**
     * DELETE /module/{id}
     */
    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        $data = Module::paginate(20);
        return $this->successResponse([
            'message' => 'Módulo eliminado',
            'items' => $data->items(),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
        ]);
    }
}
