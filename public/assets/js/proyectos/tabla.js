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

export function actualizarProyectoEnTabla(idProyecto, filaHtml) {
  console.log("ID recibido:", idProyecto);
  console.log("HTML recibido:", filaHtml);

  const filaActual = document.querySelector(
    `tr[data-proyecto-id="${idProyecto}"]`,
  );

  console.log("Fila encontrada:", filaActual);

  if (!filaActual) {
    return false;
  }

  const plantilla = document.createElement("template");
  plantilla.innerHTML = filaHtml.trim();

  const nuevaFila = plantilla.content.querySelector("tr");

  console.log("Nueva fila:", nuevaFila);

  if (!nuevaFila) {
    return false;
  }

  filaActual.replaceWith(nuevaFila);

  console.log("Fila reemplazada");

  return true;
}