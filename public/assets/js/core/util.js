/**
 * Normaliza texto para búsquedas:
 * - elimina espacios laterales;
 * - convierte a minúsculas;
 * - elimina acentos.
 */
function normalizarTexto(texto) {
  return String(texto ?? "")
    .trim()
    .toLowerCase()
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "");
}

/**
 * Convierte una cadena JSON en un valor de JavaScript.
 */
function obtenerJson(valor, valorPredeterminado = []) {
  try {
    return JSON.parse(valor || JSON.stringify(valorPredeterminado));
  } catch (error) {
    console.error("No fue posible interpretar el JSON.", error);

    return valorPredeterminado;
  }
}