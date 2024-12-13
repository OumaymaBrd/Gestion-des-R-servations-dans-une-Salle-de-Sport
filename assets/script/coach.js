function toggleElement(elementId) {
    var element = document.getElementById(elementId);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var navLinks = document.querySelectorAll('.nav-link');
    var contentSections = document.querySelectorAll('.content-section');

    navLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var targetId = this.getAttribute('href').substring(1);

            navLinks.forEach(function(link) {
                link.classList.remove('active');
            });
            this.classList.add('active');

            contentSections.forEach(function(section) {
                if (section.id === targetId) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            });
        });
    });
});