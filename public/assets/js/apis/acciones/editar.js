import {
  inicializarComponentesEdicion,
  cargarApiEnFormulario,
} from "./editar/cargar.js";

import {
  inicializarGuardadoEdicion,
} from "./editar/guardar.js";

import {
  abrirModalEdicion,
} from "./editar/modal.js";


/*==================================================
=                    EDITAR API                     =
==================================================*/

export function inicializarEditarApi() {
  const botonEditar =
    document.getElementById(
      "btn-editar-api",
    );

  const modal =
    document.getElementById(
      "modal-editar-api",
    );

  const formulario =
    document.getElementById(
      "form-editar-api",
    );

  if (
    !botonEditar ||
    !modal ||
    !formulario
  ) {
    return;
  }

  /*
   * Inicializar componentes dinámicos
   * una sola vez.
   */
  inicializarComponentesEdicion();

  /*
   * Abrir y cargar la API seleccionada.
   */
  botonEditar.addEventListener(
    "click",
    async () => {
      const cargada =
        await cargarApiEnFormulario(
          formulario,
        );

      if (!cargada) {
        return;
      }

      abrirModalEdicion(
        modal,
      );
    },
  );

  /*
   * Conectar Guardar cambios.
   */
  inicializarGuardadoEdicion({
    formulario,
    modal,
  });
}