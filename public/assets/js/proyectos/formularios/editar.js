export function inicializarFormularioEditar() {
  const formulario = document.getElementById("form-editar-proyecto");

  if (!formulario) {
    return;
  }

  formulario.addEventListener("submit", (event) => {
    event.preventDefault();

    console.log("Formulario de edición interceptado correctamente");
  });
}