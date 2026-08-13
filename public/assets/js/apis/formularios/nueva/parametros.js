/*==================================================*
*=                  PARÁMETROS                      =*
*==================================================*/

export function inicializarParametros({
  botonId = "nueva-api-btn-agregar-parametro",
  contenedorId = "nueva-api-parametros",
  estadoVacioId = "nueva-api-parametros-vacio",
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
    contenedor.dataset.inicializadoParametros === "true"
  ) {
    return;
  }

  contenedor.dataset.inicializadoParametros =
    "true";

  botonAgregar.addEventListener(
    "click",
    () => {
      agregarParametro(
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
          "[data-eliminar-parametro]",
        );

      if (!botonEliminar) {
        return;
      }

      const fila =
        botonEliminar.closest(
          "[data-api-parametro]",
        );

      if (fila) {
        fila.remove();
      }

      actualizarEstadoParametros(
        contenedor,
        estadoVacio,
      );
    },
  );
}


/*==================================================*
*=              AGREGAR PARÁMETRO                   =*
*==================================================*/

export function agregarParametro(
  contenedor,
  estadoVacio,
  datos = {},
) {
  const fila =
    document.createElement("div");

  fila.className =
    "form-api-documentacion__item";

  fila.dataset.apiParametro = "";

  fila.innerHTML = `
    <div class="form-grid">

      <div class="form-grupo">
        <label>
          Nombre
        </label>

        <input
          type="text"
          data-parametro-nombre
          placeholder="Ej. correo"
        >
      </div>

      <div class="form-grupo">
        <label>
          Tipo
        </label>

        <select data-parametro-tipo>
          <option value="">
            Selecciona un tipo
          </option>

          <option value="string">
            string
          </option>

          <option value="integer">
            integer
          </option>

          <option value="boolean">
            boolean
          </option>

          <option value="array">
            array
          </option>

          <option value="object">
            object
          </option>

          <option value="number">
            number
          </option>
        </select>
      </div>

      <div class="form-grupo">
        <label>
          Obligatorio
        </label>

        <select data-parametro-obligatorio>
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
          data-parametro-descripcion
          placeholder="Descripción del parámetro"
        >
      </div>

    </div>

    <div class="form-api-documentacion__acciones">

      <button
        type="button"
        class="boton boton--peligro boton--sm"
        data-eliminar-parametro
      >
        Eliminar
      </button>

    </div>
  `;

  const campoNombre =
    fila.querySelector(
      "[data-parametro-nombre]",
    );

  const campoTipo =
    fila.querySelector(
      "[data-parametro-tipo]",
    );

  const campoObligatorio =
    fila.querySelector(
      "[data-parametro-obligatorio]",
    );

  const campoDescripcion =
    fila.querySelector(
      "[data-parametro-descripcion]",
    );

  if (campoNombre) {
    campoNombre.value =
      datos.nombre ?? "";
  }

  if (campoTipo) {
    campoTipo.value =
      datos.tipo ?? "";
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

  actualizarEstadoParametros(
    contenedor,
    estadoVacio,
  );
}


/*==================================================*
*=       ACTUALIZAR ESTADO PARÁMETROS               =*
*==================================================*/

function actualizarEstadoParametros(
  contenedor,
  estadoVacio,
) {
  const existenParametros =
    contenedor.querySelector(
      "[data-api-parametro]",
    ) !== null;

  estadoVacio.hidden =
    existenParametros;
}


/*==================================================*
*=              CARGAR PARÁMETROS                   =*
*==================================================*/

export function cargarParametros({
  contenedorId = "nueva-api-parametros",
  estadoVacioId = "nueva-api-parametros-vacio",
  parametros = [],
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

  const listaParametros =
    Array.isArray(parametros)
      ? parametros
      : [];

  listaParametros.forEach(
    (parametro) => {
      agregarParametro(
        contenedor,
        estadoVacio,
        parametro,
      );
    },
  );

  actualizarEstadoParametros(
    contenedor,
    estadoVacio,
  );
}


/*==================================================*
*=              OBTENER PARÁMETROS                  =*
*==================================================*/

export function obtenerParametros({
  contenedorId = "nueva-api-parametros",
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
      "[data-api-parametro]",
    ),
  ).map((fila) => ({
    nombre:
      fila.querySelector(
        "[data-parametro-nombre]",
      )?.value.trim() ?? "",

    tipo:
      fila.querySelector(
        "[data-parametro-tipo]",
      )?.value ?? "",

    obligatorio:
      fila.querySelector(
        "[data-parametro-obligatorio]",
      )?.value === "1",

    descripcion:
      fila.querySelector(
        "[data-parametro-descripcion]",
      )?.value.trim() ?? "",
  }));
}