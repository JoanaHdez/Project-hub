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