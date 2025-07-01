<?php

namespace App\Http\Controllers\Square;

use App\Http\Controllers\Controller;
use App\Services\SquarePaymentService;
use Illuminate\Http\Request;
use App\Models\PropertyPayment;


class CheckoutController extends Controller
{
    protected SquarePaymentService $square;

    public function __construct(SquarePaymentService $square)
    {
        $this->square = $square;
    }

    /**
     * Verifica que las ubicaciones de Square estén accesibles.
     */
    public function locations()
    {
        try {
            $locations = $this->square->listLocations();
            return response()->json($locations);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

   public function pagar(Request $request)
{
    $request->validate([
        'source_id' => 'required|string',
        'amount' => 'required|numeric|min:1',
        'listing_id' => 'required',
        'quote_id' => 'nullable|string',
        'guest_name' => 'required|string',
        'guest_email' => 'required|email',
        'guest_phone' => 'nullable|string',
        'check_in' => 'required|date',
        'check_out' => 'required|date',
        'guests_count' => 'required|integer',
        'currency' => 'required|string',
    ]);

    try {
        $result = $this->square->createPayment(
            sourceId: $request->source_id,
            amountCents: intval($request->amount),
            note: 'Pago desde Hostella'
        );

        $payment = $result->getPayment();

        $pago = PropertyPayment::create([
            'listing_id' => $request->listing_id,
            'quote_id' => $request->quote_id,
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_phone' => $request->guest_phone,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'guests_count' => $request->guests_count,
            'total_price' => $request->amount / 100,
            'currency' => $request->currency,

            'square_payment_id' => $payment->getId(),
            'payment_status' => $payment->getStatus(),
            'card_brand' => optional($payment->getCardDetails())->getCard()?->getCardBrand(),
            'last_4' => optional($payment->getCardDetails())->getCard()?->getLast4(),
            'payment_method_type' => $payment->getCardDetails() ? 'card' : 'unknown',
        ]);

        return redirect()->route('pago.exito', ['id' => $pago->id]);

    } catch (\Exception $e) {
        return redirect()->route('pago.fallo')->with('error', $e->getMessage());
    }
}




    /**
     * Procesar pago en efectivo (opcional).
     */
    public function pagarEfectivo(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'cash_given' => 'required|numeric|min:1',
        ]);

        try {
            $payment = $this->square->createCashPayment(
                amountCents: intval($request->amount * 100),
                cashGivenCents: intval($request->cash_given * 100),
                note: 'Pago en efectivo desde Hostella'
            );

            return response()->json([
                'success' => true,
                'payment' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
