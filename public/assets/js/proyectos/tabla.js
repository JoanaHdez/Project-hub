const REGISTROS_POR_PAGINA = 6;

let paginaActual = 1;

/*==================================================*
*=              OBTENER ELEMENTOS                   =*
*==================================================*/

function obtenerTabla() {
  return document.getElementById("tabla-proyectos");
}

function obtenerFilas() {
  const tabla = obtenerTabla();

  if (!tabla) {
    return [];
  }

  return Array.from(
    tabla.querySelectorAll(
      "tbody tr[data-proyecto-id]",
    ),
  );
}

function obtenerBuscador() {
  return document.getElementById("buscar-proyecto");
}

function obtenerPieTabla() {
  const tabla = obtenerTabla();

  if (!tabla) {
    return null;
  }

  return tabla
    .closest(".tabla-componente")
    ?.querySelector(".tabla-pie");
}

/*==================================================*
*=               NORMALIZAR TEXTO                   =*
*==================================================*/

function normalizarTexto(texto) {
  return String(texto ?? "")
    .trim()
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "");
}

/*==================================================*
*=              FILTRAR PROYECTOS                   =*
*==================================================*/

function obtenerFilasFiltradas() {
  const filas = obtenerFilas();
  const buscador = obtenerBuscador();

  const termino = normalizarTexto(
    buscador?.value ?? "",
  );

  if (termino === "") {
    return filas;
  }

  return filas.filter((fila) => {
    const contenido = normalizarTexto(
      fila.textContent,
    );

    return contenido.includes(termino);
  });
}

/*==================================================*
*=                  PAGINACIÓN                      =*
*==================================================*/

function actualizarTabla() {
  const todasLasFilas = obtenerFilas();
  const filasFiltradas = obtenerFilasFiltradas();

  const totalRegistros = filasFiltradas.length;

  const totalPaginas = Math.max(
    1,
    Math.ceil(
      totalRegistros / REGISTROS_POR_PAGINA,
    ),
  );

  /*
   * Evitar quedarnos en una página que ya
   * no existe después de eliminar o filtrar.
   */
  if (paginaActual > totalPaginas) {
    paginaActual = totalPaginas;
  }

  if (paginaActual < 1) {
    paginaActual = 1;
  }

  /*
   * Primero ocultamos absolutamente todas
   * las filas.
   */
  todasLasFilas.forEach((fila) => {
    fila.style.display = "none";
  });

  /*
   * Calcular los registros de la página actual.
   */
  const indiceInicio =
    (paginaActual - 1) *
    REGISTROS_POR_PAGINA;

  const indiceFin =
    indiceInicio +
    REGISTROS_POR_PAGINA;

  const filasPagina =
    filasFiltradas.slice(
      indiceInicio,
      indiceFin,
    );

  /*
   * Mostrar únicamente los registros
   * correspondientes a esta página.
   */
  filasPagina.forEach((fila) => {
    fila.style.display = "";
  });

  actualizarContador(
    totalRegistros,
    indiceInicio,
    filasPagina.length,
  );

  actualizarPaginacion(totalPaginas);

  actualizarVisibilidadPie(totalRegistros);
}

/*==================================================*
*=               CONTADOR DEL PIE                   =*
*==================================================*/

function actualizarContador(
  totalRegistros,
  indiceInicio,
  registrosMostrados,
) {
  const pie = obtenerPieTabla();

  if (!pie) {
    return;
  }

  const contador = pie.querySelector(
    ".tabla-pie__contador",
  );

  if (!contador) {
    return;
  }

  if (totalRegistros === 0) {
    contador.innerHTML = `
      Mostrando
      <strong>0</strong>
      registros
    `;

    return;
  }

  const inicio = indiceInicio + 1;

  const fin =
    indiceInicio +
    registrosMostrados;

  contador.innerHTML = `
    Mostrando
    <strong>${inicio}</strong>
    a
    <strong>${fin}</strong>
    de
    <strong>${totalRegistros}</strong>
    registros
  `;
}

/*==================================================*
*=            BOTONES DE PAGINACIÓN                 =*
*==================================================*/

function actualizarPaginacion(totalPaginas) {
  const pie = obtenerPieTabla();

  if (!pie) {
    return;
  }

  const navegacion = pie.querySelector(
    ".tabla-paginacion",
  );

  if (!navegacion) {
    return;
  }

  /*
   * Reconstruimos la navegación para que
   * pueda tener tantas páginas como sea necesario.
   */
  navegacion.innerHTML = "";

  /*
   * Botón anterior.
   */
  const botonAnterior =
    document.createElement("button");

  botonAnterior.type = "button";

  botonAnterior.className =
    "tabla-paginacion__boton";

  botonAnterior.setAttribute(
    "aria-label",
    "Página anterior",
  );

  botonAnterior.textContent = "‹";

  botonAnterior.disabled =
    paginaActual <= 1;

  botonAnterior.addEventListener(
    "click",
    () => {
      if (paginaActual <= 1) {
        return;
      }

      paginaActual--;

      actualizarTabla();
    },
  );

  navegacion.appendChild(
    botonAnterior,
  );

  /*
   * Botones numéricos.
   */
  for (
    let numeroPagina = 1;
    numeroPagina <= totalPaginas;
    numeroPagina++
  ) {
    const botonPagina =
      document.createElement("button");

    botonPagina.type = "button";

    botonPagina.className =
      "tabla-paginacion__boton";

    botonPagina.textContent =
      String(numeroPagina);

    if (
      numeroPagina === paginaActual
    ) {
      botonPagina.classList.add(
        "tabla-paginacion__boton--activo",
      );

      botonPagina.setAttribute(
        "aria-current",
        "page",
      );
    }

    botonPagina.addEventListener(
      "click",
      () => {
        paginaActual = numeroPagina;

        actualizarTabla();
      },
    );

    navegacion.appendChild(
      botonPagina,
    );
  }

  /*
   * Botón siguiente.
   */
  const botonSiguiente =
    document.createElement("button");

  botonSiguiente.type = "button";

  botonSiguiente.className =
    "tabla-paginacion__boton";

  botonSiguiente.setAttribute(
    "aria-label",
    "Página siguiente",
  );

  botonSiguiente.textContent = "›";

  botonSiguiente.disabled =
    paginaActual >= totalPaginas;

  botonSiguiente.addEventListener(
    "click",
    () => {
      if (
        paginaActual >= totalPaginas
      ) {
        return;
      }

      paginaActual++;

      actualizarTabla();
    },
  );

  navegacion.appendChild(
    botonSiguiente,
  );
}

/*==================================================*
*=          VISIBILIDAD DEL PIE                     =*
*==================================================*/

function actualizarVisibilidadPie(
  totalRegistros,
) {
  const pie = obtenerPieTabla();

  if (!pie) {
    return;
  }

  pie.style.display =
    totalRegistros > 0
      ? ""
      : "none";
}

/*==================================================*
*=                  BUSCADOR                        =*
*==================================================*/

function inicializarBuscador() {
  const buscador = obtenerBuscador();

  if (!buscador) {
    return;
  }

  buscador.addEventListener(
    "input",
    () => {
      /*
       * Cada nueva búsqueda comienza
       * nuevamente desde la página 1.
       */
      paginaActual = 1;

      actualizarTabla();
    },
  );
}

/*==================================================*
*=              INICIALIZAR TABLA                   =*
*==================================================*/

export function inicializarTablaProyectos() {
  const tabla = obtenerTabla();

  if (!tabla) {
    return;
  }

  inicializarBuscador();

  paginaActual = 1;

  actualizarTabla();

  console.log(
    "Tabla de proyectos inicializada",
  );
}

/*==================================================*
*=              AGREGAR PROYECTO                    =*
*==================================================*/

export function agregarProyectoATabla(
  filaHtml,
) {
  const tabla = obtenerTabla();

  if (!tabla) {
    return;
  }

  const tbody = tabla.querySelector(
    "tbody",
  );

  if (!tbody) {
    return;
  }

  const filaVacia = tbody.querySelector(
    ".tabla-vacia",
  );

  if (filaVacia) {
    filaVacia.remove();
  }

  tbody.insertAdjacentHTML(
    "afterbegin",
    filaHtml,
  );

  /*
   * Al crear un proyecto nuevo,
   * regresamos a página 1 porque se
   * inserta al principio.
   */
  paginaActual = 1;

  actualizarTabla();
}

/*==================================================*
*=             ACTUALIZAR PROYECTO                  =*
*==================================================*/

export function actualizarProyectoEnTabla(
  idProyecto,
  filaHtml,
) {
  const filaActual = document.querySelector(
    `tr[data-proyecto-id="${idProyecto}"]`,
  );

  if (!filaActual) {
    return false;
  }

  const plantilla =
    document.createElement("template");

  plantilla.innerHTML =
    filaHtml.trim();

  const nuevaFila =
    plantilla.content.querySelector("tr");

  if (!nuevaFila) {
    return false;
  }

  filaActual.replaceWith(
    nuevaFila,
  );

  actualizarTabla();

  return true;
}

/*==================================================*
*=             REFRESCAR PAGINACIÓN                 =*
*==================================================*/

export function refrescarTablaProyectos() {
  actualizarTabla();
}