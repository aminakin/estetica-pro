document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('[data-src]').forEach(el => {
            // el.src = el.dataset.src;
            el.style.backgroundImage = `url(${el.dataset.src})`;
            el.removeAttribute('data-src');
            el.classList.remove('preload');
        });
    }, 10);
});
