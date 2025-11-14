// Using plain <link> and <script> tags in layout for local vendor assets.

// visitor counter animation
document.addEventListener('DOMContentLoaded', function () {
    const counters = document.querySelectorAll('.count');

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const current = +counter.innerText.replace(/,/g, '');
            const increment = target > 100000 ? 1000 : target > 10000 ? 100 : 10;

            if (current < target) {
                const next = Math.min(current + increment, target);
                counter.innerText = next.toLocaleString();
                requestAnimationFrame(updateCount);
            } else {
                counter.innerText = target.toLocaleString();
            }
        };

        updateCount();
    });
});
