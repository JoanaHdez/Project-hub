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

      if (!formulario.checkValidity()) {
        formulario.reportValidity();
        return;
      }

      if (
        campoEjemploBody &&
        !validarCampoJson(
          campoEjemploBody,
        )
      ) {
        return;
      }

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

      const datosApi =
        obtenerDatosNuevaApi(
          formulario,
        );

      try {

        const resultado =
          await guardarNuevaApi(
            datosApi,
          );

        console.log(
          "Resultado completo del guardado:",
          resultado,
        );

        console.log(
          "selector_html:",
          resultado.selector_html,
        );

        const catalogo =
          document.querySelector(
            ".catalogo__lista",
          );

        if (
          catalogo &&
          resultado.selector_html
        ) {

          /*
           * Si el catálogo estaba vacío,
           * eliminar el mensaje antes
           * de insertar la nueva API.
           */
          const estadoVacio =
            catalogo.querySelector(
              ".catalogo-vacio, .catalogo__vacio",
            );

          estadoVacio?.remove();


          /*
           * Insertar la nueva API
           * al inicio del catálogo.
           */
          catalogo.insertAdjacentHTML(
            "afterbegin",
            resultado.selector_html,
          );
        }

        mostrarNotificacion({
          tipo: "success",
          titulo: "API registrada",
          mensaje:
            resultado.mensaje ||
            "API registrada correctamente.",
        });

        formulario.reset();

        const modal =
          formulario.closest(".modal");

        if (modal) {

          if (
            document.activeElement instanceof HTMLElement
          ) {
            document.activeElement.blur();
          }

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