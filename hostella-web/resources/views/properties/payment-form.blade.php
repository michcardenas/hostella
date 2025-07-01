@extends('layouts.app')

@section('title', 'Formulario de Pago')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header text-white" style="background-color: #02006a;">
                    <h4 class="mb-0">💳 Formulario de Pago</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Estás a punto de realizar un pago seguro por
                        <strong>${{ number_format($totalPrice, 2) }} {{ $currency }}</strong>.
                        Completa los datos de tu tarjeta para continuar.
                    </p>

                    <!-- Formulario de tarjeta -->
                    <div id="payment-form-container" class="mb-4"></div>

                    <!-- Detalles de envío -->
                    <div class="border rounded p-3 mb-4 bg-light">
                        <h6 class="mb-2 text-dark">🔍 Datos que se enviarán con el pago:</h6>
                        <ul class="list-unstyled small mb-0">
                            <li><strong>Monto:</strong> ${{ $totalPrice }} {{ $currency }}</li>
                            <li><strong>Nombre:</strong> {{ $guestName ?? '—' }}</li>
                            <li><strong>Email:</strong> {{ $guestEmail ?? '—' }}</li>
                            <li><strong>Teléfono:</strong> {{ $guestPhone ?? '—' }}</li>
                            <li><strong>Check-In:</strong> {{ $checkIn ?? '—' }}</li>
                            <li><strong>Check-Out:</strong> {{ $checkOut ?? '—' }}</li>
                            <li><strong>Huéspedes:</strong> {{ $guestsCount ?? '—' }}</li>
                        </ul>
                    </div>

                    <!-- Campos ocultos -->
                    <input type="hidden" id="amount" value="{{ $totalPrice }}">
                    <input type="hidden" id="currency" value="{{ $currency }}">
                    <input type="hidden" id="guestName" value="{{ $guestName ?? '' }}">
                    <input type="hidden" id="guestEmail" value="{{ $guestEmail ?? '' }}">
                    <input type="hidden" id="guestPhone" value="{{ $guestPhone ?? '' }}">
                    <input type="hidden" id="listingId" value="{{ $listingId ?? '' }}">
                    <input type="hidden" id="checkIn" value="{{ $checkIn ?? '' }}">
                    <input type="hidden" id="checkOut" value="{{ $checkOut ?? '' }}">
                    <input type="hidden" id="quoteId" value="{{ $quoteId ?? '' }}">
                    <input type="hidden" id="guestsCount" value="{{ $guestsCount ?? '' }}">

                    <!-- Botones -->
                    <div class="d-grid gap-2">
                        <button id="card-button" class="btn text-white" style="background-color: #02006a;">Pagar ahora</button>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>

                    <!-- Resultado -->
                    <div id="payment-status-container" class="alert mt-3 d-none" role="alert"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Square SDK -->
<script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        const payments = Square.payments(
            "{{ env('SQUARE_APPLICATION_ID') }}",
            "{{ env('SQUARE_LOCATION_ID') }}"
        );

        const card = await payments.card();
        await card.attach("#payment-form-container");

        const button = document.getElementById('card-button');

        button.addEventListener('click', async function () {
            const result = await card.tokenize();
            if (result.status !== 'OK') {
                alert("❌ No se pudo procesar el método de pago. Intenta con otra tarjeta.");
                return;
            }

            // Capturar los campos ocultos
            const payload = {
                source_id: result.token,
                amount: parseInt(document.getElementById('amount').value) * 100,
                currency: document.getElementById('currency').value,
                listing_id: document.getElementById('listingId').value,
                quote_id: document.getElementById('quoteId').value,
                check_in: document.getElementById('checkIn').value,
                check_out: document.getElementById('checkOut').value,
                guests_count: parseInt(document.getElementById('guestsCount').value),
                guest_name: document.getElementById('guestName').value,
                guest_email: document.getElementById('guestEmail').value,
                guest_phone: document.getElementById('guestPhone').value
            };

            try {
                const response = await fetch("{{ route('square.pagar') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(payload),
                    redirect: "follow"  // importante
                });

                // Si la respuesta fue redirección (Laravel lo hará)
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    // Si no redirige, es porque hubo error
                    const json = await response.json();
                    alert("❌ Error: " + (json.error || "Pago fallido"));
                }

            } catch (error) {
                alert("❌ Error inesperado durante el proceso de pago.");
            }
        });
    });
</script>

@endsection