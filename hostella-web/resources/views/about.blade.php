@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <section class="bg-primary text-white py-5 text-center" style="background-image: url('{{ asset('images/relax.webp') }}'); background-size: cover; background-position: center;">
        <div class="container">
            <h1 class="display-3 fw-bold">Bienvenido a Hostella</h1>
            <p class="lead">El aliado estratégico para propietarios que buscan maximizar la rentabilidad de sus inmuebles.</p>
            <a href="{{ route('contact') }}" class="btn btn-light  mt-3">Contáctanos</a>
        </div>
    </section>

    <div class="container-fluid p-0">
    <section class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-6 order-md-2 text-center">
                <img src="{{ asset('images/Hostella_logo_horizontal.png') }}" alt="Hostella" class="img-fluid rounded shadow-lg">
            </div>
            <div class="col-md-6 order-md-1">
                <h2 class="fw-bold">¿Quiénes Somos?</h2>
                <p>
                    Ofrecemos un enfoque integral con tecnología avanzada para optimizar propiedades, administrar reservas y mejorar continuamente la experiencia del huésped.
                </p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle-fill text-primary"></i> Tecnología avanzada y estrategias efectivas.</li>
                    <li><i class="bi bi-check-circle-fill text-primary"></i> Servicio personalizado enfocado en la excelencia.</li>
                    <li><i class="bi bi-check-circle-fill text-primary"></i> Gestión integral desde onboarding hasta marketing digital.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-4">Nuestros Servicios</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h4 class="fw-bold">Optimización y Onboarding</h4>
                            <p>Evaluación y mejora de cada propiedad para asegurar la máxima rentabilidad y experiencias inolvidables para los huéspedes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h4 class="fw-bold">Automatización y Tecnología</h4>
                            <p>Implementamos mensajería automática, tarifas dinámicas adaptativas y reportes financieros en tiempo real.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h4 class="fw-bold">Marketing y Administración</h4>
                            <p>Gestión profesional de reservas, perfiles optimizados en plataformas de alojamiento y estrategias avanzadas de marketing digital y SEO.</p>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <section class="container py-5">
        <h2 class="text-center fw-bold mb-5">¿Por qué elegir Hostella?</h2>
        <div class="row text-center">
            <div class="col-md-3">
                <h5><i class="bi bi-lightbulb-fill text-primary fs-1"></i><br> Innovación Digital</h5>
                <p>Tecnología avanzada que maximiza ingresos.</p>
            </div>
            <div class="col-md-3">
                <h5>Transparencia</h5>
                <p>Reportes financieros claros y asesoría constante.</p>
            </div>
            <div class="col-md-3">
                <h5>Gestión Integral</h5>
                <p>Desde la optimización hasta administración operativa efectiva.</p>
            </div>
            <div class="col-md-3">
                <h5>Crecimiento Sostenible</h5>
                <p>Estrategias enfocadas en aumentar la ocupación y rentabilidad.</p>
            </div>
        </div>
    </section>

    <section class="text-center my-5">
        <h2 class="fw-bold">¡Maximiza el Potencial de tu Propiedad!</h2>
        <p class="lead">Con Hostella, tu propiedad no solo está gestionada, sino lista para destacar en el mercado.</p>
        <a href="mailto:info@hostella.co" class="btn btn-primary ">Contáctanos Hoy Mismo</a>
    </section>
</div>
@endsection
