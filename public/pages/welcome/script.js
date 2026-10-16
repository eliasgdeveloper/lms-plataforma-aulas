// Welcome Page Script

document.addEventListener('DOMContentLoaded', function() {
    console.log('Welcome page loaded');
    
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add hover effect to feature items
    const featureItems = document.querySelectorAll('.features-list li');
    featureItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(37, 99, 235, 0.05)';
        });
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'transparent';
        });
    });
});
