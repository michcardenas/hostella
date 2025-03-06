<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GuestyService;

class PropertiesController extends Controller
{
    protected $guestyService;

    public function __construct(GuestyService $guestyService)
    {
        $this->guestyService = $guestyService;
    }

    /**
     * Lista todas las propiedades con filtros opcionales.
     */
    public function index(Request $request)
    {
        try {
            $properties = $this->guestyService->getListings([]);

            return view('properties.index', [
                'properties' => $properties['results'] ?? [],
                'filters' => $request->all()
            ]);
        } catch (\Exception $e) {
            return view('properties.index', [
                'properties' => [],
                'error' => 'No se pudieron cargar las propiedades. Inténtelo de nuevo más tarde.'
            ]);
        }
    }

    /**
     * Muestra una propiedad en detalle según su ID.
     */
    public function show($id)
    {
        try {
            // Obtener la propiedad por ID usando la API de Guesty
            $property = $this->guestyService->getListing($id);

            if (!$property || empty($property)) {
                return redirect()->route('properties.index')->with('error', 'Propiedad no encontrada.');
            }

            return view('properties.show', compact('property'));
        } catch (\Exception $e) {
            return redirect()->route('properties.index')->with('error', 'No se pudo cargar la propiedad. Inténtelo más tarde.');
        }
    }

    /**
     * Crear una reserva en Guesty API.
     */
    public function createReservation(Request $request, $id)
    {
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'required|email',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after:checkin_date',
            'guests' => 'required|integer|min:1',
        ]);
    
        try {
            $reservationData = [
                'reservation' => [
                    'listingId' => $id,
                    'checkInDate' => $request->checkin_date,
                    'checkOutDate' => $request->checkout_date,
                    'numGuests' => $request->guests,
                ],
                'guest' => [
                    'fullName' => $request->guest_name,
                    'email' => $request->guest_email,
                ],
                'policy' => [
                    'privacy' => ['isAccepted' => true],
                    'termsAndConditions' => ['isAccepted' => true],
                    'marketing' => ['isAccepted' => false]
                ]
            ];
    
            $response = $this->guestyService->createReservation($reservationData);
    
            return redirect()->route('properties.show', $id)->with('success', 'Reserva realizada con éxito.');
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo procesar la reserva. Inténtelo más tarde.');
        }
    }
    public function showReservationForm($id)
{
    try {
        $property = $this->guestyService->getListing($id);

        if (!$property || empty($property)) {
            return redirect()->route('properties.index')->with('error', 'Propiedad no encontrada.');
        }

        return view('properties.reservation', compact('property'));
    } catch (\Exception $e) {
        return redirect()->route('properties.index')->with('error', 'No se pudo cargar la propiedad. Inténtelo más tarde.');
    }
}
public function reserve(Request $request, $id)
{
    try {
        // Datos de la reserva
        $data = [
            "policy" => [
                "privacy" => ["isAccepted" => true],
                "termsAndConditions" => ["isAccepted" => true],
                "marketing" => ["isAccepted" => true]
            ],
            "guest" => [
                "fullName" => $request->guest_name,
                "email" => $request->guest_email
            ],
            "reservation" => [
                "listingId" => $id,
                "checkinDate" => $request->checkin_date,
                "checkoutDate" => $request->checkout_date,
                "numberOfGuests" => $request->guests
            ]
        ];

        // Llamamos al servicio para crear la reserva
        $response = $this->guestyService->createReservation($data);

        return redirect()->route('properties.show', $id)->with('success', 'Reserva realizada con éxito.');
    } catch (\Exception $e) {
        return back()->with('error', 'No se pudo procesar la reserva. Inténtelo de nuevo.');
    }
}

}
