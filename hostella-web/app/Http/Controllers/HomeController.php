<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GuestyService;

class HomeController extends Controller
{
    protected $guestyService;

    public function __construct(GuestyService $guestyService)
    {
        $this->guestyService = $guestyService;
    }

    /**
     * Mostrar la página de inicio
     */
    public function index()
    {
        // Obtener propiedades destacadas (limitado a 3-4)
        try {
            $featuredProperties = $this->guestyService->getListings([
                'limit' => 4,
                'sort' => '-reviews.avg' // Ordenar por mejor calificación
            ]);
            
            return view('home', [
                'featuredProperties' => $featuredProperties['results'] ?? []
            ]);
        } catch (\Exception $e) {
            // Si hay un error, mostrar la página de inicio sin propiedades
            return view('home', [
                'featuredProperties' => []
            ]);
        }
    }

    /**
     * Mostrar la página Acerca de
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Mostrar la página de contacto
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Mostrar la página de servicios y experiencias
     */
    public function services()
    {
        return view('services');
    }

    /**
     * Mostrar la página para propietarios
     */
    public function forOwners()
    {
        return view('for-owners');
    }

    /**
     * Mostrar la página de FAQ
     */
    public function faq()
    {
        return view('faq');
    }
}