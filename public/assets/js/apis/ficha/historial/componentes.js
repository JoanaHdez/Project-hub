/*==================================================*
*=              ITEMS HISTORIAL                     =*
*==================================================*/

export function inicializarItemsHistorial({
    botonAgregar,
    contenedor,
    estadoVacio,
}) {
    if (
        !botonAgregar ||
        !contenedor ||
        !estadoVacio
    ) {
        return;
    }

    botonAgregar.addEventListener(
        "click",
        () => {
            agregarHistorial(
                contenedor,
                estadoVacio,
            );
        },
    );
}


/*==================================================*
*=              AGREGAR CAMBIO                      =*
*==================================================*/

export function agregarHistorial(
    contenedor,
    estadoVacio,
) {
    const item =
        document.createElement(
            "div",
        );

    item.className =
        "form-api-documentacion__item";

    item.dataset.historial = "";

    item.innerHTML = `
        <div class="form-grupo">

            <label>
                Versión
            </label>

            <input
                type="text"
                data-historial-version
                placeholder="Ej. 1.0"
            >

        </div>


        <div class="form-grupo">

            <label>
                Descripción del cambio
            </label>

            <input
                type="text"
                data-historial-descripcion
                placeholder="Ej. Creación inicial de la API"
            >

        </div>


        <div class="form-grupo">

            <label>
                Fecha
            </label>

            <input
                type="text"
                data-historial-fecha
                placeholder="Ej. Julio de 2026"
            >

        </div>


        <button
            type="button"
            class="boton boton--peligro boton--sm"
            data-eliminar-historial
        >
            Eliminar
        </button>
    `;

    contenedor.appendChild(
        item,
    );

    actualizarEstadoVacio(
        contenedor,
        estadoVacio,
    );
}


/*==================================================*
*=              ELIMINAR CAMBIO                     =*
*==================================================*/

export function inicializarEliminacionHistorial({
    contenedor,
    estadoVacio,
}) {
    if (
        !contenedor ||
        !estadoVacio
    ) {
        return;
    }

    contenedor.addEventListener(
        "click",
        (event) => {
            const botonEliminar =
                event.target.closest(
                    "[data-eliminar-historial]",
                );

            if (!botonEliminar) {
                return;
            }

            const item =
                botonEliminar.closest(
                    "[data-historial]",
                );

            item?.remove();

            actualizarEstadoVacio(
                contenedor,
                estadoVacio,
            );
        },
    );
}


/*==================================================*
*=              ESTADO VACÍO                        =*
*==================================================*/

export function actualizarEstadoVacio(
    contenedor,
    estadoVacio,
) {
    const tieneHistorial =
        contenedor.querySelector(
            "[data-historial]",
        ) !== null;

    estadoVacio.hidden =
        tieneHistorial;
}


/*==================================================*
*=              CARGAR HISTORIAL                    =*
*==================================================*/

export function cargarHistorial(
    historial,
    contenedor,
    estadoVacio,
) {
    if (
        !contenedor ||
        !estadoVacio
    ) {
        return;
    }

    contenedor.innerHTML = "";

    const lista =
        Array.isArray(historial)
            ? historial
            : [];

    lista.forEach(
        (registro) => {
            agregarHistorial(
                contenedor,
                estadoVacio,
            );

            const item =
                contenedor.lastElementChild;

            if (!item) {
                return;
            }

            const campoVersion =
                item.querySelector(
                    "[data-historial-version]",
                );

            const campoDescripcion =
                item.querySelector(
                    "[data-historial-descripcion]",
                );

            const campoFecha =
                item.querySelector(
                    "[data-historial-fecha]",
                );

            if (campoVersion) {
                campoVersion.value =
                    registro.version ?? "";
            }

            if (campoDescripcion) {
                campoDescripcion.value =
                    registro.descripcion ?? "";
            }

            if (campoFecha) {
                campoFecha.value =
                    registro.fecha ?? "";
            }
        },
    );

    actualizarEstadoVacio(
        contenedor,
        estadoVacio,
    );
}