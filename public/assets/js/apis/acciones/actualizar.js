/*==================================================
=                ACTUALIZAR API                     =
==================================================*/

export async function actualizarApi(
  idApi,
  datosApi,
) {
  const respuesta = await fetch(
    `/apis/${idApi}`,
    {
      method: "PUT",

      headers: {
        "Content-Type":
          "application/json",

        "X-Requested-With":
          "XMLHttpRequest",
      },

      body: JSON.stringify(
        datosApi,
      ),
    },
  );

  const resultado =
    await respuesta.json();

  if (
    !respuesta.ok ||
    !resultado.ok
  ) {
    throw new Error(
      resultado.mensaje ||
        "No fue posible actualizar la API.",
    );
  }

  return resultado;
}