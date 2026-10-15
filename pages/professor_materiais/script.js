// Professor Materiais Script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Professor Materiais page loaded');
    
    const form = document.querySelector('.material-form form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Material form submitted');
        });
    }
});
