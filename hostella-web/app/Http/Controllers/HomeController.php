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
         try {
             // Obtener propiedades sin "sort"
             $featuredProperties = $this->guestyService->getListings([
                 'limit' => 4 // Eliminamos el parámetro "sort" porque no es permitido
             ]);
     
             // Extraer imágenes
             $featuredImages = $this->getFeaturedImages($featuredProperties['results'] ?? []);
     
             return view('home', [
                 'featuredProperties' => $featuredProperties['results'] ?? [],
                 'featuredImages' => $featuredImages
             ]);
         } catch (\Exception $e) {
             \Log::error('Error al obtener propiedades:', ['error' => $e->getMessage()]);
             return view('home', [
                 'featuredProperties' => [],
                 'featuredImages' => []
             ]);
         }
     }
     


    /**
     * Obtener imágenes de propiedades destacadas
     */
    private function getFeaturedImages(array $properties)
    {
        $images = [];
    
        foreach ($properties as $property) {
            if (isset($property['pictures']) && is_array($property['pictures'])) {
                foreach ($property['pictures'] as $picture) {
                    if (!empty($picture['original'])) {
                        $images[] = $picture['original'];
                    }
                }
            }
        }
    
        \Log::info('Imágenes obtenidas:', $images); // Ver imágenes en logs
        return $images;
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