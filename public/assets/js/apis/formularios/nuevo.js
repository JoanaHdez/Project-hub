export function inicializarFormularioNuevaApi() {
  const formulario = document.getElementById(
    "form-nueva-api",
  );

  if (!formulario) {
    return;
  }

  /*==================================================*
  *=                    ELEMENTOS                     =*
  *==================================================*/

  const selectProyecto = document.getElementById(
    "nueva-api-proyecto",
  );

  const selectSistema = document.getElementById(
    "nueva-api-sistema",
  );

  const botonAgregarHeader = document.getElementById(
    "btn-agregar-header",
  );

  const contenedorHeaders = document.getElementById(
    "nueva-api-headers",
  );

  const estadoHeadersVacio = document.getElementById(
    "nueva-api-headers-vacio",
  );

  const botonAgregarParametro = document.getElementById(
    "btn-agregar-parametro",
  );

  const contenedorParametros = document.getElementById(
    "nueva-api-parametros",
  );

  const estadoParametrosVacio = document.getElementById(
    "nueva-api-parametros-vacio",
  );

  const botonAgregarRespuesta = document.getElementById(
    "btn-agregar-respuesta",
  );

  const contenedorRespuestas = document.getElementById(
    "nueva-api-respuestas",
  );

  const estadoRespuestasVacio = document.getElementById(
    "nueva-api-respuestas-vacio",
  );

  const campoEjemploBody = document.getElementById(
    "nueva-api-ejemplo-body",
  );

  if (!selectProyecto || !selectSistema) {
    return;
  }

  /*==================================================*
  *=      SISTEMAS SEGÚN EL PROYECTO SELECCIONADO    =*
  *==================================================*/

  selectProyecto.addEventListener(
    "change",
    async () => {
      const idProyecto = selectProyecto.value;

      selectSistema.innerHTML = `
        <option value="">
          Sin sistema asociado
        </option>
      `;

      if (!idProyecto) {
        return;
      }

      selectSistema.disabled = true;

      try {
        const respuesta = await fetch(
          `/proyectos/${idProyecto}/sistemas`,
          {
            method: "GET",
            headers: {
              "X-Requested-With": "XMLHttpRequest",
            },
          },
        );

        const resultado = await respuesta.json();

        if (!respuesta.ok || !resultado.ok) {
          throw new Error(
            resultado.mensaje ||
            "No fue posible obtener los sistemas.",
          );
        }

        const sistemas = Array.isArray(
          resultado.sistemas,
        )
          ? resultado.sistemas
          : [];

        sistemas.forEach((sistema) => {
          const opcion =
            document.createElement("option");

          opcion.value =
            sistema.id_sistema ?? "";

          opcion.textContent =
            sistema.nombre ??
            "Sistema sin nombre";

          selectSistema.appendChild(opcion);
        });
      } catch (error) {
        console.error(
          "Error al cargar los sistemas del proyecto:",
          error,
        );
      } finally {
        selectSistema.disabled = false;
      }
    },
  );

  /*==================================================*
  *=                    HEADERS                       =*
  *==================================================*/

  if (
    botonAgregarHeader &&
    contenedorHeaders &&
    estadoHeadersVacio
  ) {
    botonAgregarHeader.addEventListener(
      "click",
      () => {
        agregarHeader(
          contenedorHeaders,
          estadoHeadersVacio,
        );
      },
    );

    contenedorHeaders.addEventListener(
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
          contenedorHeaders,
          estadoHeadersVacio,
        );
      },
    );
  }

  /*==================================================*
  *=                  PARÁMETROS                      =*
  *==================================================*/

  if (
    botonAgregarParametro &&
    contenedorParametros &&
    estadoParametrosVacio
  ) {
    botonAgregarParametro.addEventListener(
      "click",
      () => {
        agregarParametro(
          contenedorParametros,
          estadoParametrosVacio,
        );
      },
    );

    contenedorParametros.addEventListener(
      "click",
      (event) => {
        const botonEliminar = event.target.closest(
          "[data-eliminar-parametro]",
        );

        if (!botonEliminar) {
          return;
        }

        const fila = botonEliminar.closest(
          "[data-api-parametro]",
        );

        if (fila) {
          fila.remove();
        }

        actualizarEstadoParametros(
          contenedorParametros,
          estadoParametrosVacio,
        );
      },
    );
  }

  /*==================================================*
  *=               RESPUESTAS ESPERADAS              =*
  *==================================================*/

  if (
    botonAgregarRespuesta &&
    contenedorRespuestas &&
    estadoRespuestasVacio
  ) {
    /*
     * Agregar respuesta.
     */
    botonAgregarRespuesta.addEventListener(
      "click",
      () => {
        agregarRespuesta(
          contenedorRespuestas,
          estadoRespuestasVacio,
        );
      },
    );

    /*
     * Eliminar respuesta.
     */
    contenedorRespuestas.addEventListener(
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
          contenedorRespuestas,
          estadoRespuestasVacio,
        );
      },
    );

    /*
     * Validar el JSON de los bodies
     * automáticamente mientras se escribe.
     */
    contenedorRespuestas.addEventListener(
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
  *=           EJEMPLO DE CONSUMO                    =*
  *==================================================*/

  if (campoEjemploBody) {
    campoEjemploBody.addEventListener(
      "input",
      () => {
        validarCampoJson(
          campoEjemploBody,
        );
      },
    );
  }
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
*=         ACTUALIZAR ESTADO DE HEADERS             =*
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
*=              AGREGAR PARÁMETRO                   =*
*==================================================*/

function agregarParametro(
  contenedor,
  estadoVacio,
) {
  const fila = document.createElement("div");

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

  contenedor.appendChild(fila);

  actualizarEstadoParametros(
    contenedor,
    estadoVacio,
  );
}


/*==================================================*
*=       ACTUALIZAR ESTADO DE PARÁMETROS            =*
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
*=       ACTUALIZAR ESTADO DE RESPUESTAS            =*
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
*=               VALIDAR JSON                       =*
*==================================================*/

function validarCampoJson(campo) {
  const valor =
    campo.value.trim();

  let mensajeError = campo
    .closest(".form-grupo")
    ?.querySelector(
      "[data-error-json]",
    );

  /*
   * Campo vacío:
   * no lo consideramos error.
   */
  if (!valor) {
    campo.dataset.jsonValido =
      "true";

    if (mensajeError) {
      mensajeError.remove();
    }

    return true;
  }

  try {
    JSON.parse(valor);

    campo.dataset.jsonValido =
      "true";

    if (mensajeError) {
      mensajeError.remove();
    }

    return true;
  } catch (error) {
    campo.dataset.jsonValido =
      "false";

    if (!mensajeError) {
      mensajeError =
        document.createElement("small");

      mensajeError.dataset.errorJson =
        "";

      mensajeError.className =
        "campo-error";

      campo.insertAdjacentElement(
        "afterend",
        mensajeError,
      );
    }

    mensajeError.textContent =
      "El contenido debe ser un JSON válido.";

    return false;
  }
}