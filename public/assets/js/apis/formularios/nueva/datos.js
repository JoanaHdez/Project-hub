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

function obtenerEjemplo({
  formulario,
  campoEjemploId,
}) {
  const campoBody =
    document.getElementById(
      campoEjemploId,
    );

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
*=             OBTENER DATOS DE API                 =*
*==================================================*/

export function obtenerDatosApi({
  formulario,
  prefijo = "nueva-api",
}) {
  const datosFormulario =
    new FormData(formulario);

  const idSistema =
    datosFormulario.get(
      "id_sistema",
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
      datosFormulario.get(
        "nombre",
      ) ?? "",
    ).trim(),

    descripcion: String(
      datosFormulario.get(
        "descripcion",
      ) ?? "",
    ).trim(),

    estado: String(
      datosFormulario.get(
        "estado",
      ) ?? "",
    ),

    metodo: String(
      datosFormulario.get(
        "metodo",
      ) ?? "",
    ),

    endpoint: String(
      datosFormulario.get(
        "endpoint",
      ) ?? "",
    ).trim(),

    url: String(
      datosFormulario.get(
        "url",
      ) ?? "",
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
      obtenerHeaders({
        contenedorId:
          `${prefijo}-headers`,
      }),

    parametros:
      obtenerParametros({
        contenedorId:
          `${prefijo}-parametros`,
      }),

    ejemplo:
      obtenerEjemplo({
        formulario,
        campoEjemploId:
          `${prefijo}-ejemplo-body`,
      }),

    respuestas:
      obtenerRespuestas({
        contenedorId:
          `${prefijo}-respuestas`,
      }),
  };
}


/*==================================================*
*=         COMPATIBILIDAD NUEVA API                 =*
*==================================================*/

export function obtenerDatosNuevaApi(
  formulario,
) {
  return obtenerDatosApi({
    formulario,
    prefijo: "nueva-api",
  });
}


/*==================================================*
*=         COMPATIBILIDAD EDITAR API                =*
*==================================================*/

export function obtenerDatosEditarApi(
  formulario,
) {
  return obtenerDatosApi({
    formulario,
    prefijo: "editar-api",
  });
}