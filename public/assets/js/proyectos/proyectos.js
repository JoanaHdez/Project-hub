document.addEventListener("DOMContentLoaded", () => {
  const formulario = document.getElementById("form-proyecto");

  if (!formulario) {
    return;
  }

  formulario.addEventListener("submit", (event) => {
    event.preventDefault();

    if (!formulario.checkValidity()) {
      formulario.reportValidity();
      return;
    }

    const datosFormulario = new FormData(formulario);

    const proyecto = {
      nombre: String(datosFormulario.get("nombre") ?? "").trim(),
      estado: String(datosFormulario.get("estado") ?? ""),
      origen: String(datosFormulario.get("origen") ?? ""),
      descripcion: String(datosFormulario.get("descripcion") ?? "").trim(),
      repositorio_url: String(
        datosFormulario.get("repositorio_url") ?? ""
      ).trim(),
      ruta_local: String(datosFormulario.get("ruta_local") ?? "").trim(),
      url_servidor: String(
        datosFormulario.get("url_servidor") ?? ""
      ).trim(),
      id_especificacion: String(
        datosFormulario.get("id_especificacion") ?? ""
      ),
      responsable: String(
        datosFormulario.get("responsable") ?? ""
      ).trim(),
      observaciones: String(
        datosFormulario.get("observaciones") ?? ""
      ).trim(),
    };

    console.log("Datos originales del formulario:");
    console.table(Object.fromEntries(datosFormulario.entries()));

    console.log("Proyecto capturado:");
    console.table(proyecto);
  });
});