/*==================================================*
*=                 VALIDACIÓN JSON                  =*
*==================================================*/

export function validarCampoJson(campo) {
  if (!campo) {
    return true;
  }

  const valor = campo.value.trim();

  let mensajeError = campo
    .closest(".form-grupo")
    ?.querySelector(
      "[data-error-json]",
    );

  if (!valor) {
    campo.dataset.jsonValido = "true";

    if (mensajeError) {
      mensajeError.remove();
    }

    return true;
  }

  try {
    JSON.parse(valor);

    campo.dataset.jsonValido = "true";

    if (mensajeError) {
      mensajeError.remove();
    }

    return true;
  } catch (error) {
    campo.dataset.jsonValido = "false";

    if (!mensajeError) {
      mensajeError =
        document.createElement("small");

      mensajeError.dataset.errorJson = "";
      mensajeError.className = "campo-error";

      campo.insertAdjacentElement(
        "afterend",
        mensajeError,
      );
    }

    mensajeError.textContent =
      "El contenido debe ser un JSON válido.";

    return false;
  }
}