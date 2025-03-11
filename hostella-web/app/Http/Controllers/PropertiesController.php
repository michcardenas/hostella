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
    
            // Obtener calendario de disponibilidad desde 30 días atrás hasta 30 días adelante
            $from = now()->subDays(30)->format('Y-m-d');
            $to = now()->addDays(30)->format('Y-m-d');
            $calendar = $this->guestyService->getListingCalendar($id, $from, $to);
    
            // Extraer solo las fechas reservadas
            $bookedDates = collect($calendar)->filter(function ($day) {
                return $day['status'] === 'booked';
            })->pluck('date')->values()->toArray();
    
            return view('properties.show', compact('property', 'bookedDates'));
    
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
            'guest.name' => 'required|string|max:255',
            'guest.email' => 'required|email',
            'guest.phone' => 'nullable|string|max:20',
            'checkIn' => 'required|date',
            'checkOut' => 'required|date|after:checkIn',
            'policy.policyId' => 'required|string',
            'payment.method' => 'required|string|in:credit_card,paypal',
            'payment.amount' => 'required|numeric|min:0',
        ]);
    
        try {
            $reservationData = [
                'reservation' => [
                    'listingId' => $id,
                    'checkInDate' => $request->checkIn,
                    'checkOutDate' => $request->checkOut,
                    'numGuests' => $request->input('guests', 1),
                ],
                'guest' => [
                    'fullName' => $request->input('guest.name'),
                    'email' => $request->input('guest.email'),
                    'phone' => $request->input('guest.phone', null),
                ],
                'policy' => [
                    'policyId' => $request->input('policy.policyId'),
                    'privacy' => ['isAccepted' => true],
                    'termsAndConditions' => ['isAccepted' => true],
                    'marketing' => ['isAccepted' => false],
                ],
                'payment' => [
                    'method' => $request->input('payment.method'),
                    'amount' => $request->input('payment.amount'),
                ]
            ];
    
            $response = $this->guestyService->createReservation($reservationData);
    
            if (isset($response['error'])) {
                return back()->with('error', 'Error en la reserva: ' . $response['error']['message']);
            }
    
            return redirect()->route('properties.show', $id)->with('success', 'Reserva realizada con éxito.');
        } catch (\Exception $e) {
            \Log::error('Error en la reserva: ' . $e->getMessage());
            return back()->with('error', 'No se pudo procesar la reserva. Inténtelo más tarde.');
        }
    }
    public function calculatePrice(Request $request)
{
    try {
        $validated = $request->validate([
            'listingId' => 'required|string',
            'checkIn' => 'required|date',
            'checkOut' => 'required|date',
            'guestsCount' => 'required|integer|min:1'
        ]);
        
        // Crear una cotización formal
        $quoteData = $this->guestyService->createReservationQuote(
            $validated['listingId'],
            $validated['checkIn'],
            $validated['checkOut'],
            $validated['guestsCount']
        );
        
        // Registrar la respuesta completa para depuración
        \Log::info("Respuesta completa de Guesty:", $quoteData);
        
        // Verificar la estructura mínima necesaria
        if (!isset($quoteData['_id'])) {
            return response()->json([
                'error' => 'No se pudo obtener la cotización: Falta ID',
                'data' => $quoteData // Incluir los datos recibidos para depuración
            ], 422);
        }
        
        // Construir respuesta con los datos disponibles
        $response = [
            'quoteId' => $quoteData['_id']
        ];
        
        // Agregar datos adicionales si están disponibles
        if (isset($quoteData['expiresAt'])) {
            $response['expiresAt'] = $quoteData['expiresAt'];
        }
        
        // Extraer la información de precios desde la estructura anidada
        if (isset($quoteData['rates']) && 
            isset($quoteData['rates']['ratePlans']) && 
            !empty($quoteData['rates']['ratePlans']) &&
            isset($quoteData['rates']['ratePlans'][0]['ratePlan']['money'])) {
            
            $response['money'] = $quoteData['rates']['ratePlans'][0]['ratePlan']['money'];
        }
        
        // Extraer información de precios por día si está disponible
        if (isset($quoteData['rates']['ratePlans'][0]['days'])) {
            $response['days'] = $quoteData['rates']['ratePlans'][0]['days'];
        }
        
        return response()->json($response);
        
    } catch (\Exception $e) {
        \Log::error("Error al calcular precio: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
// En PropertiesController.php
public function redirectToGuestPortal(Request $request)
{
    $validated = $request->validate([
        'quoteId' => 'required|string',
    ]);
    
    $quoteId = $validated['quoteId'];
    $returnUrl = route('home'); // Usa una ruta que ya exista
    
    $portalUrl = $this->guestyService->getGuestPortalUrl($quoteId, $returnUrl);
    
    return redirect()->away($portalUrl);
}

// En GuestyService.php (método para obtener la URL del portal)
public function getGuestPortalUrl($quoteId, $returnUrl)
{
    try {
        $response = $this->client->post("reservations/quotes/{$quoteId}/guest-portal-link", [
            'json' => [
                'returnUrl' => $returnUrl
            ]
        ]);
        
        $data = json_decode($response->getBody(), true);
        return $data['portalUrl']; // Asegúrate de verificar la estructura exacta de la respuesta
    } catch (\Exception $e) {
        \Log::error('Error al obtener URL del portal de Guesty: ' . $e->getMessage());
        throw new \Exception('No se pudo obtener el enlace al portal de reservas: ' . $e->getMessage());
    }
}


    public function createQuote(Request $request)
{
    try {
        $validated = $request->validate([
            'listingId' => 'required|string',
            'checkIn' => 'required|date',
            'checkOut' => 'required|date',
            'guestsCount' => 'required|integer|min:1',
            'coupon' => 'nullable|string'
        ]);
        
        // Crear una cotización formal que se usará para la reserva
        $quoteData = $this->guestyService->createReservationQuote(
            $validated['listingId'],
            $validated['checkIn'],
            $validated['checkOut'],
            $validated['guestsCount'],
            $validated['coupon'] ?? null
        );
        
        if (!isset($quoteData['_id'])) {
            return response()->json(['error' => 'No se pudo crear la cotización'], 422);
        }
        
        return response()->json([
            'quoteId' => $quoteData['_id'],
            'expiresAt' => $quoteData['expiresAt'] ?? null,
            'money' => $quoteData['money'] ?? null,
            'message' => 'Cotización creada exitosamente'
        ]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
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
