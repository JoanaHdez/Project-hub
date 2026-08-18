/*==================================================*
*=              BUSCADOR DE APIs                   =*
*==================================================*/

export function inicializarBuscadorApis() {
    const buscador =
        document.getElementById(
            "buscar-api",
        );

    const catalogo =
        document.querySelector(
            ".catalogo__lista",
        );

    if (
        !buscador ||
        !catalogo
    ) {
        return;
    }


    /*==================================================*
    *=              FILTRAR APIs                       =*
    *==================================================*/

    buscador.addEventListener(
        "input",
        () => {
            const termino =
                normalizarTexto(
                    buscador.value,
                );

            const selectores =
                catalogo.querySelectorAll(
                    ".api-selector",
                );

            selectores.forEach(
                (selector) => {
                    const nombre =
                        normalizarTexto(
                            selector.dataset.apiNombre ?? "",
                        );

                    const proyecto =
                        normalizarTexto(
                            selector.dataset.apiProyecto ?? "",
                        );

                    const metodo =
                        normalizarTexto(
                            selector.dataset.apiMetodo ?? "",
                        );

                    const estado =
                        normalizarTexto(
                            selector.dataset.apiEstado ?? "",
                        );

                    const coincide =
                        termino === "" ||
                        nombre.includes(termino) ||
                        proyecto.includes(termino) ||
                        metodo.includes(termino) ||
                        estado.includes(termino);

                    selector.style.display =
                        coincide
                            ? ""
                            : "none";
                },
            );
        },
    );
}


/*==================================================*
*=              NORMALIZAR TEXTO                    =*
*==================================================*/

function normalizarTexto(
    texto,
) {
    return String(
        texto ?? "",
    )
        .trim()
        .toLowerCase()
        .normalize("NFD")
        .replace(
            /[\u0300-\u036f]/g,
            "",
        );
}