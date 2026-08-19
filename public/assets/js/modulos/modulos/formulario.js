/*==================================================*
*=          FORMULARIO NUEVO MÓDULO                =*
*==================================================*/

export function obtenerDatosNuevoModulo(
    formulario,
) {
    if (!formulario) {
        return null;
    }

    const datosFormulario =
        new FormData(
            formulario,
        );

    const idSistema =
        formulario.dataset.sistemaId ?? "";

    if (!idSistema) {
        return null;
    }

    return {
        id_sistema:
            Number(idSistema),

        tipo:
            String(
                datosFormulario.get(
                    "tipo",
                ) ?? "",
            ).trim(),

        nombre:
            String(
                datosFormulario.get(
                    "nombre",
                ) ?? "",
            ).trim(),

        descripcion:
            String(
                datosFormulario.get(
                    "descripcion",
                ) ?? "",
            ).trim(),

        url:
            String(
                datosFormulario.get(
                    "url",
                ) ?? "",
            ).trim(),
    };
}