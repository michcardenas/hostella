<footer class="hostella-footer py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Logo y descripción -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="footer-branding">
                    <div class="logo-container">
                        <img src="{{ asset('images/Hostella_Avatar.png') }}" alt="Hostella" class="footer-logo">
                    </div>
                    <p class="footer-description">Descubre las más exclusivas villas y propiedades de lujo con Hostella, tu socio confiable para experiencias inolvidables.</p>
                </div>
            </div>
            
            <!-- Enlaces rápidos -->
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="footer-heading">Enlaces</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Inicio</a></li>
                    <li><a href="{{ route('properties.index') }}">Propiedades</a></li>
                    <li><a href="{{ route('for-owners') }}">Propietarios</a></li>
                    <li><a href="{{ route('about') }}">Nosotros</a></li>
                </ul>
            </div>
            
            <!-- Información legal -->
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="footer-heading">Legal</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="#">Políticas</a></li>
                    <li><a href="#">Términos</a></li>
                    <li><a href="#">Privacidad</a></li>
                </ul>
            </div>
            
            <!-- Contacto -->
            <div class="col-lg-4 col-md-4">
                <h5 class="footer-heading">Contacto</h5>
                <ul class="contact-info">
                    <li>
                        <div class="icon-wrapper">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <span>República Dominicana</span>
                    </li>
                    <li>
                        <div class="icon-wrapper">
                            <i class="fas fa-phone"></i>
                        </div>
                        <span>+1 (123) 456-7890</span>
                    </li>
                    <li>
                        <div class="icon-wrapper">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <span>info@hostella.com</span>
                    </li>
                </ul>
                
                <!-- Redes sociales -->
                <div class="social-links">
                    <a href="https://instagram.com/hostella" target="_blank" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://facebook.com/hostella" target="_blank" aria-label="Facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://wa.me/1234567890" target="_blank" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="footer-divider"></div>
        
        <!-- Copyright -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <p class="copyright">&copy; {{ date('Y') }} Hostella. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                
                </div>
            </div>
        </div>
    </div>
</footer>