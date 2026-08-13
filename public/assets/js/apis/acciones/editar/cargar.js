import {
  inicializarRelacionProyectoSistema,
  cargarSistemasPorProyecto,
} from "../../formularios/nueva/relaciones.js";

import {
  inicializarHeaders,
  cargarHeaders,
} from "../../formularios/nueva/headers.js";

import {
  inicializarParametros,
  cargarParametros,
} from "../../formularios/nueva/parametros.js";

import {
  inicializarRespuestas,
  cargarRespuestas,
} from "../../formularios/nueva/respuestas.js";

import {
  validarCampoJson,
} from "../../formularios/nueva/json.js";

import {
  obtenerJson,
} from "../../documentacion/json.js";


/*==================================================
=       INICIALIZAR COMPONENTES DE EDICIÓN          =
==================================================*/

export function inicializarComponentesEdicion() {
  inicializarRelacionProyectoSistema({
    proyectoId:
      "editar-api-proyecto",

    sistemaId:
      "editar-api-sistema",
  });

  inicializarHeaders({
    botonId:
      "editar-api-btn-agregar-header",

    contenedorId:
      "editar-api-headers",

    estadoVacioId:
      "editar-api-headers-vacio",
  });

  inicializarParametros({
    botonId:
      "editar-api-btn-agregar-parametro",

    contenedorId:
      "editar-api-parametros",

    estadoVacioId:
      "editar-api-parametros-vacio",
  });

  inicializarRespuestas({
    botonId:
      "editar-api-btn-agregar-respuesta",

    contenedorId:
      "editar-api-respuestas",

    estadoVacioId:
      "editar-api-respuestas-vacio",
  });

  const campoEjemploBody =
    document.getElementById(
      "editar-api-ejemplo-body",
    );

  if (campoEjemploBody) {
    campoEjemploBody.addEventListener(
      "input",
      () => {
        validarCampoJson(
          campoEjemploBody,
        );
      },
    );
  }
}


/*==================================================
=          CARGAR API EN FORMULARIO                 =
==================================================*/

export async function cargarApiEnFormulario(
  formulario,
) {
  const selector =
    document.querySelector(
      ".api-selector.selector--activo",
    );

  if (!selector || !formulario) {
    return false;
  }

  const idApi =
    selector.dataset.apiId ?? "";

  const idProyecto =
    selector.dataset.apiIdProyecto ?? "";

  const idSistema =
    selector.dataset.apiIdSistema ?? "";

  if (!idApi) {
    return false;
  }

  /*
   * Identificar qué API estamos editando.
   */
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

  asignarValor(
    formulario,
    "responsable",
    selector.dataset.apiResponsable ?? "",
  );

  asignarValor(
    formulario,
    "observaciones",
    selector.dataset.apiObservaciones ?? "",
  );


  /*==================================================
  =              PROYECTO / SISTEMA                  =
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
  =                    HEADERS                       =
  ==================================================*/

  const headers =
    obtenerJson(
      selector.dataset.apiHeaders,
    );

  cargarHeaders({
    contenedorId:
      "editar-api-headers",

    estadoVacioId:
      "editar-api-headers-vacio",

    headers:
      Array.isArray(headers)
        ? headers
        : [],
  });


  /*==================================================
  =                  PARÁMETROS                      =
  ==================================================*/

  const parametros =
    obtenerJson(
      selector.dataset.apiParametros,
    );

  cargarParametros({
    contenedorId:
      "editar-api-parametros",

    estadoVacioId:
      "editar-api-parametros-vacio",

    parametros:
      Array.isArray(parametros)
        ? parametros
        : [],
  });


  /*==================================================
  =              EJEMPLO DE CONSUMO                  =
  ==================================================*/

  const campoEjemploBody =
    document.getElementById(
      "editar-api-ejemplo-body",
    );

  const ejemplo =
    obtenerJson(
      selector.dataset.apiEjemplo,
    );

  if (campoEjemploBody) {
    if (
      ejemplo &&
      !Array.isArray(ejemplo) &&
      ejemplo.body &&
      typeof ejemplo.body === "object"
    ) {
      campoEjemploBody.value =
        JSON.stringify(
          ejemplo.body,
          null,
          2,
        );
    } else {
      campoEjemploBody.value =
        "";
    }

    validarCampoJson(
      campoEjemploBody,
    );
  }


  /*==================================================
  =                   RESPUESTAS                     =
  ==================================================*/

  const respuestas =
    obtenerJson(
      selector.dataset.apiRespuestas,
    );

  cargarRespuestas({
    contenedorId:
      "editar-api-respuestas",

    estadoVacioId:
      "editar-api-respuestas-vacio",

    respuestas:
      Array.isArray(respuestas)
        ? respuestas
        : [],
  });

  return true;
}


/*==================================================
=                    AUXILIAR                       =
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