// Professor Aulas Script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Professor Aulas page loaded');
    
    const form = document.querySelector('.aula-form form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Aula form submitted');
        });
    }
});
