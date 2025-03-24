@extends('layouts.app')

@section('title', 'Hostella - Villas exclusivas en República Dominicana')

@section('meta_description', 'Descubre las villas y propiedades más exclusivas en República Dominicana con Hostella, tu socio confiable para experiencias de lujo inolvidables.')

@section('content')
    <!-- Hero Section -->
    @if(count($featuredImages) > 0)
    <div class="carousel-container position-relative">
    <!-- Carrusel -->
    <div id="carouselProperties" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($featuredImages as $index => $image)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <img src="{{ $image }}" class="d-block w-100 carousel-image" alt="Propiedad">
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProperties" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselProperties" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Contenido superpuesto -->
    <div class="carousel-overlay text-center">
    <h1 class="display-4 fw-bold text-white">
        {{ $pagina->h1 ?? 'Descubre Propiedades Exclusivas' }}
    </h1>

    <h2 class="lead text-white">
        {{ $pagina->h2_1 ?? 'Explora villas y alojamientos de lujo en los mejores destinos' }}
    </h2>

        <!-- <a href="{{ route('properties.index') }}" class="btn btn-primary mt-3">Ver Propiedades</a> -->

        <div class="search-box-overlay">
        <div class="container">
            <div class="search-box p-4  shadow rounded">
                <form action="{{ route('properties.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="location" class="form-label">Ubicación</label>
                            <select class="form-select" id="location" name="location">
                                <option value="">Todas las ubicaciones</option>
                                <option value="hatillo">Hatillo</option>
                                <option value="santodomingo">Santo Domingo</option>
                                <option value="bavaro">Bávaro</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="checkin" class="form-label">Llegada</label>
                            <input type="date" class="form-control" id="checkin" name="checkin">
                        </div>
                        <div class="col-md-3">
                            <label for="checkout" class="form-label">Salida</label>
                            <input type="date" class="form-control" id="checkout" name="checkout">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Caja de búsqueda superpuesta -->

</div>

@else
    <p class="text-center text-danger">No se encontraron imágenes de propiedades.</p>
@endif


    

    <!-- Featured Properties -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-8">
                <h2 class="fw-bold">
                    {{ $pagina->h2_propiedades ?? 'Propiedades Destacadas' }}
                </h2>

                <p class="text-muted">
                    {{ $pagina->p_propiedades ?? 'Descubre nuestras propiedades más exclusivas' }}
                </p>

                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('properties.index') }}" class="btn btn-outline-primary">Ver todas</a>
                </div>
            </div>
            
            <div class="row">
                @forelse($featuredProperties as $property)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card h-100 property-card">
                            <img src="{{ $property['picture']['thumbnail'] ?? asset('images/property-placeholder.jpg') }}" 
                                 class="card-img-top" alt="{{ $property['title'] }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $property['title'] }}</h5>
                                <p class="card-text text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i> 
                                    {{ $property['address']['city'] ?? 'N/A' }}, {{ $property['address']['country'] ?? 'N/A' }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-bed me-1"></i> {{ $property['bedrooms'] ?? 0 }}
                                        <i class="fas fa-bath ms-2 me-1"></i> {{ $property['bathrooms'] ?? 0 }}
                                    </div>
                                    <strong>${{ $property['prices']['basePrice'] ?? 0 }}/noche</strong>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('properties.show', $property['_id']) }}" class="btn btn-outline-primary w-100">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p>No hay propiedades destacadas disponibles en este momento.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-5" style="background-color: #f8f9fa; background-image: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="fw-bold" style="color: #1c2d41;">
                    {{ $pagina->h2_hostella ?? 'Maximiza el potencial de tu propiedad con Hostella' }}
                </h2>
                <p class="text-muted">
                    {{ $pagina->p_hostella ?? '¿Por qué elegir Hostella?' }}
                </p>
            </div>
        </div>

        <div class="row g-4">
            @for ($i = 1; $i <= 3; $i++)
                <div class="col-lg-4">
                    <div class="benefit-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            {{-- Imagen personalizada si existe --}}
                            @php
                                $imageField = 'card1_image_' . $i;
                                $titleField = 'card1_title_' . $i;
                                $contentField = 'card1_content_' . $i;
                            @endphp

                            @if (!empty($pagina->$imageField))
                            <img 
                            src="{{ asset(images/$pagina->$imageField) }}"

                                alt="Imagen tarjeta {{ $i }}" 
                                class="mb-4 img-fluid" 
                                style="max-height: 120px; object-fit: contain; width: 100%; max-width: 100%;">
                            @else
                                {{-- Ícono por defecto si no hay imagen --}}
                                <div class="feature-icon text-white rounded-circle mb-4">
                                    @if ($i === 1)
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    @elseif ($i === 2)
                                        <i class="fas fa-cogs fa-2x"></i>
                                    @else
                                        <i class="fas fa-concierge-bell fa-2x"></i>
                                    @endif
                                </div>
                            @endif

                            <h4 class="card-title">
                                {{ $pagina->$titleField ?? "Título tarjeta $i" }}
                            </h4>
                            <p class="text-muted">
                                {{ $pagina->$contentField ?? "Contenido tarjeta $i" }}
                            </p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>





<!-- Sección de Propiedad Destacada -->
@if(count($featuredProperties) > 0)
    @php
        $property = $featuredProperties[0]; // Primera propiedad destacada
    @endphp

    <section class="featured-property py-5">
        <div class="container">
            <div class="row align-items-center">
                <!-- Columna de texto -->
                <div class="col-md-6 text-section">
                    <p class="text-muted">{{$pagina->p_lugar_favorito ?? 'El lugar favorito, según nuestros huéspedes.' }}</p>
                    <div class="stars">
                        @php
                            $rating = $property['rating'] ?? 5; // Usa la calificación si está disponible
                        @endphp
                        @for ($i = 0; $i < $rating; $i++)
                            ★
                        @endfor
                    </div>
                    <h2 class="property-title">{{ $property['title'] ?? 'Propiedad Destacada' }}</h2>
                    <a href="{{ route('properties.show', $property['_id']) }}" class="btn btn-primary">Ver disponibilidad</a>
                </div>

                <!-- Columna de imágenes -->
                <div class="col-md-6 images-section">
                    <div class="image-wrapper">
                        <img src="{{ $featuredImages[0] ?? asset('images/property-placeholder.jpg') }}" 
                             class="small-image" alt="Interior">
                        <img src="{{ $featuredImages[1] ?? asset('images/property-placeholder.jpg') }}" 
                             class="large-image" alt="Piscina">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif




    <!-- Por qué los propietarios confían en Hostella-->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
            <h2 class="fw-bold">
                {{ $pagina->h2_confiar ?? '¿Por qué los propietarios confían en Hostella?' }}
            </h2>

            <p class="text-muted">
                {{ $pagina->p_confiar ?? 'Valores que hacen la diferencia en la gestión de tu propiedad.' }}
            </p>

            </div>
        </div>

        <div class="row g-4 text-center">
        @for ($i = 4; $i <= 7; $i++)
        <div class="col-lg-3 col-md-6">
            <div class="feature-card h-100 p-4 border-0 shadow-sm bg-white rounded">
                @php
                    $title = "card2_title_$i";
                    $content = "card2_content_$i";
                    $image = "card2_image_$i";
                @endphp

                {{-- Imagen si existe --}}
                @if (!empty($pagina->{$image}))
                    <img src="{{ asset('images/' . $pagina->{$image}) }}" alt="Imagen tarjeta {{ $i }}"
                         style="max-height: 100px; object-fit: contain; width: 100%; max-width: 100%;" class="mb-4">
                @else
                    <div class="feature-icon rounded-circle text-white mb-4 bg-primary d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        @switch($i)
                            @case(4) <i class="fas fa-seedling fa-2x"></i> @break
                            @case(5) <i class="fas fa-shield-alt fa-2x"></i> @break
                            @case(6) <i class="fas fa-clock fa-2x"></i> @break
                            @case(7) <i class="fas fa-handshake fa-2x"></i> @break
                        @endswitch
                    </div>
                @endif

                <h5 class="feature-title">{{ $pagina->{$title} ?? 'Título tarjeta ' . $i }}</h5>
                <p class="feature-text text-muted">{{ $pagina->{$content} ?? 'Descripción de tarjeta ' . $i }}</p>
            </div>
        </div>
    @endfor
        </div>

        <div class="row text-center mt-5">
            <div class="col-lg-12">
            <a href="{{ route('about') }}" class="animated-button">
                <span>Explorar más</span>
                <span></span>
            </a>

            </div>
        </div>
    </div>
</section>
    <!-- CTA Section -->
    <!-- <section class="py-5 text-white" style="background-color: #2c3e50; background-image: linear-gradient(135deg, #2c3e50 0%, #1a2a43 100%);">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <h2 class="fw-bold">Tu próxima experiencia exclusiva comienza aquí</h2>
                <p class="lead mb-0">Descubre villas y propiedades únicas seleccionadas especialmente para ti.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button class="button-explore">
                    <span>Explorar Propiedades</span>
                </button>
            </div>
        </div>
    </div>
</section> -->

@endsection

