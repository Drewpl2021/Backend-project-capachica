<?php

namespace App\Http\Controllers\api\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 0);
        $size = $request->get('size', 10);

        // Obtener las secciones con paginación
        $sections = Section::paginate($size);

        // Cambiar el índice de las páginas de Laravel
        $sections->setPageName('page');
        $sections->appends(['size' => $size]);

        $response = [
            'content' => $sections->items(),
            'currentPage' => $sections->currentPage() - 1,
            'perPage' => $sections->perPage(),
            'totalElements' => $sections->total(),
            'totalPages' => $sections->lastPage() - 1,
        ];

        return response()->json($response);
    }
    public function show($id)
    {
        $section = Section::find($id); // Busca la sección por ID (UUID)

        if (!$section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        return response()->json($section); // Devuelve la sección encontrada en formato JSON
    }
    // Método para editar una sección
    public function update(Request $request, $id)
    {
        // Validación de entrada, asegurándonos de que `status` sea un valor booleano (true o false)
        $validated = $request->validate([
            'code' => 'required|string',
            'name' => 'required|string',
            'status' => 'required|boolean', // Aseguramos que el status sea un valor booleano
        ]);

        // Buscar la sección por su ID (UUID)
        $section = Section::find($id);

        if (!$section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        // Actualizar los campos de la sección
        $section->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'status' => $validated['status'], // Guardamos el valor booleano
        ]);

        return response()->json(['message' => 'Section updated successfully', 'section' => $section]);
    }

    public function destroy($id)
    {
        // Busca la sección por su ID
        $section = Section::find($id);

        if (!$section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        // Elimina la sección
        $section->delete();

        return response()->json(['message' => 'Section deleted successfully']);
    }
}
