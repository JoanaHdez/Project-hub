/*==================================================*
*=              DATOS DE MÓDULOS                   =*
*==================================================*/

export function obtenerModulosPorSistema(
    idSistema,
) {
    const contenedor =
        document.getElementById(
            "datos-modulos",
        );

    if (!contenedor) {
        return [];
    }

    let modulos = [];

    try {
        modulos =
            JSON.parse(
                contenedor.textContent || "[]",
            );
    } catch (error) {
        console.error(
            "No fue posible leer los módulos:",
            error,
        );

        return [];
    }

    return modulos.filter(
        (modulo) =>
            String(
                modulo.id_sistema ?? "",
            ) === String(idSistema),
    );
}