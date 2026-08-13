import {
  validarCampoJson,
} from "./json.js";


/*==================================================*
*=               RESPUESTAS ESPERADAS               =*
*==================================================*/

export function inicializarRespuestas({
  botonId = "nueva-api-btn-agregar-respuesta",
  contenedorId = "nueva-api-respuestas",
  estadoVacioId = "nueva-api-respuestas-vacio",
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
    contenedor.dataset.inicializadoRespuestas === "true"
  ) {
    return;
  }

  contenedor.dataset.inicializadoRespuestas =
    "true";

  botonAgregar.addEventListener(
    "click",
    () => {
      agregarRespuesta(
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
          "[data-eliminar-respuesta]",
        );

      if (!botonEliminar) {
        return;
      }

      const fila =
        botonEliminar.closest(
          "[data-api-respuesta]",
        );

      if (fila) {
        fila.remove();
      }

      actualizarEstadoRespuestas(
        contenedor,
        estadoVacio,
      );
    },
  );

  contenedor.addEventListener(
    "input",
    (event) => {
      const campoBody =
        event.target.closest(
          "[data-respuesta-body]",
        );

      if (!campoBody) {
        return;
      }

      validarCampoJson(
        campoBody,
      );
    },
  );
}


/*==================================================*
*=              AGREGAR RESPUESTA                   =*
*==================================================*/

export function agregarRespuesta(
  contenedor,
  estadoVacio,
  datos = {},
) {
  const fila =
    document.createElement("div");

  fila.className =
    "form-api-documentacion__item";

  fila.dataset.apiRespuesta = "";

  fila.innerHTML = `
    <div class="form-grid">

      <div class="form-grupo">
        <label>
          Código HTTP
        </label>

        <input
          type="number"
          min="100"
          max="599"
          data-respuesta-codigo
          placeholder="Ej. 200"
        >
      </div>

      <div class="form-grupo">
        <label>
          Descripción
        </label>

        <input
          type="text"
          data-respuesta-descripcion
          placeholder="Ej. Operación exitosa"
        >
      </div>

      <div class="form-grupo form-grupo--completo">

        <label>
          Body de respuesta
        </label>

        <textarea
          rows="6"
          data-respuesta-body
          placeholder='{
  "success": true,
  "message": "Operación realizada correctamente"
}'
        ></textarea>

        <small>
          Ingresa un objeto JSON válido.
        </small>

      </div>

    </div>

    <div class="form-api-documentacion__acciones">

      <button
        type="button"
        class="boton boton--peligro boton--sm"
        data-eliminar-respuesta
      >
        Eliminar
      </button>

    </div>
  `;

  const campoCodigo =
    fila.querySelector(
      "[data-respuesta-codigo]",
    );

  const campoDescripcion =
    fila.querySelector(
      "[data-respuesta-descripcion]",
    );

  const campoBody =
    fila.querySelector(
      "[data-respuesta-body]",
    );

  if (campoCodigo) {
    campoCodigo.value =
      datos.codigo ?? "";
  }

  if (campoDescripcion) {
    campoDescripcion.value =
      datos.descripcion ?? "";
  }

  if (campoBody) {
    campoBody.value =
      datos.body &&
      typeof datos.body === "object"
        ? JSON.stringify(
            datos.body,
            null,
            2,
          )
        : "";
  }

  contenedor.appendChild(
    fila,
  );

  actualizarEstadoRespuestas(
    contenedor,
    estadoVacio,
  );
}


/*==================================================*
*=       ACTUALIZAR ESTADO RESPUESTAS               =*
*==================================================*/

function actualizarEstadoRespuestas(
  contenedor,
  estadoVacio,
) {
  const existenRespuestas =
    contenedor.querySelector(
      "[data-api-respuesta]",
    ) !== null;

  estadoVacio.hidden =
    existenRespuestas;
}


/*==================================================*
*=              CARGAR RESPUESTAS                   =*
*==================================================*/

export function cargarRespuestas({
  contenedorId = "nueva-api-respuestas",
  estadoVacioId = "nueva-api-respuestas-vacio",
  respuestas = [],
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

  const listaRespuestas =
    Array.isArray(respuestas)
      ? respuestas
      : [];

  listaRespuestas.forEach(
    (respuesta) => {
      agregarRespuesta(
        contenedor,
        estadoVacio,
        respuesta,
      );
    },
  );

  actualizarEstadoRespuestas(
    contenedor,
    estadoVacio,
  );
}


/*==================================================*
*=              OBTENER RESPUESTAS                  =*
*==================================================*/

export function obtenerRespuestas({
  contenedorId = "nueva-api-respuestas",
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
      "[data-api-respuesta]",
    ),
  ).map((fila) => {
    const bodyTexto =
      fila.querySelector(
        "[data-respuesta-body]",
      )?.value.trim() ?? "";

    return {
      codigo: Number(
        fila.querySelector(
          "[data-respuesta-codigo]",
        )?.value ?? 0,
      ),

      descripcion:
        fila.querySelector(
          "[data-respuesta-descripcion]",
        )?.value.trim() ?? "",

      body:
        bodyTexto
          ? JSON.parse(bodyTexto)
          : {},
    };
  });
}