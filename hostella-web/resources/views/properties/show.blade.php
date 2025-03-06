@extends('layouts.app')

@section('title', $property['title'] ?? 'Detalle de Propiedad')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Galería de imágenes -->
        <div class="col-md-7">
            <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($property['pictures'] as $index => $picture)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ $picture['original'] ?? asset('images/property-placeholder.jpg') }}" 
                                 class="d-block w-100 rounded carousel-img"
                                 alt="Imagen de la propiedad"
                                 data-bs-toggle="modal" data-bs-target="#imageModal"
                                 data-bs-img="{{ $picture['original'] }}">
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <!-- Información de la propiedad -->
        <div class="col-md-5">
            <h1 class="fw-bold">{{ $property['title'] ?? 'Sin título' }}</h1>
            <p class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $property['address']['full'] ?? 'Ubicación no disponible' }}</p>

            <!-- Precios -->
            <div class="bg-light p-3 rounded">
                <h3 class="fw-bold">${{ $property['prices']['basePrice'] ?? 'N/A' }} <small class="text-muted">/ noche</small></h3>
                <p class="text-muted">Moneda: {{ $property['prices']['currency'] ?? 'USD' }}</p>
            
            </div>

            <a href="{{ route('properties.reservation', $property['_id']) }}" class="btn btn-primary btn-lg w-100">
    Reservar Ahora
</a>
        </div>
    </div>

    <!-- Sección de Información con Ver Más -->
    <div class="row mt-5">
        <div class="col-md-8">
            <h2>Descripción</h2>
            @php
                $summary = $property['publicDescription']['summary'] ?? 'No disponible';
                $summaryShort = Str::limit($summary, 200);
            @endphp
            <p class="text-content" id="summary">
                <span class="short-text">{{ $summaryShort }}</span>
                <span class="d-none full-text">{{ $summary }}</span>
                @if(strlen($summary) > 200)
                    <a href="#" class="text-primary see-more" data-target="summary">Ver más</a>
                @endif
            </p>

            <h4>Espacio</h4>
            @php
                $space = $property['publicDescription']['space'] ?? 'No disponible';
                $spaceShort = Str::limit($space, 200);
            @endphp
            <p class="text-content" id="space">
                <span class="short-text">{{ $spaceShort }}</span>
                <span class="d-none full-text">{{ $space }}</span>
                @if(strlen($space) > 200)
                    <a href="#" class="text-primary see-more" data-target="space">Ver más</a>
                @endif
            </p>

            <h4>Ubicación</h4>
            @php
                $location = $property['publicDescription']['neighborhood'] ?? 'No disponible';
                $locationShort = Str::limit($location, 200);
            @endphp
            <p class="text-content" id="location">
                <span class="short-text">{{ $locationShort }}</span>
                <span class="d-none full-text">{{ $location }}</span>
                @if(strlen($location) > 200)
                    <a href="#" class="text-primary see-more" data-target="location">Ver más</a>
                @endif
            </p>

            <h4>Reglas de la Casa</h4>
            @php
                $houseRules = $property['publicDescription']['houseRules'] ?? 'No disponible';
                $houseRulesShort = Str::limit($houseRules, 200);
            @endphp
            <p class="text-content" id="houseRules">
                <span class="short-text">{{ $houseRulesShort }}</span>
                <span class="d-none full-text">{{ $houseRules }}</span>
                @if(strlen($houseRules) > 200)
                    <a href="#" class="text-primary see-more" data-target="houseRules">Ver más</a>
                @endif
            </p>
        </div>
    </div>
</div>

<!-- Modal para ampliar imagen -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen"> <!-- Esto hace que el modal ocupe toda la pantalla -->
        <div class="modal-content bg-transparent border-0"> <!-- Fondo transparente y sin bordes -->
            <div class="modal-body d-flex justify-content-center align-items-center p-0">
                <img id="modalImage" src="" class="img-fluid rounded zoomable">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Manejar "Ver más" para expandir texto
        document.querySelectorAll(".see-more").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                let target = this.getAttribute("data-target");
                let container = document.getElementById(target);
                
                let shortText = container.querySelector(".short-text");
                let fullText = container.querySelector(".full-text");

                if (fullText.classList.contains("d-none")) {
                    fullText.classList.remove("d-none");
                    shortText.classList.add("d-none");
                    this.textContent = "Ver menos";
                } else {
                    fullText.classList.add("d-none");
                    shortText.classList.remove("d-none");
                    this.textContent = "Ver más";
                }
            });
        });

        // Capturar imágenes del carrusel para abrir en modal
        document.querySelectorAll(".carousel-img").forEach(img => {
            img.addEventListener("click", function () {
                document.getElementById("modalImage").src = this.getAttribute("data-bs-img");
            });
        });
    });
</script>

<style>
    /* Ajuste de imágenes */
    .carousel-img {
        height: 400px;
        object-fit: cover;
        cursor: pointer;
    }

    /* Diseño de Ver Más */
    .see-more {
        cursor: pointer;
        font-weight: bold;
    }
</style>
@endsection
