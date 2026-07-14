document.addEventListener('click', (event) => {
    const botonAbrir = event.target.closest('[data-modal-abrir]');
    const botonCerrar = event.target.closest('[data-modal-cerrar]');

    if (botonAbrir) {
        event.preventDefault();

        const modalId = botonAbrir.dataset.modalAbrir;
        const modal = document.getElementById(modalId);

        if (modal) {
            modal.classList.add('modal--visible');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }
    }

    if (botonCerrar) {
        const modal = botonCerrar.closest('.modal');

        if (modal) {
            modal.classList.remove('modal--visible');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    const modalVisible = document.querySelector('.modal.modal--visible');

    if (modalVisible) {
        modalVisible.classList.remove('modal--visible');
        modalVisible.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
});