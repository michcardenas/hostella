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
                    <form id="reservationForm" action="{{ route('properties.confirm-reservation', $property['_id']) }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" id="checkIn" name="checkIn">
                        <input type="hidden" id="checkOut" name="checkOut">
                        <input type="hidden" id="reservationData" name="reservationData">
                        <input type="hidden" id="quoteId" name="quoteId">
                        
                        <!-- Número de huéspedes -->
                        <div class="mb-3">
                            <label for="guestsCount" class="form-label">Número de huéspedes</label>
                            <select id="guestsCount" name="guestsCount" class="form-select" required>
                                @for ($i = 1; $i <= $property['accommodates']; $i++)
                                    <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'huésped' : 'huéspedes' }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="mb-2 form-floating">
                            <input type="text" id="paymentAmount" name="payment[amount]" class="form-control" readonly placeholder="Monto Total">
                            <label for="paymentAmount">Monto Total</label>
                        </div>
                        
                        <div id="priceBreakdown" class="mt-3 mb-2"></div>
                        
                        <!-- Botón de Continuar -->
                        <button type="submit" id="reserveBtn" class="btn btn-primary w-100 mt-2" disabled>Continuar con la Reserva</button>
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

    // Elemento para mostrar desglose de precios (ya creado en el HTML)
    const priceBreakdown = document.getElementById('priceBreakdown');
    
    // Selector de número de huéspedes
    const guestsCountSelect = document.getElementById('guestsCount');
    
    // Referencia al botón de reserva
    const reserveBtn = document.getElementById('reserveBtn');
    reserveBtn.textContent = 'Continuar con la Reserva';

    // Inicializar Flatpickr para el calendario
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
                
                // Obtener número de huéspedes del selector
                const guestsCount = guestsCountSelect.value;
                
                // Usar el token CSRF del formulario
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
                            
                            // Habilitar el botón de reserva
                            reserveBtn.disabled = false;
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
    
    // Actualizar precio cuando cambia el número de huéspedes
    guestsCountSelect.addEventListener('change', function() {
        const checkInValue = document.getElementById("checkIn").value;
        const checkOutValue = document.getElementById("checkOut").value;
        
        if (checkInValue && checkOutValue) {
            // Simular un cambio en las fechas para recalcular
            const dates = flatpickr.instances[0].selectedDates;
            flatpickr.instances[0].onChange(dates, '', flatpickr.instances[0]);
        }
    });
});
</script>
@endsection
