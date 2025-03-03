<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GuestyService;

class PropertyController extends Controller
{
    protected $guestyService;

    public function __construct(GuestyService $guestyService)
    {
        $this->guestyService = $guestyService;
    }

    /**
     * Mostrar listado de propiedades
     */
    public function index(Request $request)
    {
        $filters = [];
        
        // Filtros de búsqueda
        if ($request->has('location')) {
            $filters['address.city'] = $request->location;
        }
        
        if ($request->has('checkin') && $request->has('checkout')) {
            // Lógica para filtrar por disponibilidad
        }
        
        // Obtener propiedades
        try {
            $properties = $this->guestyService->getListings($filters);
            return view('properties.index', [
                'properties' => $properties['results'] ?? [],
                'filters' => $request->all()
            ]);
        } catch (\Exception $e) {
            return view('properties.index', [
                'properties' => [],
                'error' => 'No se pudieron cargar las propiedades. Por favor, inténtelo de nuevo más tarde.'
            ]);
        }
    }

    /**
     * Mostrar detalles de una propiedad
     */
    public function show($id)
    {
        try {
            $property = $this->guestyService->getListing($id);
            return view('properties.show', [
                'property' => $property
            ]);
        } catch (\Exception $e) {
            return redirect()->route('properties.index')
                ->with('error', 'No se pudo encontrar la propiedad solicitada.');
        }
    }
}