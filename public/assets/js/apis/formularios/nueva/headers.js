/*==================================================*
*=                    HEADERS                       =*
*==================================================*/

export function inicializarHeaders({
  botonId = "nueva-api-btn-agregar-header",
  contenedorId = "nueva-api-headers",
  estadoVacioId = "nueva-api-headers-vacio",
} = {}) {
  const botonAgregar =
    document.getElementById(
      botonId,
    );

  const contenedor =
    document.getElementById(
      contenedorId,
    );

  const estadoVacio =
    document.getElementById(
      estadoVacioId,
    );

  if (
    !botonAgregar ||
    !contenedor ||
    !estadoVacio
  ) {
    return;
  }

  if (
    contenedor.dataset.inicializadoHeaders === "true"
  ) {
    return;
  }

  contenedor.dataset.inicializadoHeaders =
    "true";

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
      const botonEliminar =
        event.target.closest(
          "[data-eliminar-header]",
        );

      if (!botonEliminar) {
        return;
      }

      const fila =
        botonEliminar.closest(
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

export function agregarHeader(
  contenedor,
  estadoVacio,
  datos = {},
) {
  const fila =
    document.createElement("div");

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

  const campoNombre =
    fila.querySelector(
      "[data-header-nombre]",
    );

  const campoValor =
    fila.querySelector(
      "[data-header-valor]",
    );

  const campoObligatorio =
    fila.querySelector(
      "[data-header-obligatorio]",
    );

  const campoDescripcion =
    fila.querySelector(
      "[data-header-descripcion]",
    );

  if (campoNombre) {
    campoNombre.value =
      datos.nombre ?? "";
  }

  if (campoValor) {
    campoValor.value =
      datos.valor ?? "";
  }

  if (campoObligatorio) {
    campoObligatorio.value =
      datos.obligatorio
        ? "1"
        : "0";
  }

  if (campoDescripcion) {
    campoDescripcion.value =
      datos.descripcion ?? "";
  }

  contenedor.appendChild(
    fila,
  );

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
*=               CARGAR HEADERS                     =*
*==================================================*/

export function cargarHeaders({
  contenedorId = "nueva-api-headers",
  estadoVacioId = "nueva-api-headers-vacio",
  headers = [],
} = {}) {
  const contenedor =
    document.getElementById(
      contenedorId,
    );

  const estadoVacio =
    document.getElementById(
      estadoVacioId,
    );

  if (
    !contenedor ||
    !estadoVacio
  ) {
    return;
  }

  contenedor.innerHTML = "";

  const listaHeaders =
    Array.isArray(headers)
      ? headers
      : [];

  listaHeaders.forEach(
    (header) => {
      agregarHeader(
        contenedor,
        estadoVacio,
        header,
      );
    },
  );

  actualizarEstadoHeaders(
    contenedor,
    estadoVacio,
  );
}


/*==================================================*
*=                OBTENER HEADERS                   =*
*==================================================*/

export function obtenerHeaders({
  contenedorId = "nueva-api-headers",
} = {}) {
  const contenedor =
    document.getElementById(
      contenedorId,
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