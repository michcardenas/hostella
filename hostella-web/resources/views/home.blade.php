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
            <h1 class="display-4 fw-bold text-white">Descubre Propiedades Exclusivas</h1>
            <h2 class="lead text-white">Explora villas y alojamientos de lujo en los mejores destinos</h2>
            <a href="{{ route('properties.index') }}" class="btn  btn-primary mt-3">Ver Propiedades</a>
            </div>
    </div>
@else
    <p class="text-center text-danger">No se encontraron imágenes de propiedades.</p>
@endif


    <!-- Search Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="search-box p-4 bg-white shadow rounded">
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
    </section>

    <!-- Featured Properties -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2 class="fw-bold">Propiedades Destacadas</h2>
                    <p class="text-muted">Descubre nuestras propiedades más exclusivas</p>
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
                <h2 class="fw-bold" style="color: #1c2d41;">Maximiza el potencial de tu propiedad con Hostella</h2>
                <p class="text-muted">¿Por qué elegir Hostella?</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="benefit-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-white rounded-circle mb-4">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h4 class="card-title">Rentabilidad Máxima</h4>
                        <p class="text-muted">Optimizamos cada propiedad con tecnología para maximizar tu rentabilidad y ocupación.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="benefit-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-white rounded-circle mb-4">
                            <i class="fas fa-cogs fa-2x"></i>
                        </div>
                        <h4 class="card-title">Automatización Inteligente</h4>
                        <p class="text-muted">Automatizamos procesos clave para ahorrar tiempo, optimizar operaciones y aumentar la eficiencia.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="benefit-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon text-white rounded-circle mb-4">
                            <i class="fas fa-concierge-bell fa-2x"></i>
                        </div>
                        <h4 class="card-title">Experiencias Memorables</h4>
                        <p class="text-muted">Brindamos servicios exclusivos para ofrecer momentos inolvidables a tus huéspedes.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row text-center mt-5">
            <div class="col-lg-12">
                <button class="animated-button">
                    <span>Explorar más</span>
                    <span></span>
                </button>
            </div>
        </div>
    </div>
</section>


    <!-- Por qué los propietarios confían en Hostella-->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5 text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="fw-bold">¿Por qué los propietarios confían en Hostella?</h2>
                <p class="text-muted">Valores que hacen la diferencia en la gestión de tu propiedad.</p>
            </div>
        </div>

        <div class="row g-4 text-center">
            <div class="col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon rounded-circle text-white mb-4">
                        <i class="fas fa-laptop-house fa-2x"></i>
                    </div>
                    <h5 class="feature-title">Innovación Digital</h5>
                    <p class="feature-text">Tecnología avanzada para optimizar ingresos, tarifas dinámicas y análisis de mercado en tiempo real.</p>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon rounded-circle text-white mb-4">
                        <i class="fas fa-file-invoice-dollar fa-2x"></i>
                    </div>
                    <h5 class="feature-title">Transparencia Total</h5>
                    <p class="feature-text">Reportes financieros claros, detallados y asesoría constante para mantenerte informado.</p>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon rounded-circle text-white mb-4">
                        <i class="fas fa-tasks fa-2x"></i>
                    </div>
                    <h5 class="feature-title">Gestión Integral</h5>
                    <p class="feature-text">Administramos todo, desde optimización inicial hasta operaciones diarias, liberando tu tiempo.</p>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon rounded-circle text-white mb-4">
                        <i class="fas fa-seedling fa-2x"></i>
                    </div>
                    <h5 class="feature-title">Crecimiento Sostenible</h5>
                    <p class="feature-text">Creamos estrategias que aseguran un crecimiento constante y sostenible para tu propiedad.</p>
                </div>
            </div>
        </div>

        <div class="row text-center mt-5">
            <div class="col-lg-12">
                <button class="animated-button">
                    <span>Explorar más</span>
                    <span></span>
                </button>
            </div>
        </div>
    </div>
</section>
    <!-- CTA Section -->
    <section class="py-5 text-white" style="background-color: #2c3e50; background-image: linear-gradient(135deg, #2c3e50 0%, #1a2a43 100%);">
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
</section>

@endsection

