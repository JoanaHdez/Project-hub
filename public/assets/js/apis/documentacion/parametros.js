export function renderParametros(
  parametros,
) {
  const contenedor =
    document.getElementById(
      "api-parametros-contenedor",
    );

  const body =
    document.getElementById(
      "api-parametros",
    );

  const vacio =
    document.getElementById(
      "api-parametros-vacio",
    );

  if (!contenedor || !body || !vacio) {
    return;
  }

  body.innerHTML = "";

  if (
    !Array.isArray(parametros) ||
    parametros.length === 0
  ) {
    contenedor.hidden = true;
    vacio.hidden = false;

    vacio.textContent =
      "Esta API no tiene parámetros documentados.";

    return;
  }

  parametros.forEach((parametro) => {
    const fila =
      document.createElement("tr");

    fila.innerHTML = `
      <td>${parametro.nombre ?? "—"}</td>
      <td>${parametro.tipo ?? "—"}</td>
      <td>${parametro.obligatorio ? "Sí" : "No"}</td>
      <td>${parametro.descripcion ?? "—"}</td>
    `;

    body.appendChild(fila);
  });

  contenedor.hidden = false;
  vacio.hidden = true;
}