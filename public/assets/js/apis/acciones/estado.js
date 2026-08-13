import {
  mostrarNotificacion,
} from "../../proyectos/notificaciones.js";


/*==================================================
=              ACTIVAR / DESACTIVAR API             =
==================================================*/

export function inicializarEstadoApi() {
  const botonEstado =
    document.getElementById(
      "btn-estado-api",
    );

  const modal =
    document.getElementById(
      "modal-estado-api",
    );

  const botonConfirmar =
    document.getElementById(
      "btn-confirmar-estado-api",
    );

  const tituloConfirmacion =
    document.querySelector(
      "[data-estado-api-titulo]",
    );

  const mensajeConfirmacion =
    document.querySelector(
      "[data-estado-api-mensaje]",
    );

  if (
    !botonEstado ||
    !modal ||
    !botonConfirmar
  ) {
    return;
  }

  let idApiPendiente = "";
  let accionPendiente = "";


  /*==================================================
  =              ABRIR CONFIRMACIÓN                 =
  ==================================================*/

  botonEstado.addEventListener(
    "click",
    () => {
      const selector =
        document.querySelector(
          ".api-selector.selector--activo",
        );

      if (!selector) {
        return;
      }

      const idApi =
        selector.dataset.apiId ?? "";

      const nombre =
        selector.dataset.apiNombre ??
        "seleccionada";

      const accion =
        botonEstado.dataset.accion ?? "";

      if (
        !idApi ||
        !["activar", "desactivar"].includes(
          accion,
        )
      ) {
        return;
      }

      idApiPendiente =
        idApi;

      accionPendiente =
        accion;

      if (tituloConfirmacion) {
        tituloConfirmacion.textContent =
          accion === "desactivar"
            ? `¿Deseas desactivar la API "${nombre}"?`
            : `¿Deseas activar la API "${nombre}"?`;
      }

      if (mensajeConfirmacion) {
        mensajeConfirmacion.textContent =
          accion === "desactivar"
            ? "La API quedará inactiva, pero conservará toda su información."
            : "La API volverá a estar disponible como activa.";
      }

      botonConfirmar.textContent =
        accion === "desactivar"
          ? "Desactivar"
          : "Activar";

      modal.classList.add(
        "modal--visible",
      );

      modal.setAttribute(
        "aria-hidden",
        "false",
      );

      document.body.style.overflow =
        "hidden";
    },
  );


  /*==================================================
  =                CONFIRMAR ACCIÓN                 =
  ==================================================*/

  botonConfirmar.addEventListener(
    "click",
    async () => {
      if (
        !idApiPendiente ||
        !accionPendiente
      ) {
        return;
      }

      botonConfirmar.disabled =
        true;

      try {
        const respuesta =
          await fetch(
            `/apis/${idApiPendiente}/${accionPendiente}`,
            {
              method: "PATCH",

              headers: {
                "X-Requested-With":
                  "XMLHttpRequest",
              },
            },
          );

        const resultado =
          await respuesta.json();

        if (
          !respuesta.ok ||
          !resultado.ok
        ) {
          throw new Error(
            resultado.mensaje ||
              "No fue posible cambiar el estado de la API.",
          );
        }


        /*==================================================
        =             ACTUALIZAR SELECTOR                   =
        ==================================================*/

        const selectorActual =
          document.querySelector(
            `.api-selector[data-api-id="${idApiPendiente}"]`,
          );

        if (
          selectorActual &&
          resultado.selector_html
        ) {
          const plantilla =
            document.createElement(
              "template",
            );

          plantilla.innerHTML =
            resultado.selector_html.trim();

          const nuevoSelector =
            plantilla.content.querySelector(
              ".api-selector",
            );

          if (nuevoSelector) {
            selectorActual.replaceWith(
              nuevoSelector,
            );

            /*
             * Mantener seleccionada la API
             * y refrescar botones/documentación.
             */
            nuevoSelector.click();
          }
        }


        /*==================================================
        =                 NOTIFICACIÓN                     =
        ==================================================*/

        mostrarNotificacion({
          tipo: "success",

          titulo:
            accionPendiente === "desactivar"
              ? "API desactivada"
              : "API activada",

          mensaje:
            resultado.mensaje ||
            (
              accionPendiente === "desactivar"
                ? "API desactivada correctamente."
                : "API activada correctamente."
            ),
        });


        /*==================================================
        =                 CERRAR MODAL                     =
        ==================================================*/

        if (
          document.activeElement
          instanceof HTMLElement
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

        document.body.style.overflow =
          "";

        idApiPendiente = "";
        accionPendiente = "";
      } catch (error) {
        console.error(
          "Error al cambiar el estado de la API:",
          error,
        );

        mostrarNotificacion({
          tipo: "error",

          titulo:
            "No se pudo cambiar el estado",

          mensaje:
            error.message ||
            "Ocurrió un error al cambiar el estado de la API.",
        });
      } finally {
        botonConfirmar.disabled =
          false;
      }
    },
  );
}