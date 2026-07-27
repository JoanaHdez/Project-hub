export function validarFormulario(formulario) {
  if (!formulario) {
    return false;
  }

  if (!formulario.checkValidity()) {
    formulario.reportValidity();
    return false;
  }

  return true;
} 