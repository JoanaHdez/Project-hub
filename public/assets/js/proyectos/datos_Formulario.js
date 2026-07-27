export function obtenerDatosFormulario(formulario) {
  const datosFormulario = new FormData(formulario);

  return {
    nombre: String(datosFormulario.get("nombre") ?? "").trim(),
    estado: String(datosFormulario.get("estado") ?? ""),
    origen: String(datosFormulario.get("origen") ?? ""),
    descripcion: String(datosFormulario.get("descripcion") ?? "").trim(),

    repositorio_url: String(
      datosFormulario.get("repositorio_url") ?? "",
    ).trim(),

    ruta_local: String(
      datosFormulario.get("ruta_local") ?? "",
    ).trim(),

    url_servidor: String(
      datosFormulario.get("url_servidor") ?? "",
    ).trim(),

    id_especificacion: String(
      datosFormulario.get("id_especificacion") ?? "",
    ),

    responsable: String(
      datosFormulario.get("responsable") ?? "",
    ).trim(),

    observaciones: String(
      datosFormulario.get("observaciones") ?? "",
    ).trim(),
  };
}