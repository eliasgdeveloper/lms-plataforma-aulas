// Admin Layout Script

function updateActiveNav() {
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        const href = link.getAttribute('href');
        link.classList.remove('active');
        if (!href) {
            return;
        }
        const isExact = currentPath === href;
        const isChild = currentPath.startsWith(`${href}/`);
        if (isExact || isChild) {
            link.classList.add('active');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Admin layout loaded');
    console.log('✅ HTMX version:', window.htmx ? window.htmx.version : 'NOT LOADED');
    
    // Ativar link nav conforme rota atual
    updateActiveNav();

    // Debug: Detectar clique no link do perfil
    const profileLink = document.querySelector('.user-profile');
    if (profileLink) {
        profileLink.addEventListener('click', function(e) {
            console.log('🔵 Perfil clicado! URL:', this.href);
        });
    }
});

// Debug HTMX events
document.body.addEventListener('htmx:beforeRequest', function(evt) {
    console.log('🚀 HTMX Request:', evt.detail.requestConfig);
});

document.body.addEventListener('htmx:afterRequest', function(evt) {
    console.log('✅ HTMX Response:', evt.detail);
});

document.body.addEventListener('htmx:responseError', function(evt) {
    console.error('❌ HTMX Error:', evt.detail);
});

document.body.addEventListener('htmx:afterSettle', function() {
    updateActiveNav();
});

document.body.addEventListener('htmx:historyRestore', function() {
    updateActiveNav();
});
