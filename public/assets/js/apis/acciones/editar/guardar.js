import {
  validarCampoJson,
} from "../../formularios/nueva/json.js";

import {
  obtenerDatosEditarApi,
} from "../../formularios/nueva/datos.js";

import {
  actualizarApi,
} from "../actualizar.js";

import {
  mostrarNotificacion,
} from "../../../proyectos/notificaciones.js";

import {
  cerrarModalEdicion,
} from "./modal.js";


/*==================================================
=             GUARDAR EDICIÓN DE API                =
==================================================*/

export function inicializarGuardadoEdicion({
  formulario,
  modal,
}) {
  if (!formulario || !modal) {
    return;
  }

  const campoEjemploBody =
    document.getElementById(
      "editar-api-ejemplo-body",
    );

  const contenedorRespuestas =
    document.getElementById(
      "editar-api-respuestas",
    );

  formulario.addEventListener(
    "submit",
    async (event) => {
      event.preventDefault();

      /*
       * Validaciones HTML.
       */
      if (
        !formulario.checkValidity()
      ) {
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
       * Validar JSON de respuestas.
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
        if (
          !validarCampoJson(campo)
        ) {
          return;
        }
      }

      const idApi =
        formulario.dataset.apiId ?? "";

      if (!idApi) {
        mostrarNotificacion({
          tipo: "error",

          titulo:
            "No se pudo actualizar la API",

          mensaje:
            "No se encontró el identificador de la API.",
        });

        return;
      }

      const datosApi =
        obtenerDatosEditarApi(
          formulario,
        );

      try {
        const resultado =
          await actualizarApi(
            idApi,
            datosApi,
          );

        actualizarSelectorCatalogo(
          idApi,
          resultado.selector_html,
        );

        mostrarNotificacion({
          tipo: "success",

          titulo:
            "API actualizada",

          mensaje:
            resultado.mensaje ||
            "API actualizada correctamente.",
        });

        cerrarModalEdicion(
          modal,
        );
      } catch (error) {
        console.error(
          "Error al actualizar la API:",
          error,
        );

        mostrarNotificacion({
          tipo: "error",

          titulo:
            "No se pudo actualizar la API",

          mensaje:
            error.message ||
            "Ocurrió un error al actualizar la API.",
        });
      }
    },
  );
}


/*==================================================
=              ACTUALIZAR CATÁLOGO                  =
==================================================*/

function actualizarSelectorCatalogo(
  idApi,
  selectorHtml,
) {
  if (!selectorHtml) {
    return;
  }

  const selectorActual =
    document.querySelector(
      `.api-selector[data-api-id="${idApi}"]`,
    );

  if (!selectorActual) {
    return;
  }

  const plantilla =
    document.createElement(
      "template",
    );

  plantilla.innerHTML =
    selectorHtml.trim();

  const nuevoSelector =
    plantilla.content.querySelector(
      ".api-selector",
    );

  if (!nuevoSelector) {
    return;
  }

  selectorActual.replaceWith(
    nuevoSelector,
  );

  /*
   * La selección usa delegación de eventos,
   * así que el selector nuevo funciona
   * inmediatamente.
   *
   * Simulamos clic para refrescar
   * la documentación visible.
   */
  nuevoSelector.click();
}