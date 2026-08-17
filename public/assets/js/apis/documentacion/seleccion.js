import {
  obtenerJson,
} from "./json.js";

import {
  renderHeaders,
} from "./headers.js";

import {
  renderParametros,
} from "./parametros.js";

import {
  renderEjemplo,
} from "./ejemplo.js";

import {
  renderRespuestas,
} from "./respuestas.js";

import {
  actualizarFichaTecnica,
} from "../ficha/actualizar.js";

/*==================================================
=              SELECCIÓN DE API                     =
==================================================*/

export function inicializarSeleccionApi() {
  const catalogoApis =
    document.querySelector(
      ".catalogo__lista",
    );

  if (!catalogoApis) {
    return;
  }

  catalogoApis.addEventListener(
    "click",
    (event) => {
      const selector =
        event.target.closest(
          ".api-selector",
        );

      if (!selector) {
        return;
      }


      /*==================================================
      =              MARCAR SELECCIÓN                    =
      ==================================================*/

      document
        .querySelectorAll(
          ".api-selector",
        )
        .forEach((elemento) => {
          elemento.classList.remove(
            "selector--activo",
          );
        });

      selector.classList.add(
        "selector--activo",
      );


      /*==================================================
      =               IDENTIFICADOR                     =
      ==================================================*/

      const idApi =
        selector.dataset.apiId ?? "";

      document.body.dataset.apiSeleccionadaId =
        idApi;


      /*==================================================
      =               DATOS DE LA API                   =
      ==================================================*/

      const nombre =
        selector.dataset.apiNombre ?? "";

      const metodo =
        selector.dataset.apiMetodo ?? "";

      const descripcion =
        selector.dataset.apiDescripcion ?? "";

      const proyecto =
        selector.dataset.apiProyecto ?? "";

      const estado =
        selector.dataset.apiEstado ?? "";

      const autenticacion =
        selector.dataset.apiAutenticacion ?? "";

      const endpoint =
        selector.dataset.apiEndpoint ?? "";

      const url =
        selector.dataset.apiUrl ?? "";

      const repositorio =
        selector.dataset.apiRepositorio ?? "";

      const ruta =
        selector.dataset.apiRuta ?? "";

      const servidor =
        selector.dataset.apiServidor ?? "";


      /*==================================================
      =              DOCUMENTACIÓN                       =
      ==================================================*/

      const headers =
        obtenerJson(
          selector.dataset.apiHeaders,
        );

      const parametros =
        obtenerJson(
          selector.dataset.apiParametros,
        );

      const ejemplo =
        obtenerJson(
          selector.dataset.apiEjemplo,
        );

      const respuestas =
        obtenerJson(
          selector.dataset.apiRespuestas,
        );

      const arquitectura =
        obtenerJson(
          selector.dataset.apiArquitectura,
        );

      const dependencias =
        obtenerJson(
          selector.dataset.apiDependencias,
        );

      const observaciones =
        obtenerJson(
          selector.dataset.apiObservaciones,
        );

      renderHeaders(
        headers,
      );

      renderParametros(
        parametros,
      );

      renderEjemplo(
        ejemplo,
      );

      renderRespuestas(
        respuestas,
      );


      /*==================================================
      =            INFORMACIÓN GENERAL                  =
      ==================================================*/

      actualizarTexto(
        "api-nombre",
        nombre,
      );

      actualizarTexto(
        "api-metodo",
        metodo,
      );

      actualizarTexto(
        "api-descripcion",
        descripcion,
      );

      actualizarTexto(
        "api-proyecto",
        proyecto,
      );

      actualizarTexto(
        "api-estado",
        estado,
      );

      actualizarTexto(
        "api-autenticacion",
        autenticacion,
      );

      actualizarTexto(
        "api-endpoint-metodo",
        metodo,
      );

      actualizarTexto(
        "api-endpoint",
        endpoint,
      );

      actualizarTexto(
        "api-url",
        url,
      );


      /*==================================================
      =               FICHA TÉCNICA                      =
      ==================================================*/

      actualizarFichaTecnica({
        nombre,
        proyecto,
        metodo,
        estado,
        descripcion,
        autenticacion,
        endpoint,
        url,
        repositorio,
        ruta,
        servidor,
        arquitectura,
        dependencias,
        observaciones,
      });

      /*==================================================*
      *=             INFORMACIÓN GENERAL                 =*
      *==================================================*/

      actualizarTexto(
        "ficha-general-nombre",
        nombre || "—",
      );

      actualizarTexto(
        "ficha-general-proyecto",
        proyecto || "—",
      );

      actualizarTexto(
        "ficha-general-metodo",
        metodo || "—",
      );

      actualizarTexto(
        "ficha-general-estado",
        estado || "—",
      );

      actualizarTexto(
        "ficha-general-descripcion",
        descripcion || "Sin descripción.",
      );

      /*==================================================
      =                    BOTONES                       =
      ==================================================*/

      const botonEditarApi =
        document.getElementById(
          "btn-editar-api",
        );

      const botonAdministrarApi =
        document.getElementById(
          "btn-administrar-api",
        );

      const botonFichaApi =
        document.getElementById(
          "abrir-ficha-tecnica",
        );

      if (botonEditarApi) {
        botonEditarApi.disabled =
          false;
      }

      if (botonAdministrarApi) {
        botonAdministrarApi.disabled =
          false;
      }

      if (botonFichaApi) {
        botonFichaApi.disabled =
          false;
      }
    },
  );
}


/*==================================================
=                ACTUALIZAR TEXTO                   =
==================================================*/

function actualizarTexto(
  id,
  valor,
) {
  const elemento =
    document.getElementById(
      id,
    );

  if (!elemento) {
    return;
  }

  elemento.textContent =
    valor;
}