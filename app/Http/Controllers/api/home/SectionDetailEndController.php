<?php

namespace App\Http\Controllers\API\home;

use App\Http\Controllers\Controller;
use App\Models\SectionDetailEnd;
use Illuminate\Http\Request;


class SectionDetailEndController extends Controller
{
    // Método para listar todos los SectionDetailEnds
    public function index()
    {
        // Obtiene todos los detalles finales
        $sectionDetailEnds = SectionDetailEnd::all();
        return response()->json($sectionDetailEnds); // Devuelve los detalles en formato JSON
    }

    // Método para listar SectionDetailEnd por su ID
    public function show($id)
    {
        // Busca el SectionDetailEnd por ID (UUID)
        $sectionDetailEnd = SectionDetailEnd::find($id);

        if (!$sectionDetailEnd) {
            return response()->json(['message' => 'SectionDetailEnd not found'], 404);
        }

        return response()->json($sectionDetailEnd);  // Devuelve el detalle en formato JSON
    }

    // Método para listar SectionDetailEnd por SectionDetail ID (FK)
    public function getBySectionDetailId($section_detail_id)
    {
        // Busca los detalles que corresponden a un SectionDetail específico
        $sectionDetailEnds = SectionDetailEnd::where('section_detail_id', $section_detail_id)->get();

        if ($sectionDetailEnds->isEmpty()) {
            return response()->json(['message' => 'No details found for this section detail'], 404);
        }

        return response()->json($sectionDetailEnds); // Devuelve los detalles en formato JSON
    }

    // Método para actualizar un SectionDetailEnd
    public function update(Request $request, $id)
    {
        // Validación de los datos de entrada. Los campos son opcionales (nullable).
        $validated = $request->validate([
            'status' => 'nullable|boolean',
            'code' => 'nullable|string',
            'image' => 'nullable|string',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'section_detail_id' => 'nullable|uuid' // Validación opcional para la clave foránea
        ]);

        // Busca el SectionDetailEnd por su ID (UUID)
        $sectionDetailEnd = SectionDetailEnd::find($id);

        if (!$sectionDetailEnd) {
            return response()->json(['message' => 'SectionDetailEnd not found'], 404);
        }

        // Si el campo está presente, lo actualizamos
        if (isset($validated['status'])) {
            $sectionDetailEnd->status = $validated['status'];
        }
        if (isset($validated['code'])) {
            $sectionDetailEnd->code = $validated['code'];
        }
        if (isset($validated['image'])) {
            $sectionDetailEnd->image = $validated['image'];
        }
        if (isset($validated['title'])) {
            $sectionDetailEnd->title = $validated['title'];
        }
        if (isset($validated['description'])) {
            $sectionDetailEnd->description = $validated['description'];
        }
        if (isset($validated['subtitle'])) {
            $sectionDetailEnd->subtitle = $validated['subtitle'];
        }
        if (isset($validated['section_detail_id'])) {
            $sectionDetailEnd->section_detail_id = $validated['section_detail_id'];
        }

        // Guardamos los cambios
        $sectionDetailEnd->save();

        return response()->json(['message' => 'SectionDetailEnd updated successfully', 'sectionDetailEnd' => $sectionDetailEnd]);
    }

    // Método para eliminar un SectionDetailEnd
    public function destroy($id)
    {
        // Busca el SectionDetailEnd por su ID (UUID)
        $sectionDetailEnd = SectionDetailEnd::find($id);

        if (!$sectionDetailEnd) {
            return response()->json(['message' => 'SectionDetailEnd not found'], 404);
        }

        // Elimina el SectionDetailEnd
        $sectionDetailEnd->delete();

        return response()->json(['message' => 'SectionDetailEnd deleted successfully']);
    }
}
