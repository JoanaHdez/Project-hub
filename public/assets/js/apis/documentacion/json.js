export function obtenerJson(valor) {
  try {
    return JSON.parse(
      valor || "[]",
    );
  } catch (error) {
    console.error(
      "No fue posible interpretar el JSON.",
      error,
    );

    return [];
  }
}


export function resaltarJson(json) {
  json = JSON.stringify(
    json,
    null,
    2,
  );

  json = json.replace(
    /("(\\u[\da-fA-F]{4}|\\[^u]|[^\\"])*")(\s*:)?|\b(true|false|null)\b|-?\d+(\.\d+)?/g,
    function (match) {
      let clase = "json-numero";

      if (/^"/.test(match)) {
        if (/:$/.test(match)) {
          clase = "json-clave";
        } else {
          clase = "json-string";
        }
      } else if (
        /true|false/.test(match)
      ) {
        clase = "json-boolean";
      } else if (
        /null/.test(match)
      ) {
        clase = "json-null";
      }

      return `<span class="${clase}">${match}</span>`;
    },
  );

  return json;
}