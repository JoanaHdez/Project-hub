export function inicializarFormularioEliminar() {
  const formulario = document.getElementById("form-eliminar-proyecto");

  if (!formulario) {
    return;
  }

  formulario.addEventListener("submit", (event) => {
    event.preventDefault();

    console.log("Formulario de eliminación interceptado correctamente");
  });
}