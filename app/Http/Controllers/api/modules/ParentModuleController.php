<?php

namespace App\Http\Controllers\API\Modules;

use App\Http\Controllers\Controller;
use App\Models\ParentModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ParentModuleController extends Controller
{
    /**
     * GET /parent-module?page=&size=&name=
     */
    public function listPaginate(Request $request)
    {
        $size = $request->input('size', 10);
        $name = $request->input('name');

        $query = ParentModule::query();

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
     * GET /parent-module/list?name=
     */
    public function list(Request $request)
    {
        $name = $request->input('name');
        $query = ParentModule::query();

        if ($name) {
            $query->where('title', 'like', "%$name%");
        }

        return response()->json($query->get());
    }

    /**
     * GET /parent-module/list-detail-module-list
     */
    public function listDetailModuleList()
    {
        $parents = ParentModule::with('modules')->get();
        return response()->json($parents);
    }

    /**
     * POST /parent-module
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'code' => 'nullable|string',
            'subtitle' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|boolean',
            'moduleOrder' => 'required|integer',
            'link' => 'required|string|max:500',
        ]);

        $module = ParentModule::create($validated);
        return response()->json($module, 201);
    }

    /**
     * GET /parent-module/{id}
     */
    public function show($id)
    {
        $module = ParentModule::findOrFail($id);
        return response()->json($module);
    }

    /**
     * PUT /parent-module/{id}
     */
    public function update(Request $request, $id)
    {
        $module = ParentModule::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'code' => 'nullable|string',
            'subtitle' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'icon' => 'nullable|string|max:100',
            'status' => 'required|boolean',
            'moduleOrder' => 'required|integer',
            'link' => 'required|string|max:500',
        ]);

        $module->update($validated);

        return response()->json($module);
    }

    /**
     * DELETE /parent-module/{id}
     */
    public function destroy($id)
    {
        $module = ParentModule::findOrFail($id);
        $module->delete();

        // Retornar la paginación por defecto como en Java
        $data = ParentModule::paginate(20);

        return response()->json([
            'message' => 'Eliminado correctamente',
            'items' => $data->items(),
            'total' => $data->total(),
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
        ]);
    }
}
