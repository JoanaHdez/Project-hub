export function inicializarTablaProyectos() {
  console.log("Tabla de proyectos inicializada");
}

export function agregarProyectoATabla(filaHtml) {
  const tabla = document.getElementById("tabla-proyectos");

  if (!tabla) {
    return;
  }

  const tbody = tabla.querySelector("tbody");

  if (!tbody) {
    return;
  }

  const filaVacia = tbody.querySelector(".tabla-vacia");

  if (filaVacia) {
    filaVacia.remove();
  }

  tbody.insertAdjacentHTML("afterbegin", filaHtml);
}