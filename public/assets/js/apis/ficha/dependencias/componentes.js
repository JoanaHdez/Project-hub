/*==================================================*
*=              ITEMS DEPENDENCIAS                  =*
*==================================================*/

export function inicializarItemsDependencias({
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
            agregarDependencia(
                contenedor,
                estadoVacio,
            );
        },
    );
}


/*==================================================*
*=              AGREGAR DEPENDENCIA                 =*
*==================================================*/

function agregarDependencia(
    contenedor,
    estadoVacio,
) {
    const item =
        document.createElement(
            "div",
        );

    item.className =
        "form-api-documentacion__item";

    item.dataset.dependencia = "";

    item.innerHTML = `
        <div class="form-grupo">

            <label>
                Tipo
            </label>

            <select data-dependencia-tipo>
                <option value="">
                    Selecciona un tipo
                </option>

                <option value="Base de datos">
                    Base de datos
                </option>

                <option value="Servicio de correo">
                    Servicio de correo
                </option>

                <option value="Framework">
                    Framework
                </option>

                <option value="Autenticación">
                    Autenticación
                </option>

                <option value="Configuración">
                    Configuración
                </option>

                <option value="Servicio interno">
                    Servicio interno
                </option>

                <option value="Otro">
                    Otro
                </option>
            </select>

        </div>


        <div class="form-grupo">

            <label>
                Nombre
            </label>

            <input
                type="text"
                data-dependencia-nombre
                placeholder="Ej. MySQL"
            >

        </div>


        <div class="form-grupo">

            <label>
                Descripción
            </label>

            <input
                type="text"
                data-dependencia-descripcion
                placeholder="Ej. Base de datos principal del sistema"
            >

        </div>


        <div class="form-grupo">

            <label>
                Estado
            </label>

            <select data-dependencia-estado>

                <option value="Activa">
                    Activa
                </option>

                <option value="Requerida">
                    Requerida
                </option>

                <option value="Interna">
                    Interna
                </option>

                <option value="Opcional">
                    Opcional
                </option>

                <option value="Inactiva">
                    Inactiva
                </option>

            </select>

        </div>


        <button
            type="button"
            class="boton boton--peligro boton--sm"
            data-eliminar-dependencia
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
*=              ELIMINAR DEPENDENCIA                =*
*==================================================*/

export function inicializarEliminacionDependencias({
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
                    "[data-eliminar-dependencia]",
                );

            if (!botonEliminar) {
                return;
            }

            const item =
                botonEliminar.closest(
                    "[data-dependencia]",
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

function actualizarEstadoVacio(
    contenedor,
    estadoVacio,
) {
    const tieneDependencias =
        contenedor.querySelector(
            "[data-dependencia]",
        ) !== null;

    estadoVacio.hidden =
        tieneDependencias;
}