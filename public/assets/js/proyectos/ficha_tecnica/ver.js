import {
  mostrarNotificacion,
} from "../notificaciones.js";


/*==================================================*
*=               VER FICHA TÉCNICA                 =*
*==================================================*/

export function inicializarVerFichaTecnica() {

  document.addEventListener(
    "click",
    async (event) => {

      const boton =
        event.target.closest(
          '[data-modal-abrir="modal-ficha-tecnica"]',
        );

      if (!boton) {
        return;
      }


      const formulario =
        boton.closest("form");

      const selector =
        formulario
          ? formulario.querySelector(
              '[name="id_especificacion"]',
            )
          : document.getElementById(
              "especificacion-tecnica",
            );


      if (!selector) {
        return;
      }


      const idEspecificacion =
        String(
          selector.value
          ?? "",
        ).trim();


      if (!idEspecificacion) {

        event.preventDefault();

        mostrarNotificacion({
          tipo:
            "warning",

          titulo:
            "Selecciona una ficha",

          mensaje:
            "Selecciona una especificación técnica antes de consultar la ficha.",
        });

        return;
      }


      try {

        const respuesta =
          await fetch(
            `/proyectos/especificaciones/${idEspecificacion}`,
            {
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
            "No fue posible obtener la ficha técnica.",
          );
        }


        actualizarFicha(
          resultado.especificacion,
        );


      } catch (error) {

        event.preventDefault();

        console.error(
          "Error al obtener la ficha técnica:",
          error,
        );

        mostrarNotificacion({
          tipo:
            "error",

          titulo:
            "No se pudo consultar",

          mensaje:
            error.message ||
            "Ocurrió un error al consultar la ficha técnica.",
        });
      }
    },
  );
}


/*==================================================*
*=              ACTUALIZAR FICHA                    =*
*==================================================*/

function actualizarFicha(
  especificacion = {},
) {

  actualizarTexto(
    "[data-ficha-codigo]",
    especificacion.codigo ||
    "Sin código",
  );

  actualizarTexto(
    "[data-ficha-framework]",
    especificacion.framework ||
    "No disponible",
  );

  actualizarTexto(
    "[data-ficha-version-framework]",
    especificacion.version_framework ||
    "No disponible",
  );

  actualizarTexto(
    "[data-ficha-php]",
    especificacion.php ||
    "No disponible",
  );

  actualizarTexto(
    "[data-ficha-base-datos]",
    especificacion.base_datos ||
    "No disponible",
  );

  actualizarTexto(
    "[data-ficha-repositorio]",
    especificacion.repositorio ||
    "No disponible",
  );

  actualizarTexto(
    "[data-ficha-entorno-local]",
    especificacion.entorno_local ||
    "No disponible",
  );
}


/*==================================================*
*=                ACTUALIZAR TEXTO                  =*
*==================================================*/

function actualizarTexto(
  selector,
  valor,
) {

  const elemento =
    document.querySelector(
      selector,
    );

  if (!elemento) {
    return;
  }

  elemento.textContent =
    valor;
}