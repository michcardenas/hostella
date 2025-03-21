@extends('layouts.app')

@section('title', 'Propiedades - Hostella')

@section('content')
<!-- Banner hero moderno para Hostella -->
<div class="hostella-hero-banner">
    <div class="hostella-overlay"></div>
    <div class="hostella-hero-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10">
                    <h1 class="hostella-main-title">Relájate en tu próxima aventura</h1>
                    <h2 class="hostella-subtitle">Propiedades exclusivas para momentos únicos</h2>
                    <h3 class="hostella-description">Descubre alojamientos excepcionales verificados por Hostella</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h1 class="fw-bold text-center mb-4">Lista de Propiedades</h1>

    @if(isset($properties) && count($properties) > 0)
        <div class="row">
            @foreach($properties as $property)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <!-- Imagen de la propiedad -->
                        <img src="{{ $property['picture']['thumbnail'] ?? asset('images/property-placeholder.jpg') }}" 
                             class="card-img-top property-img" alt="{{ $property['title'] }}">

                        <div class="card-body">
                            <h5 class="card-title">{{ $property['title'] ?? 'Sin título' }}</h5>
                            <p class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i> 
                                {{ $property['address']['city'] ?? 'Ubicación no disponible' }}, 
                                {{ $property['address']['country'] ?? '' }}
                            </p>
                            <div class="d-flex justify-content-between">
                                <span><i class="fas fa-bed"></i> {{ $property['bedrooms'] ?? 0 }} Habitaciones</span>
                                <span><i class="fas fa-bath"></i> {{ $property['bathrooms'] ?? 0 }} Baños</span>
                            </div>
                        </div>

                        <div class="card-footer bg-white text-center">
                            <a href="{{ route('properties.show', $property['_id']) }}" 
                               class="btn btn-primary w-100">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-muted">No hay propiedades disponibles en este momento.</p>
    @endif
</div>

<!-- Estilos personalizados -->
<style>
  
    

  .hostella-hero-banner {
    position: relative;
    height: 500px;
    background-image: url('{{ asset("images/relax.webp") }}');
    background-size: cover;
    background-position: center;
    margin-bottom: 50px;
    overflow: hidden;
}

</style>
@endsection