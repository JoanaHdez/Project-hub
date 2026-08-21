import {
  renderArquitectura,
} from "./arquitectura/render.js";

import {
  renderDependencias,
} from "./dependencias/render.js";

import {
  renderObservaciones,
} from "./observaciones/render.js";

import {
  renderHistorial,
} from "./historial/render.js";


/*==================================================*
*=            ACTUALIZAR FICHA TÉCNICA             =*
*==================================================*/

export function actualizarFichaTecnica({
  nombre = "",
  proyecto = "",
  metodo = "",
  estado = "",
  descripcion = "",
  autenticacion = "",
  endpoint = "",
  url = "",
  repositorio = "",
  ruta = "",
  servidor = "",
  arquitectura = {},
  dependencias = [],
  observaciones = [],
  historial = [],
} = {}) {


  /*==================================================*
  *=              VERSIÓN ACTUAL                    =*
  *==================================================*/

  const versionActual =
    obtenerVersionActual(
      historial,
    );


  /*==================================================*
  *=                 ENCABEZADO                      =*
  *==================================================*/

  actualizarTexto(
    "titulo-ficha-tecnica",
    nombre || "—",
  );

  actualizarTexto(
    "ficha-encabezado-proyecto",
    proyecto || "Sin proyecto",
  );

  actualizarTexto(
    "ficha-encabezado-metodo",
    metodo || "—",
  );

  actualizarTexto(
    "ficha-encabezado-version",
    versionActual,
  );

  actualizarTexto(
    "ficha-encabezado-estado-texto",
    estado || "—",
  );


  /*==================================================*
  *=                    RESUMEN                       =*
  *==================================================*/

  actualizarTexto(
    "ficha-resumen-metodo",
    metodo || "—",
  );

  actualizarTexto(
    "ficha-resumen-version",
    versionActual,
  );

  actualizarTexto(
    "ficha-resumen-estado",
    estado || "—",
  );


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


  /*==================================================*
  *=                    UBICACIÓN                     =*
  *==================================================*/

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

  actualizarTexto(
    "ficha-api-endpoint",
    endpoint || "—",
  );


  /*==================================================*
  *=                  ARQUITECTURA                    =*
  *==================================================*/

  renderArquitectura(
    arquitectura,
  );


  /*==================================================*
  *=                  DEPENDENCIAS                    =*
  *==================================================*/

  renderDependencias(
    dependencias,
  );


  /*==================================================*
  *=                  OBSERVACIONES                   =*
  *==================================================*/

  renderObservaciones(
    observaciones,
  );


  /*==================================================*
  *=                  HISTORIAL                       =*
  *==================================================*/

  renderHistorial(
    historial,
  );
}


/*==================================================*
*=              OBTENER VERSIÓN ACTUAL             =*
*==================================================*/

function obtenerVersionActual(
  historial,
) {

  if (
    !Array.isArray(historial) ||
    historial.length === 0
  ) {
    return "Sin versión";
  }


  const ultimoRegistro =
    historial[
      historial.length - 1
    ];


  const version =
    String(
      ultimoRegistro?.version
      ?? "",
    ).trim();


  return (
    version ||
    "Sin versión"
  );
}


/*==================================================*
*=                ACTUALIZAR TEXTO                  =*
*==================================================*/

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