/*==================================================
=                     MODALES                       =
==================================================*/

document.addEventListener('click', (event) => {
    const botonAbrir = event.target.closest('[data-modal-abrir]');
    const botonCerrar = event.target.closest('[data-modal-cerrar]');

    if (botonAbrir) {
        event.preventDefault();

        if (botonAbrir.disabled) {
            return;
        }

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


/*==================================================
=              BÚSQUEDA EN TABLAS                  =
==================================================*/

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

    const termino = normalizarTexto(buscador.value);
    const filas = tabla.querySelectorAll('tbody tr');

    filas.forEach((fila) => {
        if (fila.querySelector('.tabla-vacia')) {
            return;
        }

        const contenido = normalizarTexto(fila.textContent);

        fila.hidden = !contenido.includes(termino);
    });
});


/*==================================================
=                     SISTEMAS                      =
==================================================*/

const selectoresSistema = document.querySelectorAll('.sistema-selector');

const visorFrame = document.getElementById('visor-sistema-frame');
const visorVacio = document.getElementById('visor-sistema-vacio');
const visorCargando = document.getElementById('visor-sistema-cargando');
const visorNombre = document.getElementById('visor-sistema-nombre');

const botonRecargar = document.getElementById('recargar-sistema');
const enlaceExterno = document.getElementById('abrir-sistema-externo');
const botonFichaUbicacion = document.getElementById(
    'btn-ficha-ubicacion'
);

const buscadorSistema = document.getElementById('buscar-sistema');

let sistemaUrlActual = '';

selectoresSistema.forEach((selector) => {
    selector.addEventListener('click', () => {
        const nombre = selector.dataset.sistemaNombre ?? '';
        const url = selector.dataset.sistemaUrl ?? '';

        sistemaUrlActual = url;

        selectoresSistema.forEach((elemento) => {
            elemento.classList.remove('sistema-selector--activo');
        });

        selector.classList.add('sistema-selector--activo');

        if (visorNombre) {
            visorNombre.textContent = nombre || 'Sistema seleccionado';
        }

        /*
         * La ficha de ubicación siempre se habilita
         * cuando existe un sistema seleccionado.
         */
        if (botonFichaUbicacion) {
            botonFichaUbicacion.disabled = false;
        }

        /*
         * Si no existe una URL, no se intenta cargar
         * el sistema dentro del iframe.
         */
        if (!url) {
            mostrarVisorSinUrl();
            return;
        }

        cargarSistemaEnVisor(url);
    });
});


/*==================================================
=              RECARGAR SISTEMA                    =
==================================================*/

botonRecargar?.addEventListener('click', () => {
    if (!sistemaUrlActual || !visorFrame) {
        return;
    }

    if (visorVacio) {
        visorVacio.hidden = true;
    }

    if (visorCargando) {
        visorCargando.hidden = false;
    }

    visorFrame.hidden = true;
    visorFrame.src = 'about:blank';

    window.setTimeout(() => {
        visorFrame.src = sistemaUrlActual;
    }, 100);
});


/*==================================================
=                CARGA DEL IFRAME                  =
==================================================*/

visorFrame?.addEventListener('load', () => {
    /*
     * Evita mostrar el iframe cuando está usando
     * about:blank durante el proceso de recarga.
     */
    if (
        visorFrame.getAttribute('src') === 'about:blank' ||
        visorFrame.src === 'about:blank'
    ) {
        return;
    }

    if (visorCargando) {
        visorCargando.hidden = true;
    }

    visorFrame.hidden = false;
});


/*==================================================
=             BÚSQUEDA DE SISTEMAS                 =
==================================================*/

buscadorSistema?.addEventListener('input', () => {
    const termino = normalizarTexto(buscadorSistema.value);

    selectoresSistema.forEach((selector) => {
        const contenido = normalizarTexto(selector.textContent);

        selector.hidden = !contenido.includes(termino);
    });
});


/*==================================================
=          ABRIR SISTEMA EN OTRA PESTAÑA           =
==================================================*/

enlaceExterno?.addEventListener('click', (event) => {
    const deshabilitado =
        enlaceExterno.getAttribute('aria-disabled') === 'true';

    if (deshabilitado || !sistemaUrlActual) {
        event.preventDefault();
    }
});


/*==================================================
=                 FUNCIONES AUXILIARES              =
==================================================*/

function cargarSistemaEnVisor(url) {
    if (visorVacio) {
        visorVacio.hidden = true;
    }

    if (visorCargando) {
        visorCargando.hidden = false;
    }

    if (visorFrame) {
        visorFrame.hidden = true;
        visorFrame.src = url;
    }

    if (botonRecargar) {
        botonRecargar.disabled = false;
    }

    if (enlaceExterno) {
        enlaceExterno.href = url;
        enlaceExterno.setAttribute('aria-disabled', 'false');
    }
}

function mostrarVisorSinUrl() {
    if (visorCargando) {
        visorCargando.hidden = true;
    }

    if (visorFrame) {
        visorFrame.hidden = true;
        visorFrame.removeAttribute('src');
    }

    if (visorVacio) {
        visorVacio.hidden = false;

        const titulo = visorVacio.querySelector('strong');
        const descripcion = visorVacio.querySelector('p');

        if (titulo) {
            titulo.textContent = 'Sistema no disponible en servidor';
        }

        if (descripcion) {
            descripcion.textContent =
                'Este sistema aún no cuenta con una dirección de servidor registrada. Consulta su ficha de ubicación para conocer dónde está almacenado.';
        }
    }

    if (botonRecargar) {
        botonRecargar.disabled = true;
    }

    if (enlaceExterno) {
        enlaceExterno.href = '#';
        enlaceExterno.setAttribute('aria-disabled', 'true');
    }
}

function normalizarTexto(texto) {
    return String(texto ?? '')
        .trim()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
}