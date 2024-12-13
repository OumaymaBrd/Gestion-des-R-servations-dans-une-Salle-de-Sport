document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.reservation-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('.container');
                if (newContent) {
                    document.querySelector('.container').outerHTML = newContent.outerHTML;
                }
                const message = doc.querySelector('.success, .error');
                if (message) {
                    alert(message.textContent);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});