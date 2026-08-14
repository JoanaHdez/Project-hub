import {
  abrirConfirmacionApi,
} from "./administrar/confirmacion.js";


/*==================================================
=                 ADMINISTRAR API                   =
==================================================*/

export function inicializarAdministrarApi() {
  const botonAdministrar =
    document.getElementById(
      "btn-administrar-api",
    );

  const modalAdministrar =
    document.getElementById(
      "modal-administrar-api",
    );

  if (
    !botonAdministrar ||
    !modalAdministrar
  ) {
    return;
  }


  /*==================================================
  =          ABRIR MODAL ADMINISTRAR                 =
  ==================================================*/

  botonAdministrar.addEventListener(
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

      const nombreApi =
        selector.dataset.apiNombre ??
        "API";

      const estaActiva =
        selector.dataset.apiActivo !== "0";

      if (!idApi) {
        return;
      }

      modalAdministrar.dataset.apiId =
        idApi;

      modalAdministrar.dataset.apiNombre =
        nombreApi;

      modalAdministrar.dataset.apiActivo =
        String(estaActiva);

      const nombreElemento =
        modalAdministrar.querySelector(
          "[data-administrar-api-nombre]",
        );

      if (nombreElemento) {
        nombreElemento.textContent =
          `"${nombreApi}"`;
      }

      const botonEstado =
        modalAdministrar.querySelector(
          "[data-boton-estado-api]",
        );

      if (botonEstado) {
        if (estaActiva) {
          botonEstado.dataset.apiAccion =
            "desactivar";

          botonEstado.textContent =
            "Desactivar";
        } else {
          botonEstado.dataset.apiAccion =
            "activar";

          botonEstado.textContent =
            "Activar";
        }
      }

      modalAdministrar.classList.add(
        "modal--visible",
      );

      modalAdministrar.setAttribute(
        "aria-hidden",
        "false",
      );

      document.body.style.overflow =
        "hidden";
    },
  );


  /*==================================================
  =             SELECCIONAR ACCIÓN                  =
  ==================================================*/

  modalAdministrar.addEventListener(
    "click",
    async (event) => {
      const botonAccion =
        event.target.closest(
          "[data-api-accion]",
        );

      if (!botonAccion) {
        return;
      }

      const accion =
        botonAccion.dataset.apiAccion ?? "";

      const idApi =
        modalAdministrar.dataset.apiId ?? "";

      const nombreApi =
        modalAdministrar.dataset.apiNombre ??
        "API";

      if (
        !idApi ||
        ![
          "activar",
          "desactivar",
          "eliminar",
        ].includes(accion)
      ) {
        return;
      }

      /*
       * Quitar foco antes de ocultar
       * el primer modal.
       */
      if (
        document.activeElement
        instanceof HTMLElement
      ) {
        document.activeElement.blur();
      }

      /*
       * Cerrar modal Administrar.
       */
      modalAdministrar.classList.remove(
        "modal--visible",
      );

      modalAdministrar.setAttribute(
        "aria-hidden",
        "true",
      );

      document.body.style.overflow =
        "";

      /*
       * Esperar a que el navegador
       * termine el cambio visual.
       */
      await esperarActualizacionInterfaz();

      /*
       * Abrir segundo modal.
       */
      abrirConfirmacionApi({
        accion,
        idApi,
        nombreApi,
      });
    },
  );
}


/*==================================================
=             ESPERAR INTERFAZ                     =
==================================================*/

function esperarActualizacionInterfaz() {
  return new Promise(
    (resolve) => {
      requestAnimationFrame(
        () => {
          requestAnimationFrame(
            resolve,
          );
        },
      );
    },
  );
}