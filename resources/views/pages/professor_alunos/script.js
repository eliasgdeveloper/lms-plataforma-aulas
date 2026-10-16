// Professor Alunos Script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Professor Alunos page loaded');
    
    // Add sorting functionality to table
    const table = document.querySelector('.alunos-table');
    if (table) {
        const headers = table.querySelectorAll('th');
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                console.log('Sorting by column:', index);
            });
        });
    }
});
