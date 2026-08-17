import {
  mostrarNotificacion,
} from "../../../proyectos/notificaciones.js";


/*==================================================
=            EJECUTAR ACCIÓN DE API                 =
==================================================*/

export function inicializarConfirmacionAccionApi() {
  const modalConfirmacion =
    document.getElementById(
      "modal-confirmar-accion-api",
    );

  if (!modalConfirmacion) {
    return;
  }

  const botonConfirmar =
    modalConfirmacion.querySelector(
      "[data-confirmar-accion-api]",
    );

  if (!botonConfirmar) {
    return;
  }

  botonConfirmar.addEventListener(
    "click",
    async () => {
      const accion =
        modalConfirmacion.dataset.accion ?? "";

      const idApi =
        modalConfirmacion.dataset.apiId ?? "";

      if (
        !idApi ||
        ![
          "activar",
          "desactivar",
          "eliminar",
          "eliminar-arquitectura",
          "eliminar-dependencias",
        ].includes(accion)
      ) {
        return;
      }

      botonConfirmar.disabled =
        true;

      try {

        /*==================================================
        =             ELIMINAR ARQUITECTURA                =
        ==================================================*/

        if (accion === "eliminar-arquitectura") {
          const resultado =
            await eliminarArquitectura(
              idApi,
            );

          const selector =
            document.querySelector(
              `.api-selector[data-api-id="${idApi}"]`,
            );

          const arquitecturaVacia =
            resultado.arquitectura ?? {
              modulo: "",
              componentes: [],
            };

          if (selector) {
            selector.dataset.apiArquitectura =
              JSON.stringify(
                arquitecturaVacia,
              );
          }

          mostrarNotificacion({
            tipo: "success",

            titulo:
              "Arquitectura eliminada",

            mensaje:
              resultado.mensaje ||
              "La información de Arquitectura fue eliminada correctamente.",
          });

          cerrarModalConfirmacion(
            modalConfirmacion,
          );

          limpiarDatosTemporales(
            modalConfirmacion,
          );

          selector?.click();

          return;
        }


        /*==================================================
        =             ELIMINAR DEPENDENCIAS                =
        ==================================================*/

        if (accion === "eliminar-dependencias") {
          const resultado =
            await eliminarDependencias(
              idApi,
            );

          const selector =
            document.querySelector(
              `.api-selector[data-api-id="${idApi}"]`,
            );

          const dependenciasVacias =
            resultado.dependencias ?? [];

          if (selector) {
            selector.dataset.apiDependencias =
              JSON.stringify(
                dependenciasVacias,
              );
          }

          mostrarNotificacion({
            tipo: "success",

            titulo:
              "Dependencias eliminadas",

            mensaje:
              resultado.mensaje ||
              "La información de Dependencias fue eliminada correctamente.",
          });

          cerrarModalConfirmacion(
            modalConfirmacion,
          );

          limpiarDatosTemporales(
            modalConfirmacion,
          );

          selector?.click();

          return;
        }


        /*==================================================
        =                   ELIMINAR                        =
        ==================================================*/

        if (accion === "eliminar") {
          const resultado =
            await eliminarApi(
              idApi,
            );

          eliminarSelectorApi(
            idApi,
          );

          mostrarNotificacion({
            tipo: "success",

            titulo:
              "API eliminada",

            mensaje:
              resultado.mensaje ||
              "API eliminada correctamente.",
          });

          cerrarModalConfirmacion(
            modalConfirmacion,
          );

          limpiarDatosTemporales(
            modalConfirmacion,
          );

          return;
        }


        /*==================================================
        =             ACTIVAR / DESACTIVAR                 =
        ==================================================*/

        const resultado =
          await cambiarEstadoApi(
            idApi,
            accion,
          );

        actualizarSelectorApi(
          idApi,
          resultado.selector_html,
        );

        mostrarNotificacion({
          tipo: "success",

          titulo:
            accion === "desactivar"
              ? "API desactivada"
              : "API activada",

          mensaje:
            resultado.mensaje ||
            (
              accion === "desactivar"
                ? "API desactivada correctamente."
                : "API activada correctamente."
            ),
        });

        cerrarModalConfirmacion(
          modalConfirmacion,
        );

        limpiarDatosTemporales(
          modalConfirmacion,
        );

      } catch (error) {
        console.error(
          "Error al ejecutar la acción de la API:",
          error,
        );

        mostrarNotificacion({
          tipo: "error",

          titulo:
            "No se pudo realizar la acción",

          mensaje:
            error.message ||
            "Ocurrió un error al realizar la acción sobre la API.",
        });

      } finally {
        botonConfirmar.disabled =
          false;
      }
    },
  );
}


/*==================================================
=             PETICIÓN PATCH                        =
==================================================*/

async function cambiarEstadoApi(
  idApi,
  accion,
) {
  const respuesta =
    await fetch(
      `/apis/${idApi}/${accion}`,
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

  return resultado;
}


/*==================================================
=         ELIMINAR ARQUITECTURA                     =
==================================================*/

async function eliminarArquitectura(
  idApi,
) {
  const respuesta =
    await fetch(
      `/apis/${idApi}/arquitectura`,
      {
        method: "DELETE",

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
      "No fue posible eliminar la Arquitectura.",
    );
  }

  return resultado;
}


/*==================================================
=         ELIMINAR DEPENDENCIAS                    =
==================================================*/

async function eliminarDependencias(
  idApi,
) {
  const respuesta =
    await fetch(
      `/apis/${idApi}/dependencias`,
      {
        method: "DELETE",

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
      "No fue posible eliminar las Dependencias.",
    );
  }

  return resultado;
}


/*==================================================
=              PETICIÓN DELETE                      =
==================================================*/

async function eliminarApi(
  idApi,
) {
  const respuesta =
    await fetch(
      `/apis/${idApi}`,
      {
        method: "DELETE",

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
      "No fue posible eliminar la API.",
    );
  }

  return resultado;
}


/*==================================================
=             ACTUALIZAR SELECTOR                   =
==================================================*/

function actualizarSelectorApi(
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
   * Mantener selección y refrescar
   * información/botones.
   */
  nuevoSelector.click();
}


/*==================================================
=              ELIMINAR SELECTOR                    =
==================================================*/

function eliminarSelectorApi(
  idApi,
) {
  const selector =
    document.querySelector(
      `.api-selector[data-api-id="${idApi}"]`,
    );

  if (!selector) {
    return;
  }

  selector.remove();
}


/*==================================================
=              CERRAR MODAL                         =
==================================================*/

function cerrarModalConfirmacion(
  modal,
) {
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
}


/*==================================================
=           LIMPIAR DATOS TEMPORALES                =
==================================================*/

function limpiarDatosTemporales(
  modal,
) {
  modal.removeAttribute(
    "data-accion",
  );

  modal.removeAttribute(
    "data-api-id",
  );

  modal.removeAttribute(
    "data-api-nombre",
  );
}