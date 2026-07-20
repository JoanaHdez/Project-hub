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