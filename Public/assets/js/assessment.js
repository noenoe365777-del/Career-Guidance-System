document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('[data-assessment-card]');
    cards.forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            card.classList.add('ring-2', 'ring-blue-100');
        });
        card.addEventListener('mouseleave', function () {
            card.classList.remove('ring-2', 'ring-blue-100');
        });
    });
});