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
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">¿Por qué elegir Hostella?</h2>
                    <p class="text-muted">Nos especializamos en brindar experiencias únicas y memorables</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-4">
                                <i class="fas fa-gem"></i>
                            </div>
                            <h4>Propiedades de Lujo</h4>
                            <p class="text-muted">Seleccionamos cuidadosamente cada propiedad para garantizar los más altos estándares de calidad y confort.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-4">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <h4>Servicio Premium</h4>
                            <p class="text-muted">Atención personalizada las 24 horas para asegurar que tu estancia sea perfecta en cada detalle.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary bg-gradient text-white rounded-circle mb-4">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h4>Experiencias Únicas</h4>
                            <p class="text-muted">Creamos momentos inolvidables con servicios adicionales y experiencias personalizadas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Lo que dicen nuestros clientes</h2>
                    <p class="text-muted">Opiniones de huéspedes que han disfrutado de nuestras propiedades</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-4">
                                <div class="testimonial-stars text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="testimonial-text">"Una experiencia increíble. La villa era exactamente como en las fotos, incluso mejor. El servicio fue excepcional y la ubicación perfecta."</p>
                            <div class="d-flex align-items-center mt-4">
                                <div class="testimonial-img me-3">
                                    <img src="{{ asset('images/testimonial-1.jpg') }}" alt="Cliente" class="rounded-circle" width="60">
                                </div>
                                <div>
                                    <h5 class="mb-0">María García</h5>
                                    <p class="text-muted mb-0">Madrid, España</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-4">
                                <div class="testimonial-stars text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="testimonial-text">"Hostella superó todas nuestras expectativas. La atención a los detalles fue impresionante y las instalaciones de primera categoría. Volveremos seguro."</p>
                            <div class="d-flex align-items-center mt-4">
                                <div class="testimonial-img me-3">
                                    <img src="{{ asset('images/testimonial-2.jpg') }}" alt="Cliente" class="rounded-circle" width="60">
                                </div>
                                <div>
                                    <h5 class="mb-0">John Smith</h5>
                                    <p class="text-muted mb-0">New York, USA</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex mb-4">
                                <div class="testimonial-stars text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p class="testimonial-text">"Nuestras vacaciones familiares fueron perfectas gracias a Hostella. La villa tenía todo lo que necesitábamos y el servicio al cliente fue excepcional."</p>
                            <div class="d-flex align-items-center mt-4">
                                <div class="testimonial-img me-3">
                                    <img src="{{ asset('images/testimonial-3.jpg') }}" alt="Cliente" class="rounded-circle" width="60">
                                </div>
                                <div>
                                    <h5 class="mb-0">Laura Martínez</h5>
                                    <p class="text-muted mb-0">Buenos Aires, Argentina</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h2 class="fw-bold">¿Listo para vivir una experiencia inolvidable?</h2>
                    <p class="lead mb-0">Explora nuestras propiedades exclusivas y reserva ahora.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('properties.index') }}" class="btn btn-light btn-lg">Encontrar mi villa</a>
                </div>
            </div>
        </div>
    </section>
@endsection

