import {
  resaltarJson,
} from "./json.js";


export function renderEjemplo(ejemplo) {
  const contenedor =
    document.getElementById(
      "api-ejemplo-contenedor",
    );

  const codigo =
    document.getElementById(
      "api-ejemplo",
    );

  const vacio =
    document.getElementById(
      "api-ejemplo-vacio",
    );

  const metodoElemento =
    document.getElementById(
      "api-ejemplo-metodo",
    );

  if (
    !contenedor ||
    !codigo ||
    !vacio ||
    !metodoElemento
  ) {
    return;
  }

  codigo.textContent = "";

  if (
    !ejemplo ||
    Array.isArray(ejemplo) ||
    Object.keys(ejemplo).length === 0
  ) {
    contenedor.hidden = true;
    vacio.hidden = false;

    vacio.textContent =
      "Esta API no tiene un ejemplo documentado.";

    return;
  }

  const metodo =
    ejemplo.metodo ?? "POST";

  const url =
    ejemplo.url ??
    ejemplo.endpoint ??
    "";

  const body =
    ejemplo.body ?? {};

  metodoElemento.textContent =
    metodo;

  metodoElemento.className =
    `badge-metodo badge-metodo--${metodo.toLowerCase()}`;

  codigo.innerHTML =
    `${url}

${resaltarJson(body)}`;

  contenedor.hidden = false;
  vacio.hidden = true;
}


export function inicializarCopiarEjemplo() {
  const boton =
    document.getElementById(
      "copiar-ejemplo",
    );

  const codigo =
    document.getElementById(
      "api-ejemplo",
    );

  if (!boton || !codigo) {
    return;
  }

  boton.addEventListener(
    "click",
    async () => {
      try {
        await navigator.clipboard.writeText(
          codigo.textContent,
        );

        boton.textContent =
          "¡Copiado!";

        window.setTimeout(
          () => {
            boton.textContent =
              "Copiar";
          },
          1500,
        );
      } catch (error) {
        console.error(
          "No fue posible copiar el ejemplo:",
          error,
        );
      }
    },
  );
}