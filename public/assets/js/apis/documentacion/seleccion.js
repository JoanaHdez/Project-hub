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

      renderHeaders(headers);
      renderParametros(parametros);
      renderEjemplo(ejemplo);
      renderRespuestas(respuestas);

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

      actualizarTexto(
        "ficha-api-proyecto",
        proyecto || "—",
      );

      actualizarTexto(
        "ficha-api-estado",
        estado || "—",
      );

      actualizarTexto(
        "ficha-api-repositorio",
        repositorio || "—",
      );

      actualizarTexto(
        "ficha-api-ruta",
        ruta || "—",
      );

      actualizarTexto(
        "ficha-api-servidor",
        servidor || "—",
      );

      const botonFichaApi =
        document.getElementById(
          "btn-ficha-api",
        );

      if (botonFichaApi) {
        botonFichaApi.disabled = false;
      }
    },
  );
}


function actualizarTexto(
  id,
  valor,
) {
  const elemento =
    document.getElementById(id);

  if (!elemento) {
    return;
  }

  elemento.textContent =
    valor;
}