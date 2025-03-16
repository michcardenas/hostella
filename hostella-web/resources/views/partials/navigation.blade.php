<!-- resources/views/partials/navigation.blade.php -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/Hostella_logo_horizontal.png') }}" alt="Hostella" height="40">
        </a>
        
        <!-- Botón móvil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menú principal -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('properties.index') ? 'active' : '' }}" href="{{ route('properties.index') }}">Propiedades</a>
                </li>
            
           
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contacto</a>
                </li>
            </ul>
            
            <!-- Redes sociales -->
            <div class="hostella-social-nav ms-lg-4 d-flex align-items-center">
                <a href="https://instagram.com/hostella" target="_blank" class="hostella-social-icon instagram" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://facebook.com/hostella" target="_blank" class="hostella-social-icon facebook" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://wa.me/1234567890" target="_blank" class="hostella-whatsapp-btn" aria-label="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</nav>