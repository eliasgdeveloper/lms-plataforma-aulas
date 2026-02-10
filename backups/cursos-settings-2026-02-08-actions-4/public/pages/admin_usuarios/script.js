// Admin Usuarios Script
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Usuarios page loaded');
    
    // Add sorting functionality to table
    const table = document.querySelector('.usuarios-table');
    if (table) {
        const headers = table.querySelectorAll('th');
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                console.log('Sorting by column:', index);
            });
        });
    }
    
    // Add/Edit user buttons
    const addBtn = document.querySelector('.btn-add');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            console.log('Add user clicked');
        });
    }
});
