/*==================================================
=                 ADMINISTRAR API                   =
==================================================*/

export function inicializarAdministrarApi() {
  const botonAdministrar =
    document.getElementById(
      "btn-administrar-api",
    );

  const modal =
    document.getElementById(
      "modal-administrar-api",
    );

  if (
    !botonAdministrar ||
    !modal
  ) {
    return;
  }

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

      /*
       * Guardar datos temporales
       * en el modal.
       */
      modal.dataset.apiId =
        idApi;

      modal.dataset.apiNombre =
        nombreApi;

      modal.dataset.apiActivo =
        String(estaActiva);

      /*
       * Mostrar nombre.
       */
      const nombreElemento =
        modal.querySelector(
          "[data-administrar-api-nombre]",
        );

      if (nombreElemento) {
        nombreElemento.textContent =
          `"${nombreApi}"`;
      }

      /*
       * Cambiar Activar / Desactivar
       * según estado actual.
       */
      const botonEstado =
        modal.querySelector(
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

      /*
       * Abrir modal.
       */
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
}