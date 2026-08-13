/*==================================================
=                 MODAL EDITAR API                  =
==================================================*/

export function abrirModalEdicion(
  modal,
) {
  if (!modal) {
    return;
  }

  modal.classList.add(
    "modal--visible",
  );

  modal.setAttribute(
    "aria-hidden",
    "false",
  );

  document.body.style.overflow =
    "hidden";
}


export function cerrarModalEdicion(
  modal,
) {
  if (!modal) {
    return;
  }

  /*
   * Evita el warning de aria-hidden
   * cuando un elemento del modal
   * conserva el foco.
   */
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