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

import {
  guardarNuevaApi,
} from "./nueva/guardar.js";

import {
  mostrarNotificacion,
} from "../../proyectos/notificaciones.js";


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
  *=                  GUARDAR API                   =*
  *================================================*/

  formulario.addEventListener(
    "submit",
    async (event) => {
      event.preventDefault();

      /*
       * Validaciones HTML.
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
       * Validar JSON de todas
       * las respuestas registradas.
       */
      const camposRespuesta =
        contenedorRespuestas
          ? contenedorRespuestas.querySelectorAll(
              "[data-respuesta-body]",
            )
          : [];

      for (const campo of camposRespuesta) {
        if (!validarCampoJson(campo)) {
          return;
        }
      }

      /*
       * Construir todos los datos
       * de la nueva API.
       */
      const datosApi =
        obtenerDatosNuevaApi(
          formulario,
        );

      try {
        /*
         * Guardar en el backend.
         */
        const resultado =
          await guardarNuevaApi(
            datosApi,
          );

        /*
         * Agregar inmediatamente la API
         * al catálogo sin recargar.
         */
        const catalogo =
          document.querySelector(
            ".catalogo__lista",
          );

        if (
          catalogo &&
          resultado.selector_html
        ) {
          catalogo.insertAdjacentHTML(
            "afterbegin",
            resultado.selector_html,
          );
        }

        /*
         * Notificación de éxito.
         */
        mostrarNotificacion({
          tipo: "success",
          titulo: "API registrada",
          mensaje:
            resultado.mensaje ||
            "API registrada correctamente.",
        });

        /*
         * Limpiar campos normales.
         */
        formulario.reset();

        /*
         * Cerrar modal.
         */
        const modal =
          formulario.closest(".modal");

        if (modal) {
          modal.classList.remove(
            "modal--visible",
          );

          modal.setAttribute(
            "aria-hidden",
            "true",
          );

          document.body.style.overflow = "";
        }
      } catch (error) {
        console.error(
          "Error al registrar la API:",
          error,
        );

        mostrarNotificacion({
          tipo: "error",
          titulo:
            "No se pudo registrar la API",
          mensaje:
            error.message ||
            "Ocurrió un error al registrar la API.",
        });
      }
    },
  );
}