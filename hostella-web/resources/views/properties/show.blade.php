@extends('layouts.app')

@section('title', $property['title'] ?? 'Detalle de Propiedad')

@section('content')
<div class="container py-5">
    <!-- Título de la propiedad -->
    <h1 class="fw-bold text-center">{{ $property['title'] ?? 'Sin título' }}</h1>
    <p class="text-muted text-center"><i class="fas fa-map-marker-alt"></i> {{ $property['address']['full'] ?? 'Ubicación no disponible' }}</p>

    <!-- Galería de imágenes -->
    <div class="row g-2">
        @if(isset($property['pictures']) && count($property['pictures']) >= 4)
            <div class="col-md-6">
                <img src="{{ $property['pictures'][0]['original'] ?? asset('images/property-placeholder.jpg') }}" 
                     class="img-fluid rounded w-100 h-100 object-fit-cover">
            </div>
            <div class="col-md-3">
                <img src="{{ $property['pictures'][1]['original'] ?? asset('images/property-placeholder.jpg') }}" 
                     class="img-fluid rounded w-100 mb-2 object-fit-cover">
                <img src="{{ $property['pictures'][2]['original'] ?? asset('images/property-placeholder.jpg') }}" 
                     class="img-fluid rounded w-100 object-fit-cover">
            </div>
            <div class="col-md-3">
                <img src="{{ $property['pictures'][3]['original'] ?? asset('images/property-placeholder.jpg') }}" 
                     class="img-fluid rounded w-100 h-100 object-fit-cover">
            </div>
        @else
            <div class="col-12">
                <img src="{{ asset('images/property-placeholder.jpg') }}" class="img-fluid rounded w-100">
            </div>
        @endif
    </div>

    <div class="text-center mt-2">
        <button class="btn btn-link text-decoration-none" data-bs-toggle="modal" data-bs-target="#imageModal">Ver todas las imágenes</button>
    </div>

    <!-- Contenedor de información y formulario sticky -->
    <div class="row mt-4">
        <div class="col-md-7">
            <div class="bg-light p-3 rounded mb-3">
                <h3 class="fw-bold">${{ $property['prices']['basePrice'] ?? 'N/A' }} <small>/ noche</small></h3>
                <p class="text-muted">Moneda: {{ $property['prices']['currency'] ?? 'USD' }}</p>
            </div>

            <ul class="list-unstyled">
                <li><strong>Habitaciones:</strong> {{ $property['bedrooms'] }}</li>
                <li><strong>Camas:</strong> {{ $property['beds'] }}</li>
                <li><strong>Baños:</strong> {{ $property['bathrooms'] }}</li>
                <li><strong>Capacidad máxima:</strong> {{ $property['accommodates'] }} huéspedes</li>
            </ul>

            <!-- Sección de detalles con "Ver más" -->
            @php
                $sections = [
                    'summary' => 'Descripción',
                    'space' => 'Espacio',
                    'neighborhood' => 'Ubicación',
                    'houseRules' => 'Reglas de la Casa'
                ];
            @endphp
            
            @foreach ($sections as $key => $title)
                @php
                    $content = $property['publicDescription'][$key] ?? 'No disponible';
                    $shortContent = Str::limit($content, 200);
                @endphp
                <h4>{{ $title }}</h4>
                <p id="{{ $key }}">
                    <span class="short-text">{{ $shortContent }}</span>
                    <span class="d-none full-text">{{ $content }}</span>
                    @if(strlen($content) > 200)
                        <a href="#" class="text-primary see-more" data-target="{{ $key }}">Ver más</a>
                    @endif
                </p>
            @endforeach
        </div>

       <!-- 📌 FORMULARIO STICKY PARA RESERVAR -->
       <div class="col-md-5">
            <div class="sticky-form">
                <div class="calendar-container text-center">
                    <div id="calendar"></div>
                    <form id="reservationForm" action="{{ route('properties.reserve', $property['_id']) }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" id="checkIn" name="checkIn">
                        <input type="hidden" id="checkOut" name="checkOut">

                        <!-- 🏠 Datos del Huésped -->
                        <h5 class="mb-2">Datos del Huésped</h5>
                        <div class="mb-2 form-floating">
                            <input type="text" id="guestName" name="guest[name]" class="form-control" required placeholder="Nombre Completo">
                            <label for="guestName">Nombre Completo</label>
                        </div>
                        <div class="mb-2 form-floating">
                            <input type="email" id="guestEmail" name="guest[email]" class="form-control" required placeholder="Correo Electrónico">
                            <label for="guestEmail">Correo Electrónico</label>
                        </div>
                        <div class="mb-2 form-floating">
                            <input type="text" id="guestPhone" name="guest[phone]" class="form-control" required placeholder="Teléfono">
                            <label for="guestPhone">Teléfono</label>
                        </div>

                        <!-- 📜 Política de Reserva -->
                        <h5 class="mt-3 mb-2">Política de Reserva</h5>
                        <div class="mb-2">
                            <select id="policyId" name="policy[policyId]" class="form-select" required>
                                <option value="flexible">Flexible</option>
                                <option value="moderate">Moderada</option>
                                <option value="strict">Estricta</option>
                            </select>
                        </div>

                        <!-- 💳 Datos de Pago -->
                        <h5 class="mt-3 mb-2">Datos de Pago</h5>
                        <div class="mb-2">
                            <select id="paymentMethod" name="payment[method]" class="form-select" required>
                                <option value="credit_card">Tarjeta de Crédito</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                        <div class="mb-2 form-floating">
                            <input type="text" id="paymentAmount" name="payment[amount]" class="form-control" readonly placeholder="Monto Total">
                            <label for="paymentAmount">Monto Total</label>
                        </div>

                        <!-- Botón de Reservar -->
                        <button type="submit" id="reserveBtn" class="btn btn-primary w-100 mt-2" disabled>Reservar Ahora</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal para todas las imágenes -->
<div class="modal fade" id="imageModal">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Todas las imágenes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    @if(isset($property['pictures']))
                        @foreach($property['pictures'] as $picture)
                            <div class="col-6">
                                <img src="{{ $picture['original'] ?? asset('images/property-placeholder.jpg') }}" class="img-fluid rounded">
                            </div>
                        @endforeach
                    @else
                        <p>No hay imágenes disponibles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const bookedDates = @json($bookedDates);

    // Elemento para mostrar desglose de precios
    const priceBreakdown = document.createElement('div');
    priceBreakdown.id = 'priceBreakdown';
    priceBreakdown.className = 'mt-3 mb-2';
    document.getElementById('paymentAmount').parentNode.after(priceBreakdown);
    
    // Agregar campo oculto para los datos de la reserva
    const reservationDataInput = document.createElement('input');
    reservationDataInput.type = 'hidden';
    reservationDataInput.id = 'reservationData';
    reservationDataInput.name = 'reservationData';
    document.getElementById('reservationForm').appendChild(reservationDataInput);
    
    // Agregar campo oculto para el ID de la cotización
    const quoteIdInput = document.createElement('input');
    quoteIdInput.type = 'hidden';
    quoteIdInput.id = 'quoteId';
    quoteIdInput.name = 'quoteId';
    document.getElementById('reservationForm').appendChild(quoteIdInput);

    // Modificar el formulario para que redirija al portal de Guesty
    const reservationForm = document.getElementById('reservationForm');
    reservationForm.setAttribute('action', '{{ route("properties.redirect-to-portal") }}');
    
    // Modificar el comportamiento del botón de reserva
    const reserveBtn = document.getElementById('reserveBtn');
    reserveBtn.textContent = 'Continuar al Portal de Reserva';

    flatpickr("#calendar", {
        mode: "range",
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: bookedDates,
        inline: true,
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const checkIn = selectedDates[0].toISOString().split('T')[0];
                const checkOut = selectedDates[1].toISOString().split('T')[0];
                
                document.getElementById("checkIn").value = checkIn;
                document.getElementById("checkOut").value = checkOut;
                
                // Obtener número de huéspedes
                const guestsCount = 2; // Puedes cambiar esto para obtenerlo de un selector si tienes uno
                
                // Usar el token CSRF del formulario en lugar de buscar la meta tag
                const csrfToken = document.querySelector('input[name="_token"]').value;
                
                // Mostrar indicador de carga
                priceBreakdown.innerHTML = `
                    <div class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Calculando...</span>
                        </div>
                        <p class="mt-2">Calculando precio...</p>
                    </div>
                `;
                
                // Deshabilitar botón mientras se calcula
                reserveBtn.disabled = true;
                
                // Realizar petición AJAX para obtener la cotización
                fetch('{{ route('properties.calculatePrice') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        listingId: '{{ $property["_id"] }}',
                        checkIn: checkIn,
                        checkOut: checkOut,
                        guestsCount: guestsCount
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        console.error('Error en la respuesta:', data.error);
                        throw new Error(data.error);
                    }
                    
                    // Guardar datos completos en campo oculto
                    document.getElementById('reservationData').value = JSON.stringify(data);
                    
                    // Guardar ID de cotización si existe
                    if (data.quoteId) {
                        document.getElementById('quoteId').value = data.quoteId;
                        console.log('ID de cotización guardado:', data.quoteId);
                        
                        // Intentar acceder a los datos de dinero desde la estructura anidada
                        if (data.money) {
                            // Asegurarse de que tenemos los datos necesarios
                            const fareAccommodation = data.money.fareAccommodation || 0;
                            const fareCleaning = data.money.fareCleaning || 0;
                            const subTotalPrice = data.money.subTotalPrice || (fareAccommodation + fareCleaning);
                            const currency = data.money.currency || 'USD';
                            
                            // Actualizar el campo de monto total
                            document.getElementById('paymentAmount').value = `$${subTotalPrice} ${currency}`;
                            
                            // Mostrar el desglose de precios
                            priceBreakdown.innerHTML = `
                                <div class="card p-3 mb-3">
                                    <h5 class="mb-3">Detalles de precio</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Alojamiento:</span>
                                        <span>$${fareAccommodation} ${currency}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Limpieza:</span>
                                        <span>$${fareCleaning} ${currency}</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span>$${subTotalPrice} ${currency}</span>
                                    </div>
                                </div>
                            `;
                            
                            // Habilitar el botón de reserva y actualizar texto
                            reserveBtn.disabled = false;
                            reserveBtn.textContent = 'Continuar al Portal de Reserva';
                            
                            // También podemos simplificar el formulario ya que los datos se ingresarán en el portal
                            // Opcionalmente, ocultar los campos de datos del huésped
                            // document.querySelectorAll('.form-floating').forEach(el => el.style.display = 'none');
                            // document.querySelectorAll('h5').forEach(el => el.style.display = 'none');
                            // document.querySelector('#policyId').parentNode.style.display = 'none';
                            // document.querySelector('#paymentMethod').parentNode.style.display = 'none';
                            
                        } else {
                            console.error('No se encontraron datos de precio en la respuesta');
                        }
                    } else {
                        throw new Error('No se recibió un ID de cotización');
                    }
                })
                .catch(error => {
                    console.error('Error al obtener la cotización:', error);
                    priceBreakdown.innerHTML = `
                        <div class="alert alert-danger">
                            No se pudo calcular el precio. Por favor, intente nuevamente.
                            <p class="small mb-0 mt-1">${error.message || 'Error de conexión'}</p>
                        </div>
                    `;
                });
            }
        }
    });
});
</script>
@endsection
