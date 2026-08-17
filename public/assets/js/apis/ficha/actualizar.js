import {
  renderArquitectura,
} from "./arquitectura/render.js";

import {
  renderDependencias,
} from "./dependencias/render.js";

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
} = {}) {


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
    "ficha-encabezado-estado-texto",
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