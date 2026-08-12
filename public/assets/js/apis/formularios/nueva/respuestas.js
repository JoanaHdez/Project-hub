import {
  validarCampoJson,
} from "./json.js";


/*==================================================*
*=               RESPUESTAS ESPERADAS               =*
*==================================================*/

export function inicializarRespuestas() {
  const botonAgregar = document.getElementById(
    "btn-agregar-respuesta",
  );

  const contenedor = document.getElementById(
    "nueva-api-respuestas",
  );

  const estadoVacio = document.getElementById(
    "nueva-api-respuestas-vacio",
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
      agregarRespuesta(
        contenedor,
        estadoVacio,
      );
    },
  );

  contenedor.addEventListener(
    "click",
    (event) => {
      const botonEliminar = event.target.closest(
        "[data-eliminar-respuesta]",
      );

      if (!botonEliminar) {
        return;
      }

      const fila = botonEliminar.closest(
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
      const campoBody = event.target.closest(
        "[data-respuesta-body]",
      );

      if (!campoBody) {
        return;
      }

      validarCampoJson(campoBody);
    },
  );
}


/*==================================================*
*=              AGREGAR RESPUESTA                   =*
*==================================================*/

function agregarRespuesta(
  contenedor,
  estadoVacio,
) {
  const fila = document.createElement("div");

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

  contenedor.appendChild(fila);

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
*=              OBTENER RESPUESTAS                  =*
*==================================================*/

export function obtenerRespuestas() {
  const contenedor = document.getElementById(
    "nueva-api-respuestas",
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

      body: bodyTexto
        ? JSON.parse(bodyTexto)
        : {},
    };
  });
}