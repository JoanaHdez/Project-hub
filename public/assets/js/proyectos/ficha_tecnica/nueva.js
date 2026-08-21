import {
  mostrarNotificacion,
} from "../notificaciones.js";


/*==================================================*
*=         NUEVA / EDITAR FICHA TÉCNICA            =*
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

  formulario.dataset.modo =
    "nuevo";


  /*==================================================*
  *=              ABRIR MODO NUEVO                  =*
  *==================================================*/

  document.addEventListener(
    "click",
    (event) => {

      const boton =
        event.target.closest(
          '[data-modal-abrir="modal-nueva-ficha-tecnica"]',
        );

      if (!boton) {
        return;
      }


      if (
        boton.hasAttribute(
          "data-editar-especificacion"
        )
      ) {
        return;
      }


      prepararModoNuevo(
        formulario,
      );
    },
  );


  /*==================================================*
  *=              ABRIR MODO EDITAR                 =*
  *==================================================*/

  document.addEventListener(
    "click",
    async (event) => {

      const boton =
        event.target.closest(
          "[data-editar-especificacion]",
        );

      if (!boton) {
        return;
      }


      const idEspecificacion =
        String(
          boton.dataset.especificacionId
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
            "No fue posible obtener la ficha técnica.",
          );
        }


        prepararModoEditar(
          formulario,
          resultado.especificacion,
        );


        abrirModalFicha();

      } catch (error) {

        console.error(
          "Error al cargar ficha técnica:",
          error,
        );

        mostrarNotificacion({
          tipo:
            "error",

          titulo:
            "No se pudo editar",

          mensaje:
            error.message ||
            "Ocurrió un error al cargar la ficha técnica.",
        });
      }
    },
  );


  /*==================================================*
  *=                    GUARDAR                      =*
  *==================================================*/

  formulario.addEventListener(
    "submit",
    async (event) => {

      event.preventDefault();


      if (!formulario.checkValidity()) {
        formulario.reportValidity();
        return;
      }


      const datos =
        obtenerDatosFormulario(
          formulario,
        );


      const modo =
        formulario.dataset.modo
        || "nuevo";


      const idEspecificacion =
        String(
          formulario.dataset
            .especificacionId
          ?? "",
        ).trim();


      const esEdicion =
        modo === "editar"
        && idEspecificacion !== "";


      const url =
        esEdicion
          ? `/proyectos/especificaciones/${idEspecificacion}`
          : "/proyectos/especificaciones";


      const metodo =
        esEdicion
          ? "PUT"
          : "POST";


      try {

        const respuesta =
          await fetch(
            url,
            {
              method:
                metodo,

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
            "No fue posible guardar la ficha técnica.",
          );
        }


        const especificacion =
          resultado.especificacion;


        /*==================================================*
        *=              ACTUALIZAR SELECTOR                =*
        *==================================================*/

        actualizarSelector(
          especificacion,
          esEdicion,
        );


        /*==================================================*
        *=               ACTUALIZAR TABLA                  =*
        *==================================================*/

        actualizarFilaTabla(
          especificacion,
        );


        cerrarModalFicha();

        prepararModoNuevo(
          formulario,
        );


        mostrarNotificacion({
          tipo:
            "success",

          titulo:
            esEdicion
              ? "Ficha técnica actualizada"
              : "Ficha técnica registrada",

          mensaje:
            resultado.mensaje,
        });


      } catch (error) {

        console.error(
          "Error al guardar ficha técnica:",
          error,
        );

        mostrarNotificacion({
          tipo:
            "error",

          titulo:
            esEdicion
              ? "No se pudo actualizar"
              : "No se pudo registrar",

          mensaje:
            error.message ||
            "Ocurrió un error al guardar la ficha técnica.",
        });
      }
    },
  );
}


/*==================================================*
*=             OBTENER DATOS FORMULARIO            =*
*==================================================*/

function obtenerDatosFormulario(
  formulario,
) {

  const formData =
    new FormData(
      formulario,
    );


  return {

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
}


/*==================================================*
*=                 MODO NUEVO                      =*
*==================================================*/

function prepararModoNuevo(
  formulario,
) {

  formulario.reset();

  formulario.dataset.modo =
    "nuevo";

  formulario.removeAttribute(
    "data-especificacion-id",
  );


  actualizarTituloModal(
    "Nueva ficha técnica",
  );

  actualizarTextoBotonGuardar(
    "Guardar ficha",
  );
}


/*==================================================*
*=                 MODO EDITAR                     =*
*==================================================*/

function prepararModoEditar(
  formulario,
  especificacion = {},
) {

  formulario.dataset.modo =
    "editar";

  formulario.dataset.especificacionId =
    String(
      especificacion.id_especificacion
      ?? "",
    );


  asignarValor(
    formulario,
    "codigo",
    especificacion.codigo,
  );

  asignarValor(
    formulario,
    "framework",
    especificacion.framework,
  );

  asignarValor(
    formulario,
    "version_framework",
    especificacion.version_framework,
  );

  asignarValor(
    formulario,
    "php",
    especificacion.php,
  );

  asignarValor(
    formulario,
    "base_datos",
    especificacion.base_datos,
  );

  asignarValor(
    formulario,
    "repositorio",
    especificacion.repositorio,
  );

  asignarValor(
    formulario,
    "entorno_local",
    especificacion.entorno_local,
  );


  actualizarTituloModal(
    "Editar ficha técnica",
  );

  actualizarTextoBotonGuardar(
    "Guardar cambios",
  );
}


/*==================================================*
*=                ASIGNAR VALOR                    =*
*==================================================*/

function asignarValor(
  formulario,
  nombre,
  valor,
) {

  const campo =
    formulario.querySelector(
      `[name="${nombre}"]`,
    );

  if (!campo) {
    return;
  }

  campo.value =
    valor
    ?? "";
}


/*==================================================*
*=              ACTUALIZAR SELECTOR                =*
*==================================================*/

function actualizarSelector(
  especificacion,
  esEdicion,
) {

  const selector =
    document.getElementById(
      "especificacion-tecnica",
    );

  if (
    !selector ||
    !especificacion
  ) {
    return;
  }


  const id =
    String(
      especificacion.id_especificacion
      ?? "",
    );


  let opcion =
    selector.querySelector(
      `option[value="${id}"]`,
    );


  if (!opcion) {

    opcion =
      document.createElement(
        "option",
      );

    opcion.value =
      id;

    selector.appendChild(
      opcion,
    );
  }


  opcion.textContent =
    especificacion.codigo
    || "Ficha técnica";


  if (!esEdicion) {
    opcion.selected =
      true;
  }
}


/*==================================================*
*=              ACTUALIZAR FILA TABLA              =*
*==================================================*/

function actualizarFilaTabla(
  especificacion,
) {

  if (!especificacion) {
    return;
  }


  const id =
    String(
      especificacion.id_especificacion
      ?? "",
    );


  const fila =
    document.querySelector(
      `tr[data-especificacion-id="${id}"]`,
    );


  if (!fila) {

    /*
     * Cuando se crea una ficha nueva todavía
     * no generamos la fila desde JS.
     *
     * Al recargar aparecerá desde el servidor.
     */
    return;
  }


  const celdas =
    fila.querySelectorAll(
      "td",
    );


  if (celdas.length < 5) {
    return;
  }


  const codigo =
    celdas[0].querySelector(
      "strong",
    );


  if (codigo) {
    codigo.textContent =
      especificacion.codigo
      || "—";
  }


  celdas[1].textContent =
    especificacion.framework
    || "—";

  celdas[2].textContent =
    especificacion.version_framework
    || "—";

  celdas[3].textContent =
    especificacion.php
    || "—";

  celdas[4].textContent =
    especificacion.base_datos
    || "—";
}


/*==================================================*
*=                 ABRIR MODAL                     =*
*==================================================*/

function abrirModalFicha() {

  const modal =
    document.getElementById(
      "modal-nueva-ficha-tecnica",
    );

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


/*==================================================*
*=                CERRAR MODAL                     =*
*==================================================*/

function cerrarModalFicha() {

  const modal =
    document.getElementById(
      "modal-nueva-ficha-tecnica",
    );

  if (!modal) {
    return;
  }


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
*=             ACTUALIZAR TÍTULO MODAL             =*
*==================================================*/

function actualizarTituloModal(
  texto,
) {

  const modal =
    document.getElementById(
      "modal-nueva-ficha-tecnica",
    );

  if (!modal) {
    return;
  }


  const titulo =
    modal.querySelector(
      ".modal__titulo",
    );

  if (titulo) {
    titulo.textContent =
      texto;
  }
}


/*==================================================*
*=          TEXTO DEL BOTÓN GUARDAR                =*
*==================================================*/

function actualizarTextoBotonGuardar(
  texto,
) {

  const boton =
    document.querySelector(
      '[form="form-nueva-ficha-tecnica"][type="submit"]',
    );

  if (!boton) {
    return;
  }

  boton.textContent =
    texto;
}