document.addEventListener('DOMContentLoaded', function() {
    const toggleTableBtn = document.getElementById('toggleTable');
    const tableContainer = document.getElementById('tableContainer');

    toggleTableBtn.addEventListener('click', function() {
        tableContainer.classList.toggle('hidden');
        toggleTableBtn.textContent = tableContainer.classList.contains('hidden') ? 'Afficher le tableau' : 'Cacher le tableau';
    });

    // Add smooth scrolling to all links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add active class to current nav item
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.sidebar nav ul li a');
    const menuLength = menuItems.length;
    for (let i = 0; i < menuLength; i++) {
        if (menuItems[i].href === currentLocation) {
            menuItems[i].classList.add('active');
        }
    }

    // Responsive menu toggle for mobile
    const menuToggle = document.createElement('button');
    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    menuToggle.classList.add('menu-toggle');
    document.querySelector('.sidebar').prepend(menuToggle);

    menuToggle.addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });

    // Make table responsive
    const table = document.querySelector('table');
    if (table) {
        const tableHeaders = table.querySelectorAll('th');
        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            row.querySelectorAll('td').forEach((cell, index) => {
                cell.setAttribute('data-label', tableHeaders[index].textContent);
            });
        });
    }
});

