import {
  mostrarNotificacion,
} from "../notificaciones.js";


/*==================================================*
*=              NUEVA FICHA TÉCNICA                =*
*==================================================*/

export function inicializarNuevaFichaTecnica() {

  const formulario =
    document.getElementById(
      "form-nueva-ficha-tecnica",
    );

  if (!formulario) {
    return;
  }

  if (
    formulario.dataset.inicializado ===
    "true"
  ) {
    return;
  }

  formulario.dataset.inicializado =
    "true";


  /*==================================================*
  *=                    GUARDAR                      =*
  *==================================================*/

  formulario.addEventListener(
    "submit",
    async (event) => {

      event.preventDefault();


      /*==================================================*
      *=                  VALIDACIÓN                      =*
      *==================================================*/

      if (!formulario.checkValidity()) {
        formulario.reportValidity();
        return;
      }


      /*==================================================*
      *=                OBTENER DATOS                    =*
      *==================================================*/

      const formData =
        new FormData(
          formulario,
        );

      const datos = {

        codigo:
          String(
            formData.get("codigo")
            ?? "",
          ).trim(),

        framework:
          String(
            formData.get("framework")
            ?? "",
          ).trim(),

        version_framework:
          String(
            formData.get(
              "version_framework",
            )
            ?? "",
          ).trim(),

        php:
          String(
            formData.get("php")
            ?? "",
          ).trim(),

        base_datos:
          String(
            formData.get("base_datos")
            ?? "",
          ).trim(),

        repositorio:
          String(
            formData.get("repositorio")
            ?? "",
          ).trim(),

        entorno_local:
          String(
            formData.get("entorno_local")
            ?? "",
          ).trim(),
      };


      /*==================================================*
      *=                    FETCH                        =*
      *==================================================*/

      try {

        const respuesta =
          await fetch(
            "/proyectos/especificaciones",
            {
              method: "POST",

              headers: {
                "Content-Type":
                  "application/json",

                "X-Requested-With":
                  "XMLHttpRequest",
              },

              body:
                JSON.stringify(
                  datos,
                ),
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
            "No fue posible registrar la ficha técnica.",
          );
        }


        /*==================================================*
        *=          ACTUALIZAR SELECTOR                   =*
        *==================================================*/

        const especificacion =
          resultado.especificacion;

        const selector =
          document.getElementById(
            "especificacion-tecnica",
          );

        if (
          selector &&
          especificacion
        ) {

          const opcion =
            document.createElement(
              "option",
            );

          opcion.value =
            String(
              especificacion
                .id_especificacion
                ?? "",
            );

          opcion.textContent =
            especificacion.codigo
            || "Ficha técnica";

          opcion.selected =
            true;

          selector.appendChild(
            opcion,
          );

          selector.dispatchEvent(
            new Event(
              "change",
              {
                bubbles: true,
              },
            ),
          );
        }


        /*==================================================*
        *=              CERRAR MODAL                      =*
        *==================================================*/

        const modal =
          document.getElementById(
            "modal-nueva-ficha-tecnica",
          );

        if (modal) {

          const botonCerrar =
            modal.querySelector(
              "[data-modal-cerrar]",
            );

          if (botonCerrar) {

            botonCerrar.click();

          } else {

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
        }


        /*==================================================*
        *=              LIMPIAR FORMULARIO                =*
        *==================================================*/

        formulario.reset();


        /*==================================================*
        *=               NOTIFICACIÓN                     =*
        *==================================================*/

        mostrarNotificacion({
          tipo:
            "success",

          titulo:
            "Ficha técnica registrada",

          mensaje:
            resultado.mensaje ||
            "La ficha técnica fue registrada correctamente.",
        });


      } catch (error) {

        console.error(
          "Error al registrar la ficha técnica:",
          error,
        );

        mostrarNotificacion({
          tipo:
            "error",

          titulo:
            "No se pudo registrar",

          mensaje:
            error.message ||
            "Ocurrió un error al registrar la ficha técnica.",
        });
      }
    },
  );
}