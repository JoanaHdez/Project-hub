import {
  inicializarRelacionProyectoSistema,
  cargarSistemasPorProyecto,
} from "../formularios/nueva/relaciones.js";

import {
  inicializarHeaders,
} from "../formularios/nueva/headers.js";

import {
  inicializarParametros,
} from "../formularios/nueva/parametros.js";

import {
  inicializarRespuestas,
} from "../formularios/nueva/respuestas.js";

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

  inicializarRelacionProyectoSistema({
    proyectoId: "editar-api-proyecto",
    sistemaId: "editar-api-sistema",
  });

  inicializarHeaders({
  botonId: "editar-api-btn-agregar-header",
  contenedorId: "editar-api-headers",
  estadoVacioId: "editar-api-headers-vacio",
});

inicializarParametros({
  botonId: "editar-api-btn-agregar-parametro",
  contenedorId: "editar-api-parametros",
  estadoVacioId: "editar-api-parametros-vacio",
});

inicializarRespuestas({
  botonId: "editar-api-btn-agregar-respuesta",
  contenedorId: "editar-api-respuestas",
  estadoVacioId: "editar-api-respuestas-vacio",
});

  botonEditar.addEventListener(
    "click",
    async () => {
      const selector =
        document.querySelector(
          ".api-selector.selector--activo",
        );

      if (!selector) {
        return;
      }

      const idApi =
        selector.dataset.apiId ?? "";

      const idProyecto =
        selector.dataset.apiIdProyecto ?? "";

      const idSistema =
        selector.dataset.apiIdSistema ?? "";

      if (!idApi) {
        return;
      }


      /*==================================================
      =             IDENTIFICAR API                      =
      ==================================================*/

      formulario.dataset.apiId =
        idApi;


      /*==================================================
      =               DATOS BÁSICOS                      =
      ==================================================*/

      asignarValor(
        formulario,
        "nombre",
        selector.dataset.apiNombre ?? "",
      );

      asignarValor(
        formulario,
        "descripcion",
        selector.dataset.apiDescripcion ?? "",
      );

      asignarValor(
        formulario,
        "estado",
        selector.dataset.apiEstado ?? "",
      );

      asignarValor(
        formulario,
        "metodo",
        selector.dataset.apiMetodo ?? "",
      );

      asignarValor(
        formulario,
        "endpoint",
        selector.dataset.apiEndpoint ?? "",
      );

      asignarValor(
        formulario,
        "url",
        selector.dataset.apiUrl ?? "",
      );

      asignarValor(
        formulario,
        "autenticacion",
        selector.dataset.apiAutenticacion ?? "",
      );

      asignarValor(
        formulario,
        "repositorio_url",
        selector.dataset.apiRepositorio ?? "",
      );

      asignarValor(
        formulario,
        "ruta_local",
        selector.dataset.apiRuta ?? "",
      );

      asignarValor(
        formulario,
        "url_servidor",
        selector.dataset.apiServidor ?? "",
      );


      /*==================================================
      =                 PROYECTO                         =
      ==================================================*/

      const selectProyecto =
        document.getElementById(
          "editar-api-proyecto",
        );

      const selectSistema =
        document.getElementById(
          "editar-api-sistema",
        );

      if (
        selectProyecto &&
        idProyecto
      ) {
        selectProyecto.value =
          String(idProyecto);
      }


      /*==================================================
      =             SISTEMA ASOCIADO                     =
      ==================================================*/

      if (
        selectProyecto &&
        selectSistema
      ) {
        await cargarSistemasPorProyecto({
          selectProyecto,
          selectSistema,
          idSistemaSeleccionado:
            idSistema || null,
        });
      }


      /*==================================================
      =                  ABRIR MODAL                      =
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
    },
  );
}


/*==================================================
=                  AUXILIAR                        =
==================================================*/

function asignarValor(
  formulario,
  nombre,
  valor,
) {
  const campo =
    formulario.elements.namedItem(
      nombre,
    );

  if (!campo) {
    return;
  }

  campo.value =
    valor ?? "";
}