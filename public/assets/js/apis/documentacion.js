/*==================================================
=                       APIs                        =
==================================================*/

const apiHeadersContenedor = document.getElementById("api-headers-contenedor");
const apiHeadersBody = document.getElementById("api-headers");
const apiHeadersVacio = document.getElementById("api-headers-vacio");
const selectoresApi = document.querySelectorAll(".api-selector");

const apiParametrosContenedor = document.getElementById(
  "api-parametros-contenedor",
);
const apiParametrosBody = document.getElementById("api-parametros");
const apiParametrosVacio = document.getElementById("api-parametros-vacio");

const apiEjemploContenedor = document.getElementById("api-ejemplo-contenedor");
const apiEjemploCodigo = document.getElementById("api-ejemplo");
const apiEjemploVacio = document.getElementById("api-ejemplo-vacio");
const apiEjemploMetodo = document.getElementById("api-ejemplo-metodo");
const botonCopiarEjemplo = document.getElementById("copiar-ejemplo");

const apiRespuestasContenedor = document.getElementById(
  "api-respuestas-contenedor",
);
const apiRespuestas = document.getElementById("api-respuestas");
const apiRespuestasVacio = document.getElementById("api-respuestas-vacio");

const apiNombre = document.getElementById("api-nombre");
const apiMetodo = document.getElementById("api-metodo");
const apiDescripcion = document.getElementById("api-descripcion");

const apiProyecto = document.getElementById("api-proyecto");
const apiEstado = document.getElementById("api-estado");
const apiAutenticacion = document.getElementById("api-autenticacion");

const apiEndpointMetodo = document.getElementById("api-endpoint-metodo");
const apiEndpoint = document.getElementById("api-endpoint");
const apiUrl = document.getElementById("api-url");

const botonFichaApi = document.getElementById("btn-ficha-api");

const fichaApiProyecto = document.getElementById("ficha-api-proyecto");
const fichaApiEstado = document.getElementById("ficha-api-estado");
const fichaApiRepositorio = document.getElementById("ficha-api-repositorio");
const fichaApiRuta = document.getElementById("ficha-api-ruta");
const fichaApiServidor = document.getElementById("ficha-api-servidor");

selectoresApi.forEach((selector) => {
  selector.addEventListener("click", () => {
    selectoresApi.forEach((elemento) => {
      elemento.classList.remove("selector--activo");
    });

    selector.classList.add("selector--activo");

    const nombre = selector.dataset.apiNombre ?? "";
    const metodo = selector.dataset.apiMetodo ?? "";
    const descripcion = selector.dataset.apiDescripcion ?? "";

    const proyecto = selector.dataset.apiProyecto ?? "";
    const estado = selector.dataset.apiEstado ?? "";
    const autenticacion = selector.dataset.apiAutenticacion ?? "";

    const endpoint = selector.dataset.apiEndpoint ?? "";
    const url = selector.dataset.apiUrl ?? "";

    const repositorio = selector.dataset.apiRepositorio ?? "";
    const ruta = selector.dataset.apiRuta ?? "";
    const servidor = selector.dataset.apiServidor ?? "";

    const headers = obtenerJson(selector.dataset.apiHeaders);
    const parametros = obtenerJson(selector.dataset.apiParametros);
    const ejemplo = obtenerJson(selector.dataset.apiEjemplo);
    const respuestas = obtenerJson(selector.dataset.apiRespuestas);

    renderHeaders(headers);
    renderParametros(parametros);
    renderEjemplo(ejemplo);
    renderRespuestas(respuestas);

    apiNombre.textContent = nombre;
    apiMetodo.textContent = metodo;
    apiDescripcion.textContent = descripcion;

    apiProyecto.textContent = proyecto;
    apiEstado.textContent = estado;
    apiAutenticacion.textContent = autenticacion;

    apiEndpointMetodo.textContent = metodo;
    apiEndpoint.textContent = endpoint;
    apiUrl.textContent = url;

    if (botonFichaApi) {
      botonFichaApi.disabled = false;
    }

    if (fichaApiProyecto) {
      fichaApiProyecto.textContent = proyecto || "—";
    }

    if (fichaApiEstado) {
      fichaApiEstado.textContent = estado || "—";
    }

    if (fichaApiRepositorio) {
      fichaApiRepositorio.textContent = repositorio || "—";
    }

    if (fichaApiRuta) {
      fichaApiRuta.textContent = ruta || "—";
    }

    if (fichaApiServidor) {
      fichaApiServidor.textContent = servidor || "—";
    }
  });
});

function renderHeaders(headers) {
  apiHeadersBody.innerHTML = "";

  if (headers.length === 0) {
    apiHeadersContenedor.hidden = true;
    apiHeadersVacio.hidden = false;
    apiHeadersVacio.textContent = "Esta API no tiene headers documentados.";
    return;
  }

  headers.forEach((header) => {
    const fila = document.createElement("tr");

    fila.innerHTML = `
            <td>${header.nombre ?? "—"}</td>
            <td>${header.valor ?? "—"}</td>
            <td>${header.obligatorio ? "Sí" : "No"}</td>
            <td>${header.descripcion ?? "—"}</td>
        `;

    apiHeadersBody.appendChild(fila);
  });

  apiHeadersContenedor.hidden = false;
  apiHeadersVacio.hidden = true;
}

function renderParametros(parametros) {
  apiParametrosBody.innerHTML = "";

  if (parametros.length === 0) {
    apiParametrosContenedor.hidden = true;
    apiParametrosVacio.hidden = false;
    apiParametrosVacio.textContent =
      "Esta API no tiene parámetros documentados.";
    return;
  }

  parametros.forEach((parametro) => {
    const fila = document.createElement("tr");

    fila.innerHTML = `
            <td>${parametro.nombre ?? "—"}</td>
            <td>${parametro.tipo ?? "—"}</td>
            <td>${parametro.obligatorio ? "Sí" : "No"}</td>
            <td>${parametro.descripcion ?? "—"}</td>
        `;

    apiParametrosBody.appendChild(fila);
  });

  apiParametrosContenedor.hidden = false;
  apiParametrosVacio.hidden = true;
}

function renderEjemplo(ejemplo) {
  apiEjemploCodigo.textContent = "";

  if (!ejemplo || Array.isArray(ejemplo) || Object.keys(ejemplo).length === 0) {
    apiEjemploContenedor.hidden = true;
    apiEjemploVacio.hidden = false;
    apiEjemploVacio.textContent = "Esta API no tiene un ejemplo documentado.";
    return;
  }

  const metodo = ejemplo.metodo ?? "POST";
  const url = ejemplo.url ?? ejemplo.endpoint ?? "";
  const body = ejemplo.body ?? {};

  apiEjemploMetodo.textContent = metodo;

  apiEjemploMetodo.className = `badge-metodo badge-metodo--${metodo.toLowerCase()}`;

  apiEjemploCodigo.innerHTML = `${url}

${resaltarJson(body)}`;

  apiEjemploContenedor.hidden = false;
  apiEjemploVacio.hidden = true;
}

function obtenerClaseRespuesta(codigo) {
  if (codigo >= 200 && codigo < 300) {
    return "respuesta-http respuesta-http--success";
  }

  if (codigo >= 300 && codigo < 400) {
    return "respuesta-http respuesta-http--redirect";
  }

  if (codigo >= 400 && codigo < 500) {
    return "respuesta-http respuesta-http--warning";
  }

  return "respuesta-http respuesta-http--error";
}

function resaltarJson(json) {
  json = JSON.stringify(json, null, 2);

  json = json.replace(
    /("(\\u[\da-fA-F]{4}|\\[^u]|[^\\"])*")(\s*:)?|\b(true|false|null)\b|-?\d+(\.\d+)?/g,
    function (match) {
      let clase = "json-numero";

      if (/^"/.test(match)) {
        if (/:$/.test(match)) {
          clase = "json-clave";
        } else {
          clase = "json-string";
        }
      } else if (/true|false/.test(match)) {
        clase = "json-boolean";
      } else if (/null/.test(match)) {
        clase = "json-null";
      }

      return `<span class="${clase}">${match}</span>`;
    },
  );

  return json;
}

function renderRespuestas(respuestas) {
  apiRespuestas.innerHTML = "";

  if (!respuestas || respuestas.length === 0) {
    apiRespuestasContenedor.hidden = true;
    apiRespuestasVacio.hidden = false;
    apiRespuestasVacio.textContent =
      "Esta API no tiene respuestas documentadas.";
    return;
  }

  respuestas.forEach((respuesta) => {
    const tarjeta = document.createElement("div");

    tarjeta.className = "respuesta-api";

    const clase = obtenerClaseRespuesta(respuesta.codigo);

    tarjeta.innerHTML = `

<div class="respuesta-api__encabezado">

    <span class="${clase}">
        ${respuesta.codigo}
    </span>

    <strong>${respuesta.descripcion}</strong>

</div>

<pre class="codigo-api">
<code>${resaltarJson(respuesta.body ?? {})}</code>
</pre>

`;

    apiRespuestas.appendChild(tarjeta);
  });

  apiRespuestasContenedor.hidden = false;
  apiRespuestasVacio.hidden = true;
}

if (botonCopiarEjemplo) {
  botonCopiarEjemplo.addEventListener("click", async () => {
    try {
      await navigator.clipboard.writeText(apiEjemploCodigo.textContent);

      botonCopiarEjemplo.textContent = "¡Copiado!";

      setTimeout(() => {
        botonCopiarEjemplo.textContent = "Copiar";
      }, 1500);
    } catch (error) {
      console.error(error);
    }
  });
}

function obtenerJson(valor) {
  try {
    return JSON.parse(valor || "[]");
  } catch (error) {
    console.error("No fue posible interpretar el JSON.", error);
    return [];
  }
}
