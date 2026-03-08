document.addEventListener('DOMContentLoaded', function () {
    const deleteLinks = document.querySelectorAll('[data-confirm]');

    deleteLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            const message = link.getAttribute('data-confirm') || 'Are you sure?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});
