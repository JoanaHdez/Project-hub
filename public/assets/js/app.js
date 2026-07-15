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

document.addEventListener('input', (event) => {
    const buscador = event.target.closest('[data-tabla-busqueda]');

    if (!buscador) {
        return;
    }

    const tablaId = buscador.dataset.tablaBusqueda;
    const tabla = document.getElementById(tablaId);

    if (!tabla) {
        return;
    }

    const termino = buscador.value
        .trim()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');

    const filas = tabla.querySelectorAll('tbody tr');

    filas.forEach((fila) => {
        if (fila.querySelector('.tabla-vacia')) {
            return;
        }

        const contenido = fila.textContent
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');

        fila.hidden = !contenido.includes(termino);
    });
});