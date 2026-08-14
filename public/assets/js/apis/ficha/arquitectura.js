/*==================================================*
*=              ARQUITECTURA DE API                 =*
*==================================================*/

export function inicializarArquitecturaFicha() {
    const botonCompletar =
        document.getElementById(
            "btn-completar-arquitectura",
        );

    const modal =
        document.getElementById(
            "modal-arquitectura-api",
        );

    const botonAgregar =
        document.getElementById(
            "btn-agregar-componente-arquitectura",
        );

    const contenedor =
        document.getElementById(
            "arquitectura-componentes",
        );

    const estadoVacio =
        document.getElementById(
            "arquitectura-componentes-vacio",
        );

    if (
        !botonCompletar ||
        !modal ||
        !botonAgregar ||
        !contenedor ||
        !estadoVacio
    ) {
        return;
    }


    /*==================================================*
    *=              ABRIR MODAL                        =*
    *==================================================*/

    botonCompletar.addEventListener(
        "click",
        () => {
            modal.classList.add(
                "modal--visible",
            );

            modal.setAttribute(
                "aria-hidden",
                "false",
            );

            document.body.style.overflow =
                "hidden";
        },
    );


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

function agregarComponente(
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

function actualizarEstadoVacio(
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