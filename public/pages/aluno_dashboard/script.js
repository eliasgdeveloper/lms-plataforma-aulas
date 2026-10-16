// Aluno Dashboard Page

document.addEventListener('DOMContentLoaded', function() {
    console.log('Aluno dashboard loaded');
    
    // Add hover animations to nav cards
    const navCards = document.querySelectorAll('.nav-card');
    navCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.opacity = '0.95';
        });
        card.addEventListener('mouseleave', function() {
            this.style.opacity = '1';
        });
    });
});
