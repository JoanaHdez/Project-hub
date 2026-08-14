import {
  mostrarNotificacion,
} from "../../../proyectos/notificaciones.js";


/*==================================================
=          MODAL DE CONFIRMACIÓN DE API             =
==================================================*/

export function abrirConfirmacionApi({
  accion,
  idApi,
  nombreApi,
}) {
  const modal =
    document.getElementById(
      "modal-confirmar-accion-api",
    );

  if (!modal) {
    mostrarNotificacion({
      tipo: "error",
      titulo: "No se pudo continuar",
      mensaje:
        "No se encontró el modal de confirmación de la API.",
    });

    return;
  }

  const titulo =
    modal.querySelector(
      "[data-confirmacion-api-titulo]",
    );

  const mensaje =
    modal.querySelector(
      "[data-confirmacion-api-mensaje]",
    );

  const botonConfirmar =
    modal.querySelector(
      "[data-confirmar-accion-api]",
    );

  modal.dataset.accion =
    accion;

  modal.dataset.apiId =
    idApi;

  modal.dataset.apiNombre =
    nombreApi;


  /*==================================================
  =           LIMPIAR ESTILO DEL BOTÓN               =
  ==================================================*/

  if (botonConfirmar) {
    botonConfirmar.classList.remove(
      "boton--primario",
      "boton--advertencia",
      "boton--peligro",
    );
  }


  /*==================================================
  =                 DESACTIVAR                       =
  ==================================================*/

  if (accion === "desactivar") {
    if (titulo) {
      titulo.textContent =
        "Confirmar desactivación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas desactivar la API "${nombreApi}"? ` +
        "La API conservará su información y podrá reactivarse posteriormente.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent =
        "Desactivar";

      botonConfirmar.classList.add(
        "boton--advertencia",
      );
    }
  }


  /*==================================================
  =                   ACTIVAR                        =
  ==================================================*/

  if (accion === "activar") {
    if (titulo) {
      titulo.textContent =
        "Confirmar activación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas activar nuevamente la API "${nombreApi}"? ` +
        "La API volverá a estar disponible como activa.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent =
        "Activar";

      botonConfirmar.classList.add(
        "boton--primario",
      );
    }
  }


  /*==================================================
  =                   ELIMINAR                       =
  ==================================================*/

  if (accion === "eliminar") {
    if (titulo) {
      titulo.textContent =
        "Confirmar eliminación";
    }

    if (mensaje) {
      mensaje.textContent =
        `¿Confirmas que deseas eliminar definitivamente la API "${nombreApi}"? ` +
        "Esta acción no se podrá deshacer.";
    }

    if (botonConfirmar) {
      botonConfirmar.textContent =
        "Eliminar";

      botonConfirmar.classList.add(
        "boton--peligro",
      );
    }
  }


  /*==================================================
  =                 ABRIR MODAL                      =
  ==================================================*/

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