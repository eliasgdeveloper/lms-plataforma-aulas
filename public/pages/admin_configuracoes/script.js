// Admin Configuracoes Script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Configuracoes page loaded');
    
    const form = document.querySelector('.config-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Configuration form submitted');
        });
    }
});
