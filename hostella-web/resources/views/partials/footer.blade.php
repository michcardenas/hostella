<!-- resources/views/partials/footer.blade.php -->
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <!-- Logo y descripción -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="{{ asset('images/logo-white.png') }}" alt="Hostella" height="40" class="mb-4">
                <p>Descubre las más exclusivas villas y propiedades de lujo con Hostella, tu socio confiable para experiencias inolvidables.</p>
            </div>
            
            <!-- Enlaces rápidos -->
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-4">Enlaces</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-white-50">Inicio</a></li>
                    <li class="mb-2"><a href="{{ route('properties.index') }}" class="text-white-50">Propiedades</a></li>
                    <li class="mb-2"><a href="{{ route('services') }}" class="text-white-50">Experiencias</a></li>
                    <li class="mb-2"><a href="{{ route('for-owners') }}" class="text-white-50">Propietarios</a></li>
                </ul>
            </div>
            
            <!-- Información legal -->
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="text-uppercase mb-4">Legal</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('faq') }}" class="text-white-50">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50">Políticas</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50">Términos</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50">Privacidad</a></li>
                </ul>
            </div>
            
            <!-- Contacto -->
            <div class="col-lg-4 col-md-4">
                <h5 class="text-uppercase mb-4">Contacto</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> República Dominicana</li>
                    <li class="mb-2"><i class="fas fa-phone me-2"></i> +1 (123) 456-7890</li>
                    <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@hostella.com</li>
                </ul>
                
                <!-- Redes sociales -->
                <div class="mt-4">
                    <a href="https://instagram.com/hostella" class="text-white me-3" target="_blank"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="https://facebook.com/hostella" class="text-white me-3" target="_blank"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="https://wa.me/1234567890" class="text-white" target="_blank"><i class="fab fa-whatsapp fa-lg"></i></a>
                </div>
            </div>
        </div>
        
        <hr class="my-4">
        
        <!-- Copyright -->
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; {{ date('Y') }} Hostella. Todos los derechos reservados.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0">Desarrollado con <i class="fas fa-heart text-danger"></i> por TuEmpresa</p>
            </div>
        </div>
    </div>
</footer>