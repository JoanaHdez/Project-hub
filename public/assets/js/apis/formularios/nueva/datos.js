import {
  obtenerHeaders,
} from "./headers.js";

import {
  obtenerParametros,
} from "./parametros.js";

import {
  obtenerRespuestas,
} from "./respuestas.js";


/*==================================================*
*=              OBTENER EJEMPLO                     =*
*==================================================*/

function obtenerEjemplo(
  campoBody,
  formulario,
) {
  const valor =
    campoBody?.value.trim() ?? "";

  if (!valor) {
    return {};
  }

  return {
    metodo:
      formulario.elements
        .namedItem("metodo")
        ?.value ?? "",

    endpoint:
      formulario.elements
        .namedItem("endpoint")
        ?.value.trim() ?? "",

    url:
      formulario.elements
        .namedItem("url")
        ?.value.trim() ?? "",

    body:
      JSON.parse(valor),
  };
}


/*==================================================*
*=            OBTENER DATOS NUEVA API               =*
*==================================================*/

export function obtenerDatosNuevaApi(
  formulario,
) {
  const datosFormulario =
    new FormData(formulario);

  const idSistema =
    datosFormulario.get("id_sistema");

  const campoEjemploBody =
    document.getElementById(
      "nueva-api-ejemplo-body",
    );

  return {
    id_proyecto: Number(
      datosFormulario.get(
        "id_proyecto",
      ) ?? 0,
    ),

    id_sistema:
      idSistema === "" ||
      idSistema === null
        ? null
        : Number(idSistema),

    nombre: String(
      datosFormulario.get("nombre") ?? "",
    ).trim(),

    descripcion: String(
      datosFormulario.get(
        "descripcion",
      ) ?? "",
    ).trim(),

    estado: String(
      datosFormulario.get("estado") ?? "",
    ),

    metodo: String(
      datosFormulario.get("metodo") ?? "",
    ),

    endpoint: String(
      datosFormulario.get("endpoint") ?? "",
    ).trim(),

    url: String(
      datosFormulario.get("url") ?? "",
    ).trim(),

    autenticacion: String(
      datosFormulario.get(
        "autenticacion",
      ) ?? "",
    ).trim(),

    repositorio_url: String(
      datosFormulario.get(
        "repositorio_url",
      ) ?? "",
    ).trim(),

    ruta_local: String(
      datosFormulario.get(
        "ruta_local",
      ) ?? "",
    ).trim(),

    url_servidor: String(
      datosFormulario.get(
        "url_servidor",
      ) ?? "",
    ).trim(),

    responsable: String(
      datosFormulario.get(
        "responsable",
      ) ?? "",
    ).trim(),

    observaciones: String(
      datosFormulario.get(
        "observaciones",
      ) ?? "",
    ).trim(),

    headers:
      obtenerHeaders(),

    parametros:
      obtenerParametros(),

    ejemplo:
      obtenerEjemplo(
        campoEjemploBody,
        formulario,
      ),

    respuestas:
      obtenerRespuestas(),
  };
}