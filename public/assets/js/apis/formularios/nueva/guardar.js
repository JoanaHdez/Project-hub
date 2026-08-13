/*==================================================*
*=                GUARDAR NUEVA API                 =*
*==================================================*/

export async function guardarNuevaApi(datosApi) {
  try {
    const respuesta = await fetch("/apis", {
      method: "POST",

      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },

      body: JSON.stringify(datosApi),
    });

    const resultado = await respuesta.json();

    if (!respuesta.ok || !resultado.ok) {
      throw new Error(
        resultado.mensaje ||
          "No fue posible registrar la API.",
      );
    }

    return resultado;
  } catch (error) {
    console.error(
      "Error al guardar la API:",
      error,
    );

    throw error;
  }
}