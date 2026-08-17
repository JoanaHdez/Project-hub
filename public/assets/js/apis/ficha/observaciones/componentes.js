/*==================================================*
*=          COMPONENTES OBSERVACIONES               =*
*==================================================*/

export function inicializarItemsObservaciones({
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


    /*==================================================*
    *=              AGREGAR OBSERVACIÓN                 =*
    *==================================================*/

    botonAgregar.addEventListener(
        "click",
        () => {
            agregarObservacion(
                contenedor,
                estadoVacio,
            );
        },
    );
}


/*==================================================*
*=              AGREGAR OBSERVACIÓN                 =*
*==================================================*/

export function agregarObservacion(
    contenedor,
    estadoVacio,
) {
    const item =
        document.createElement(
            "div",
        );

    item.className =
        "form-api-documentacion__item";

    item.dataset.observacion = "";

    item.innerHTML = `
        <div class="form-grupo">

            <label>
                Tipo
            </label>

            <select
                data-observacion-tipo
            >
                <option value="">
                    Selecciona un tipo
                </option>

                <option value="informacion">
                    Información
                </option>

                <option value="recomendacion">
                    Recomendación
                </option>

                <option value="importante">
                    Importante
                </option>

            </select>

        </div>


        <div class="form-grupo">

            <label>
                Mensaje
            </label>

            <textarea
                data-observacion-mensaje
                rows="3"
                placeholder="Escribe la observación..."
            ></textarea>

        </div>


        <div class="form-api-documentacion__acciones">

            <button
                type="button"
                class="boton boton--peligro boton--sm"
                data-eliminar-observacion
            >
                Eliminar
            </button>

        </div>
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
*=              ELIMINAR OBSERVACIÓN                =*
*==================================================*/

export function inicializarEliminacionObservaciones({
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
                    "[data-eliminar-observacion]",
                );

            if (!botonEliminar) {
                return;
            }

            const item =
                botonEliminar.closest(
                    "[data-observacion]",
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
*=              CARGAR OBSERVACIONES                =*
*==================================================*/

export function cargarObservaciones(
    observaciones,
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

    const listaObservaciones =
        Array.isArray(observaciones)
            ? observaciones
            : [];

    listaObservaciones.forEach(
        (observacion) => {
            agregarObservacion(
                contenedor,
                estadoVacio,
            );

            const item =
                contenedor.lastElementChild;

            if (!item) {
                return;
            }

            const campoTipo =
                item.querySelector(
                    "[data-observacion-tipo]",
                );

            const campoMensaje =
                item.querySelector(
                    "[data-observacion-mensaje]",
                );

            if (campoTipo) {
                campoTipo.value =
                    observacion.tipo ?? "";
            }

            if (campoMensaje) {
                campoMensaje.value =
                    observacion.mensaje ?? "";
            }
        },
    );

    actualizarEstadoVacio(
        contenedor,
        estadoVacio,
    );
}


/*==================================================*
*=              ESTADO VACÍO                        =*
*==================================================*/

export function actualizarEstadoVacio(
    contenedor,
    estadoVacio,
) {
    const tieneObservaciones =
        contenedor.querySelector(
            "[data-observacion]",
        ) !== null;

    estadoVacio.hidden =
        tieneObservaciones;
}