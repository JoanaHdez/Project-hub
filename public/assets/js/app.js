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

/*==================================================
=                     SISTEMAS                      =
==================================================*/

const selectoresSistema = document.querySelectorAll('.sistema-selector');
const visorFrame = document.getElementById('visor-sistema-frame');
const visorVacio = document.getElementById('visor-sistema-vacio');
const visorNombre = document.getElementById('visor-sistema-nombre');
const botonRecargar = document.getElementById('recargar-sistema');
const enlaceExterno = document.getElementById('abrir-sistema-externo');
const buscadorSistema = document.getElementById('buscar-sistema');

let sistemaUrlActual = '';

selectoresSistema.forEach((selector) => {
    selector.addEventListener('click', () => {
        const nombre = selector.dataset.sistemaNombre ?? '';
        const url = selector.dataset.sistemaUrl ?? '';

        if (!url) {
            return;
        }

        sistemaUrlActual = url;

        selectoresSistema.forEach((elemento) => {
            elemento.classList.remove('sistema-selector--activo');
        });

        selector.classList.add('sistema-selector--activo');

        if (visorNombre) {
            visorNombre.textContent = nombre;
        }

        if (visorVacio) {
            visorVacio.hidden = true;
        }

        if (visorFrame) {
            visorFrame.hidden = false;
            visorFrame.src = url;
        }

        if (botonRecargar) {
            botonRecargar.disabled = false;
        }

        if (enlaceExterno) {
            enlaceExterno.href = url;
            enlaceExterno.setAttribute('aria-disabled', 'false');
        }
    });
});

botonRecargar?.addEventListener('click', () => {
    if (!sistemaUrlActual || !visorFrame) {
        return;
    }

    visorFrame.src = 'about:blank';

    window.setTimeout(() => {
        visorFrame.src = sistemaUrlActual;
    }, 100);
});

buscadorSistema?.addEventListener('input', () => {
    const termino = buscadorSistema.value
        .trim()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');

    selectoresSistema.forEach((selector) => {
        const contenido = selector.textContent
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');

        selector.hidden = !contenido.includes(termino);
    });
});

const visorCargando = document.getElementById('visor-sistema-cargando');

selectoresSistema.forEach((selector) => {
    selector.addEventListener('click', () => {
        const nombre = selector.dataset.sistemaNombre ?? '';
        const url = selector.dataset.sistemaUrl ?? '';

        if (!url) {
            return;
        }

        sistemaUrlActual = url;

        selectoresSistema.forEach((elemento) => {
            elemento.classList.remove('sistema-selector--activo');
        });

        selector.classList.add('sistema-selector--activo');

        visorNombre.textContent = nombre;
        visorVacio.hidden = true;
        visorCargando.hidden = false;
        visorFrame.hidden = true;
        visorFrame.src = url;

        botonRecargar.disabled = false;
        enlaceExterno.href = url;
        enlaceExterno.setAttribute('aria-disabled', 'false');
    });
});

visorFrame?.addEventListener('load', () => {
    if (visorCargando) {
        visorCargando.hidden = true;
    }

    visorFrame.hidden = false;
});