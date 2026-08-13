export function renderHeaders(headers) {
  const contenedor =
    document.getElementById(
      "api-headers-contenedor",
    );

  const body =
    document.getElementById(
      "api-headers",
    );

  const vacio =
    document.getElementById(
      "api-headers-vacio",
    );

  if (!contenedor || !body || !vacio) {
    return;
  }

  body.innerHTML = "";

  if (
    !Array.isArray(headers) ||
    headers.length === 0
  ) {
    contenedor.hidden = true;
    vacio.hidden = false;

    vacio.textContent =
      "Esta API no tiene headers documentados.";

    return;
  }

  headers.forEach((header) => {
    const fila =
      document.createElement("tr");

    fila.innerHTML = `
      <td>${header.nombre ?? "—"}</td>
      <td>${header.valor ?? "—"}</td>
      <td>${header.obligatorio ? "Sí" : "No"}</td>
      <td>${header.descripcion ?? "—"}</td>
    `;

    body.appendChild(fila);
  });

  contenedor.hidden = false;
  vacio.hidden = true;
}