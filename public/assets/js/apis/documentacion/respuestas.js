import {
  resaltarJson,
} from "./json.js";


function obtenerClaseRespuesta(
  codigo,
) {
  if (
    codigo >= 200 &&
    codigo < 300
  ) {
    return "respuesta-http respuesta-http--success";
  }

  if (
    codigo >= 300 &&
    codigo < 400
  ) {
    return "respuesta-http respuesta-http--redirect";
  }

  if (
    codigo >= 400 &&
    codigo < 500
  ) {
    return "respuesta-http respuesta-http--warning";
  }

  return "respuesta-http respuesta-http--error";
}


export function renderRespuestas(
  respuestas,
) {
  const contenedor =
    document.getElementById(
      "api-respuestas-contenedor",
    );

  const lista =
    document.getElementById(
      "api-respuestas",
    );

  const vacio =
    document.getElementById(
      "api-respuestas-vacio",
    );

  if (
    !contenedor ||
    !lista ||
    !vacio
  ) {
    return;
  }

  lista.innerHTML = "";

  if (
    !Array.isArray(respuestas) ||
    respuestas.length === 0
  ) {
    contenedor.hidden = true;
    vacio.hidden = false;

    vacio.textContent =
      "Esta API no tiene respuestas documentadas.";

    return;
  }

  respuestas.forEach((respuesta) => {
    const tarjeta =
      document.createElement("div");

    tarjeta.className =
      "respuesta-api";

    const clase =
      obtenerClaseRespuesta(
        respuesta.codigo,
      );

    tarjeta.innerHTML = `
      <div class="respuesta-api__encabezado">

        <span class="${clase}">
          ${respuesta.codigo}
        </span>

        <strong>
          ${respuesta.descripcion ?? ""}
        </strong>

      </div>

      <pre class="codigo-api"><code>${resaltarJson(
        respuesta.body ?? {},
      )}</code></pre>
    `;

    lista.appendChild(tarjeta);
  });

  contenedor.hidden = false;
  vacio.hidden = true;
}