/*==================================================*
*=                    HEADERS                       =*
*==================================================*/

export function inicializarHeaders() {
  const botonAgregar = document.getElementById(
    "btn-agregar-header",
  );

  const contenedor = document.getElementById(
    "nueva-api-headers",
  );

  const estadoVacio = document.getElementById(
    "nueva-api-headers-vacio",
  );

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
      agregarHeader(
        contenedor,
        estadoVacio,
      );
    },
  );

  contenedor.addEventListener(
    "click",
    (event) => {
      const botonEliminar = event.target.closest(
        "[data-eliminar-header]",
      );

      if (!botonEliminar) {
        return;
      }

      const fila = botonEliminar.closest(
        "[data-api-header]",
      );

      if (fila) {
        fila.remove();
      }

      actualizarEstadoHeaders(
        contenedor,
        estadoVacio,
      );
    },
  );
}


/*==================================================*
*=                AGREGAR HEADER                    =*
*==================================================*/

function agregarHeader(
  contenedor,
  estadoVacio,
) {
  const fila = document.createElement("div");

  fila.className =
    "form-api-documentacion__item";

  fila.dataset.apiHeader = "";

  fila.innerHTML = `
    <div class="form-grid">

      <div class="form-grupo">
        <label>
          Header
        </label>

        <input
          type="text"
          data-header-nombre
          placeholder="Ej. Content-Type"
        >
      </div>

      <div class="form-grupo">
        <label>
          Valor
        </label>

        <input
          type="text"
          data-header-valor
          placeholder="Ej. application/json"
        >
      </div>

      <div class="form-grupo">
        <label>
          Obligatorio
        </label>

        <select data-header-obligatorio>
          <option value="1">
            Sí
          </option>

          <option value="0">
            No
          </option>
        </select>
      </div>

      <div class="form-grupo">
        <label>
          Descripción
        </label>

        <input
          type="text"
          data-header-descripcion
          placeholder="Descripción del header"
        >
      </div>

    </div>

    <div class="form-api-documentacion__acciones">

      <button
        type="button"
        class="boton boton--peligro boton--sm"
        data-eliminar-header
      >
        Eliminar
      </button>

    </div>
  `;

  contenedor.appendChild(fila);

  actualizarEstadoHeaders(
    contenedor,
    estadoVacio,
  );
}


/*==================================================*
*=          ACTUALIZAR ESTADO HEADERS               =*
*==================================================*/

function actualizarEstadoHeaders(
  contenedor,
  estadoVacio,
) {
  const existenHeaders =
    contenedor.querySelector(
      "[data-api-header]",
    ) !== null;

  estadoVacio.hidden =
    existenHeaders;
}


/*==================================================*
*=                OBTENER HEADERS                   =*
*==================================================*/

export function obtenerHeaders() {
  const contenedor = document.getElementById(
    "nueva-api-headers",
  );

  if (!contenedor) {
    return [];
  }

  return Array.from(
    contenedor.querySelectorAll(
      "[data-api-header]",
    ),
  ).map((fila) => ({
    nombre:
      fila.querySelector(
        "[data-header-nombre]",
      )?.value.trim() ?? "",

    valor:
      fila.querySelector(
        "[data-header-valor]",
      )?.value.trim() ?? "",

    obligatorio:
      fila.querySelector(
        "[data-header-obligatorio]",
      )?.value === "1",

    descripcion:
      fila.querySelector(
        "[data-header-descripcion]",
      )?.value.trim() ?? "",
  }));
}