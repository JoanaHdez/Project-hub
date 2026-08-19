/*==================================================*
*=              GUARDAR NUEVO MÓDULO              =*
*==================================================*/

export async function guardarNuevoModulo(
    datosModulo,
) {
    const respuesta =
        await fetch(
            "/modulos",
            {
                method: "POST",

                headers: {
                    "Content-Type":
                        "application/json",

                    "X-Requested-With":
                        "XMLHttpRequest",
                },

                body:
                    JSON.stringify(
                        datosModulo,
                    ),
            },
        );


    /*==================================================*
    *=                LEER RESPUESTA                   =*
    *==================================================*/

    let resultado;

    try {
        resultado =
            await respuesta.json();
    } catch {
        throw new Error(
            "El servidor devolvió una respuesta no válida.",
        );
    }


    /*==================================================*
    *=                VALIDAR RESPUESTA                =*
    *==================================================*/

    if (!respuesta.ok) {
        throw new Error(
            resultado.mensaje ||
            "No fue posible guardar el módulo.",
        );
    }

    return resultado;
}