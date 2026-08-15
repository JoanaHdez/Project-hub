/*==================================================*
*=          COMPONENTES DE ARQUITECTURA             =*
*==================================================*/


export function inicializarComponentesArquitectura({
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
    *=            AGREGAR COMPONENTE                   =*
    *==================================================*/

    botonAgregar.addEventListener(
        "click",
        () => {
            agregarComponente(
                contenedor,
                estadoVacio,
            );
        },
    );


    /*==================================================*
    *=            ELIMINAR COMPONENTE                  =*
    *==================================================*/

    contenedor.addEventListener(
        "click",
        (event) => {
            const botonEliminar =
                event.target.closest(
                    "[data-eliminar-componente-arquitectura]",
                );

            if (!botonEliminar) {
                return;
            }

            const item =
                botonEliminar.closest(
                    "[data-componente-arquitectura]",
                );

            if (item) {
                item.remove();
            }

            actualizarEstadoVacio(
                contenedor,
                estadoVacio,
            );
        },
    );
}


/*==================================================*
*=            CREAR COMPONENTE                      =*
*==================================================*/

export function agregarComponente(
    contenedor,
    estadoVacio,
) {
    const item =
        document.createElement(
            "div",
        );

    item.className =
        "form-api-documentacion__item";

    item.dataset.componenteArquitectura =
        "";

    item.innerHTML = `
        <div class="form-grid">

            <div class="form-grupo">

                <label>
                    Tipo
                </label>

                <select
                    data-arquitectura-tipo
                >
                    <option value="">
                        Selecciona un tipo
                    </option>

                    <option value="Controllers">
                        Controllers
                    </option>

                    <option value="Services">
                        Services
                    </option>

                    <option value="Models">
                        Models
                    </option>

                    <option value="Views">
                        Views
                    </option>

                    <option value="Libraries">
                        Libraries
                    </option>

                    <option value="Helpers">
                        Helpers
                    </option>

                    <option value="Config">
                        Config
                    </option>

                    <option value="Routes">
                        Routes
                    </option>

                    <option value="Otros">
                        Otros
                    </option>

                </select>

            </div>


            <div class="form-grupo">

                <label>
                    Archivo / componente
                </label>

                <input
                    type="text"
                    data-arquitectura-archivo
                    placeholder="Ej. ConstanciaAPI_Controller.php"
                >

            </div>

        </div>


        <div class="form-api-documentacion__acciones">

            <button
                type="button"
                class="boton boton--peligro boton--sm"
                data-eliminar-componente-arquitectura
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
*=              ESTADO VACÍO                        =*
*==================================================*/

export function actualizarEstadoVacio(
    contenedor,
    estadoVacio,
) {
    const existenComponentes =
        contenedor.querySelector(
            "[data-componente-arquitectura]",
        ) !== null;

    estadoVacio.hidden =
        existenComponentes;
}

/*==================================================*
*=          CARGAR COMPONENTES                      =*
*==================================================*/

export function cargarComponentesArquitectura(
    componentes,
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

    const listaComponentes =
        Array.isArray(componentes)
            ? componentes
            : [];

    listaComponentes.forEach(
        (componente) => {
            agregarComponente(
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
                    "[data-arquitectura-tipo]",
                );

            const campoArchivo =
                item.querySelector(
                    "[data-arquitectura-archivo]",
                );

            if (campoTipo) {
                campoTipo.value =
                    componente.tipo ?? "";
            }

            if (campoArchivo) {
                campoArchivo.value =
                    componente.archivo ?? "";
            }
        },
    );

    actualizarEstadoVacio(
        contenedor,
        estadoVacio,
    );
}