// Profile Page Script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Profile page loaded');
    
    // Add form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            console.log('Form submitted');
        });
    });
});
