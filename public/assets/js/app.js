/*==================================================
=                     MODALES                       =
==================================================*/

document.addEventListener("click", (event) => {
  const botonAbrir = event.target.closest("[data-modal-abrir]");
  const botonCerrar = event.target.closest("[data-modal-cerrar]");

  if (botonAbrir) {
    event.preventDefault();

    if (botonAbrir.disabled) {
      return;
    }

    const modalId = botonAbrir.dataset.modalAbrir;
    const modal = document.getElementById(modalId);

    if (modal) {
      modal.classList.add("modal--visible");
      modal.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";
    }
  }

  if (botonCerrar) {
    const modal = botonCerrar.closest(".modal");

    if (modal) {
      modal.classList.remove("modal--visible");
      modal.setAttribute("aria-hidden", "true");
      document.body.style.overflow = "";
    }
  }
});

document.addEventListener("keydown", (event) => {
  if (event.key !== "Escape") {
    return;
  }

  const modalVisible = document.querySelector(".modal.modal--visible");

  if (modalVisible) {
    modalVisible.classList.remove("modal--visible");
    modalVisible.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }
});

/*==================================================
=              BÚSQUEDA EN TABLAS                  =
==================================================*/

document.addEventListener("input", (event) => {
  const buscador = event.target.closest("[data-tabla-busqueda]");

  if (!buscador) {
    return;
  }

  const tablaId = buscador.dataset.tablaBusqueda;
  const tabla = document.getElementById(tablaId);

  if (!tabla) {
    return;
  }

  const termino = normalizarTexto(buscador.value);
  const filas = tabla.querySelectorAll("tbody tr");

  filas.forEach((fila) => {
    if (fila.querySelector(".tabla-vacia")) {
      return;
    }

    const contenido = normalizarTexto(fila.textContent);

    fila.hidden = !contenido.includes(termino);
  });
});

/*==================================================
=                     SISTEMAS                      =
==================================================*/

const selectoresSistema = document.querySelectorAll(".sistema-selector");

const visorFrame = document.getElementById("visor-sistema-frame");
const visorVacio = document.getElementById("visor-sistema-vacio");
const visorCargando = document.getElementById("visor-sistema-cargando");
const visorNombre = document.getElementById("visor-sistema-nombre");

const botonRecargar = document.getElementById("recargar-sistema");
const enlaceExterno = document.getElementById("abrir-sistema-externo");
const botonFichaUbicacion = document.getElementById("btn-ficha-ubicacion");

const fichaProyecto = document.getElementById("ficha-proyecto");
const fichaEstado = document.getElementById("ficha-estado");
const fichaRepositorio = document.getElementById("ficha-repositorio");
const fichaRuta = document.getElementById("ficha-ruta");
const fichaServidor = document.getElementById("ficha-servidor");

const buscadorSistema = document.getElementById("buscar-sistema");

let sistemaUrlActual = "";

selectoresSistema.forEach((selector) => {
  selector.addEventListener("click", () => {
    const nombre = selector.dataset.sistemaNombre ?? "";
    const url = selector.dataset.sistemaUrl ?? "";

    const modoVisualizacion = selector.dataset.modoVisualizacion ?? "registro";

    const proyecto = selector.dataset.proyecto ?? "";
    const estado = selector.dataset.estado ?? "";
    const repositorio = selector.dataset.repositorio ?? "";
    const ruta = selector.dataset.ruta ?? "";
    const servidor = selector.dataset.servidor ?? "";

    sistemaUrlActual = url;

    selectoresSistema.forEach((elemento) => {
      elemento.classList.remove("sistema-selector--activo");
    });

    selector.classList.add("sistema-selector--activo");

    if (visorNombre) {
      visorNombre.textContent = nombre || "Sistema seleccionado";
    }

    /*
     * La ficha de ubicación siempre se habilita
     * cuando existe un sistema seleccionado.
     */
    if (botonFichaUbicacion) {
      botonFichaUbicacion.disabled = false;
    }

    if (fichaProyecto) {
      fichaProyecto.textContent = proyecto || "—";
    }

    if (fichaEstado) {
      fichaEstado.textContent = estado || "—";
    }

    if (fichaRepositorio) {
      fichaRepositorio.textContent = repositorio || "—";
    }

    if (fichaRuta) {
      fichaRuta.textContent = ruta || "—";
    }

    if (fichaServidor) {
      fichaServidor.textContent = servidor || "—";
    }

    /*
     * Si no existe una URL, no se intenta cargar
     * el sistema dentro del iframe.
     */
    /*
     /*
 * CASO 1:
 * Visualización integrada y sí existe URL.
 */
    if (modoVisualizacion === "integrado" && url) {
      cargarSistemaEnVisor(url);
      return;
    }

    /*
     * CASO 2:
     * Visualización integrada, pero todavía no existe URL.
     */
    if (modoVisualizacion === "integrado" && !url) {
      mostrarEstadoVisor({
        titulo: "Sistema pendiente de publicación",
        descripcion:
          "Este sistema está configurado para visualizarse dentro de Project Hub, pero todavía no cuenta con una URL de servidor.",
        permitirRecarga: false,
        permitirAbrirExterno: false,
      });

      return;
    }

    /*
     * CASO 3:
     * No se integra mediante iframe, pero puede abrirse externamente.
     */
    if (modoVisualizacion === "externo" && url) {
      mostrarEstadoVisor({
        titulo: "Visualización externa",
        descripcion:
          "Este sistema no se mostrará dentro de Project Hub. Puedes abrirlo en una pestaña nueva.",
        permitirRecarga: false,
        permitirAbrirExterno: true,
        url,
      });

      return;
    }

    /*
     * CASO 4:
     * El sistema únicamente está registrado.
     */
    mostrarEstadoVisor({
      titulo: "Sistema registrado en Project Hub",
      descripcion:
        "Este sistema no cuenta con visualización integrada ni con una URL de servidor disponible.",
      permitirRecarga: false,
      permitirAbrirExterno: false,
    });
  });
});

/*==================================================
=              RECARGAR SISTEMA                    =
==================================================*/

botonRecargar?.addEventListener("click", () => {
  if (!sistemaUrlActual || !visorFrame) {
    return;
  }

  if (visorVacio) {
    visorVacio.hidden = true;
  }

  if (visorCargando) {
    visorCargando.hidden = false;
  }

  visorFrame.hidden = true;
  visorFrame.src = "about:blank";

  window.setTimeout(() => {
    visorFrame.src = sistemaUrlActual;
  }, 100);
});

/*==================================================
=                CARGA DEL IFRAME                  =
==================================================*/

visorFrame?.addEventListener("load", () => {
  /*
   * Evita mostrar el iframe cuando está usando
   * about:blank durante el proceso de recarga.
   */
  if (
    visorFrame.getAttribute("src") === "about:blank" ||
    visorFrame.src === "about:blank"
  ) {
    return;
  }

  if (visorCargando) {
    visorCargando.hidden = true;
  }

  visorFrame.hidden = false;
});

/*==================================================
=             BÚSQUEDA DE SISTEMAS                 =
==================================================*/

buscadorSistema?.addEventListener("input", () => {
  const termino = normalizarTexto(buscadorSistema.value);

  selectoresSistema.forEach((selector) => {
    const contenido = normalizarTexto(selector.textContent);

    selector.hidden = !contenido.includes(termino);
  });
});

/*==================================================
=          ABRIR SISTEMA EN OTRA PESTAÑA           =
==================================================*/

enlaceExterno?.addEventListener("click", (event) => {
  const deshabilitado = enlaceExterno.getAttribute("aria-disabled") === "true";

  if (deshabilitado || !sistemaUrlActual) {
    event.preventDefault();
  }
});

/*==================================================
=                 FUNCIONES AUXILIARES              =
==================================================*/

function cargarSistemaEnVisor(url) {
  if (visorVacio) {
    visorVacio.hidden = true;
  }

  if (visorCargando) {
    visorCargando.hidden = false;
  }

  if (visorFrame) {
    visorFrame.hidden = true;
    visorFrame.src = url;
  }

  if (botonRecargar) {
    botonRecargar.disabled = false;
  }

  if (enlaceExterno) {
    enlaceExterno.href = url;
    enlaceExterno.setAttribute("aria-disabled", "false");
  }
}

function normalizarTexto(texto) {
  return String(texto ?? "")
    .trim()
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "");
}

function mostrarEstadoVisor({
  titulo,
  descripcion,
  permitirRecarga = false,
  permitirAbrirExterno = false,
  url = "",
}) {
  if (visorCargando) {
    visorCargando.hidden = true;
  }

  if (visorFrame) {
    visorFrame.hidden = true;
    visorFrame.removeAttribute("src");
  }

  if (visorVacio) {
    visorVacio.hidden = false;

    const tituloVisor = visorVacio.querySelector("strong");
    const descripcionVisor = visorVacio.querySelector("p");

    if (tituloVisor) {
      tituloVisor.textContent = titulo;
    }

    if (descripcionVisor) {
      descripcionVisor.textContent = descripcion;
    }
  }

  if (botonRecargar) {
    botonRecargar.disabled = !permitirRecarga;
  }

  if (enlaceExterno) {
    if (permitirAbrirExterno && url) {
      enlaceExterno.href = url;
      enlaceExterno.setAttribute("aria-disabled", "false");
    } else {
      enlaceExterno.href = "#";
      enlaceExterno.setAttribute("aria-disabled", "true");
    }
  }
}

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

  const contenido = `${url}

${JSON.stringify(body, null, 2)}`;

  apiEjemploCodigo.textContent = contenido;

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
<code>${JSON.stringify(respuesta.body ?? {}, null, 2)}</code>
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
