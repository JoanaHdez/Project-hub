import {
  mostrarNotificacion,
} from "../notificaciones.js";


/*==================================================*
*=           ELIMINAR FICHA TÉCNICA                =*
*==================================================*/

export function inicializarEliminarFichaTecnica() {

  const modal =
    document.getElementById(
      "modal-confirmar-eliminar-especificacion",
    );

  const botonConfirmar =
    document.querySelector(
      "[data-confirmar-eliminar-especificacion]",
    );


  if (
    !modal ||
    !botonConfirmar
  ) {
    return;
  }


  /*==================================================*
  *=              ABRIR CONFIRMACIÓN                =*
  *==================================================*/

  document.addEventListener(
    "click",
    (event) => {

      const boton =
        event.target.closest(
          "[data-eliminar-especificacion]",
        );

      if (!boton) {
        return;
      }


      const idEspecificacion =
        String(
          boton.dataset.especificacionId
          ?? "",
        ).trim();


      const totalProyectos =
        Number(
          boton.dataset.totalProyectos
          ?? 0,
        );


      if (!idEspecificacion) {
        return;
      }


      /*==================================================*
      *=          VALIDAR PROYECTOS ASOCIADOS           =*
      *==================================================*/

      if (totalProyectos > 0) {

        mostrarNotificacion({
          tipo:
            "warning",

          titulo:
            "No se puede eliminar",

          mensaje:
            totalProyectos === 1
              ? "Esta ficha técnica está asociada a un proyecto. Debes desvincularla antes de eliminarla."
              : `Esta ficha técnica está asociada a ${totalProyectos} proyectos. Debes desvincularla antes de eliminarla.`,
        });

        return;
      }


      /*==================================================*
      *=             OBTENER CÓDIGO FICHA               =*
      *==================================================*/

      const fila =
        boton.closest(
          "tr",
        );

      const codigo =
        fila
          ?.querySelector(
            "td strong",
          )
          ?.textContent
          ?.trim()
        || "esta ficha técnica";


      /*==================================================*
      *=            PREPARAR CONFIRMACIÓN               =*
      *==================================================*/

      modal.dataset.especificacionId =
        idEspecificacion;


      const titulo =
        modal.querySelector(
          "[data-confirmacion-especificacion-titulo]",
        );

      const mensaje =
        modal.querySelector(
          "[data-confirmacion-especificacion-mensaje]",
        );


      if (titulo) {

        titulo.textContent =
          "Eliminar ficha técnica";
      }


      if (mensaje) {

        mensaje.textContent =
          `¿Deseas eliminar la ficha técnica "${codigo}"? Esta acción no se puede deshacer.`;
      }


      abrirModal(
        modal,
      );
    },
  );


  /*==================================================*
  *=              CONFIRMAR ELIMINACIÓN             =*
  *==================================================*/

  botonConfirmar.addEventListener(
    "click",
    async () => {

      const idEspecificacion =
        String(
          modal.dataset.especificacionId
          ?? "",
        ).trim();


      if (!idEspecificacion) {
        return;
      }


      try {

        const respuesta =
          await fetch(
            `/proyectos/especificaciones/${idEspecificacion}`,
            {
              method:
                "DELETE",

              headers: {
                "X-Requested-With":
                  "XMLHttpRequest",
              },
            },
          );


        const contenido =
          await respuesta.text();

        let resultado;


        try {

          resultado =
            JSON.parse(
              contenido,
            );

        } catch {

          throw new Error(
            "El servidor no devolvió una respuesta JSON válida.",
          );
        }


        if (
          !respuesta.ok ||
          !resultado.ok
        ) {

          throw new Error(
            resultado.mensaje ||
            "No fue posible eliminar la ficha técnica.",
          );
        }


        /*==================================================*
        *=                ELIMINAR FILA                   =*
        *==================================================*/

        const fila =
          document.querySelector(
            `tr[data-especificacion-id="${idEspecificacion}"]`,
          );


        if (fila) {
          fila.remove();
        }


        /*==================================================*
        *=              ELIMINAR DEL SELECT               =*
        *==================================================*/

        document
          .querySelectorAll(
            `[name="id_especificacion"] option[value="${idEspecificacion}"]`,
          )
          .forEach(
            (opcion) => {

              opcion.remove();
            },
          );


        /*==================================================*
        *=             ACTUALIZAR CONTADOR                =*
        *==================================================*/

        actualizarContador(
          resultado.total_especificaciones,
        );


        /*==================================================*
        *=                CERRAR MODAL                    =*
        *==================================================*/

        cerrarModal(
          modal,
        );


        modal.removeAttribute(
          "data-especificacion-id",
        );


        /*==================================================*
        *=                NOTIFICACIÓN                    =*
        *==================================================*/

        mostrarNotificacion({
          tipo:
            "success",

          titulo:
            "Ficha técnica eliminada",

          mensaje:
            resultado.mensaje ||
            "La ficha técnica fue eliminada correctamente.",
        });


      } catch (error) {

        console.error(
          "Error al eliminar ficha técnica:",
          error,
        );


        mostrarNotificacion({
          tipo:
            "error",

          titulo:
            "No se pudo eliminar",

          mensaje:
            error.message ||
            "Ocurrió un error al eliminar la ficha técnica.",
        });
      }
    },
  );
}


/*==================================================*
*=                    ABRIR MODAL                   =*
*==================================================*/

function abrirModal(
  modal,
) {

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


/*==================================================*
*=                   CERRAR MODAL                   =*
*==================================================*/

function cerrarModal(
  modal,
) {

  const botonCerrar =
    modal.querySelector(
      "[data-modal-cerrar]",
    );


  if (botonCerrar) {

    botonCerrar.click();
    return;
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


/*==================================================*
*=              ACTUALIZAR CONTADOR                =*
*==================================================*/

function actualizarContador(
  total,
) {

  const tabla =
    document.getElementById(
      "tabla-especificaciones",
    );

  if (!tabla) {
    return;
  }


  const componente =
    tabla.closest(
      ".tabla-componente",
    );

  if (!componente) {
    return;
  }


  const contador =
    componente.querySelector(
      ".tabla-pie__contador",
    );

  if (!contador) {
    return;
  }


  const cantidad =
    Number(
      total
      ?? 0,
    );


  if (cantidad <= 0) {

    contador.textContent =
      "Mostrando 0 de 0 registros";

    return;
  }


  contador.innerHTML =
    `Mostrando <strong>1</strong> a <strong>${cantidad}</strong> de <strong>${cantidad}</strong> registros`;
}