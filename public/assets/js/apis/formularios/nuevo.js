import {
  inicializarRelacionProyectoSistema,
} from "./nueva/relaciones.js";

import {
  inicializarHeaders,
} from "./nueva/headers.js";

import {
  inicializarParametros,
} from "./nueva/parametros.js";

import {
  inicializarRespuestas,
} from "./nueva/respuestas.js";

import {
  validarCampoJson,
} from "./nueva/json.js";

import {
  obtenerDatosNuevaApi,
} from "./nueva/datos.js";


/*==================================================*
*=                NUEVA API                         =*
*==================================================*/

export function inicializarFormularioNuevaApi() {
  const formulario = document.getElementById(
    "form-nueva-api",
  );

  if (!formulario) {
    return;
  }

  /*
   * Evita inicializar dos veces el mismo
   * formulario accidentalmente.
   */
  if (
    formulario.dataset.inicializado === "true"
  ) {
    return;
  }

  formulario.dataset.inicializado =
    "true";

  const campoEjemploBody =
    document.getElementById(
      "nueva-api-ejemplo-body",
    );

  const contenedorRespuestas =
    document.getElementById(
      "nueva-api-respuestas",
    );

  /*================================================*
  *=               INICIALIZADORES                 =*
  *================================================*/

  inicializarRelacionProyectoSistema();

  inicializarHeaders();

  inicializarParametros();

  inicializarRespuestas();

  /*================================================*
  *=         VALIDAR EJEMPLO DE CONSUMO            =*
  *================================================*/

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

  /*================================================*
  *=                SUBMIT TEMPORAL                 =*
  *================================================*/

  formulario.addEventListener(
    "submit",
    (event) => {
      event.preventDefault();

      /*
       * Validaciones HTML normales.
       */
      if (!formulario.checkValidity()) {
        formulario.reportValidity();
        return;
      }

      /*
       * Validar JSON del ejemplo.
       */
      if (
        campoEjemploBody &&
        !validarCampoJson(
          campoEjemploBody,
        )
      ) {
        return;
      }

      /*
       * Validar todos los JSON de respuestas.
       */
      const camposRespuesta =
        contenedorRespuestas
          ? contenedorRespuestas.querySelectorAll(
              "[data-respuesta-body]",
            )
          : [];

      for (
        const campo
        of camposRespuesta
      ) {
        if (!validarCampoJson(campo)) {
          return;
        }
      }

      /*
       * Construir objeto completo.
       */
      const datosApi =
        obtenerDatosNuevaApi(
          formulario,
        );

      /*
       * Temporal.
       * En el siguiente paso esto se convertirá
       * en POST /apis.
       */
      console.log(
        "Datos de Nueva API:",
        datosApi,
      );
    },
  );
}