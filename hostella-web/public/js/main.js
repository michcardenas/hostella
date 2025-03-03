// Función para manejar la navegación con scroll
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }
    
    // Inicializar datepickers si existen
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    
    if (checkinInput && checkoutInput) {
        // Establecer fecha mínima como hoy
        const today = new Date().toISOString().split('T')[0];
        checkinInput.min = today;
        
        // Actualizar fecha mínima de checkout cuando cambia checkin
        checkinInput.addEventListener('change', function() {
            checkoutInput.min = this.value;
            
            // Si checkout es menor que checkin, actualizar checkout
            if (checkoutInput.value && checkoutInput.value < this.value) {
                checkoutInput.value = this.value;
            }
        });
    }
});