/*==================================================*
*=              HISTORIAL ACTIVIDAD                =*
*==================================================*/

const campoBuscar =
    document.getElementById(
        "actividad-buscar",
    );

const campoBloque =
    document.getElementById(
        "actividad-bloque",
    );

const campoAccion =
    document.getElementById(
        "actividad-accion",
    );

const campoFecha =
    document.getElementById(
        "actividad-fecha",
    );

const botonLimpiar =
    document.querySelector(
        "[data-limpiar-filtros-actividad]",
    );

const filas =
    Array.from(
        document.querySelectorAll(
            "[data-actividad-fila]",
        ),
    );

const estadoSinResultados =
    document.querySelector(
        "[data-actividad-sin-resultados]",
    );


/*==================================================*
*=                   FILTRAR                       =*
*==================================================*/

function filtrarActividades() {

    const busqueda =
        (
            campoBuscar?.value
            ?? ""
        )
            .trim()
            .toLowerCase();

    const bloque =
        (
            campoBloque?.value
            ?? ""
        )
            .trim()
            .toLowerCase();

    const accion =
        (
            campoAccion?.value
            ?? ""
        )
            .trim()
            .toLowerCase();

    const fecha =
        campoFecha?.value
        ?? "";

    let visibles =
        0;


    filas.forEach(
        (fila) => {

            const coincideBusqueda =
                !busqueda ||
                (
                    fila.dataset.texto
                    ?? ""
                ).includes(
                    busqueda,
                );

            const coincideBloque =
                !bloque ||
                (
                    fila.dataset.bloque
                    ?? ""
                ) === bloque;

            const coincideAccion =
                !accion ||
                (
                    fila.dataset.accion
                    ?? ""
                ) === accion;

            const coincideFecha =
                !fecha ||
                (
                    fila.dataset.fecha
                    ?? ""
                ) === fecha;


            const mostrar =
                coincideBusqueda &&
                coincideBloque &&
                coincideAccion &&
                coincideFecha;


            fila.hidden =
                !mostrar;

            if (mostrar) {
                visibles++;
            }
        },
    );


    if (estadoSinResultados) {
        estadoSinResultados.hidden =
            visibles !== 0;
    }
}


/*==================================================*
*=                  EVENTOS                        =*
*==================================================*/

campoBuscar?.addEventListener(
    "input",
    filtrarActividades,
);

campoBloque?.addEventListener(
    "change",
    filtrarActividades,
);

campoAccion?.addEventListener(
    "change",
    filtrarActividades,
);

campoFecha?.addEventListener(
    "change",
    filtrarActividades,
);


/*==================================================*
*=              LIMPIAR FILTROS                    =*
*==================================================*/

botonLimpiar?.addEventListener(
    "click",
    () => {

        if (campoBuscar) {
            campoBuscar.value =
                "";
        }

        if (campoBloque) {
            campoBloque.value =
                "";
        }

        if (campoAccion) {
            campoAccion.value =
                "";
        }

        if (campoFecha) {
            campoFecha.value =
                "";
        }

        filtrarActividades();
    },
);


/*==================================================*
*=              DETALLE ACTIVIDAD                  =*
*==================================================*/

document.addEventListener(
    "click",
    (evento) => {

        const boton =
            evento.target.closest(
                "[data-actividad-detalle]",
            );

        if (!boton) {
            return;
        }


        const modal =
            document.getElementById(
                "modal-actividad-detalle",
            );

        if (!modal) {
            return;
        }


        const usuario =
            modal.querySelector(
                "[data-actividad-modal-usuario]",
            );

        const bloque =
            modal.querySelector(
                "[data-actividad-modal-bloque]",
            );

        const accion =
            modal.querySelector(
                "[data-actividad-modal-accion]",
            );

        const fecha =
            modal.querySelector(
                "[data-actividad-modal-fecha]",
            );

        const detalle =
            modal.querySelector(
                "[data-actividad-modal-detalle]",
            );


        if (usuario) {
            usuario.textContent =
                boton.dataset.actividadUsuario
                ?? "—";
        }

        if (bloque) {
            bloque.textContent =
                boton.dataset.actividadBloque
                ?? "—";
        }

        if (accion) {
            accion.textContent =
                boton.dataset.actividadAccion
                ?? "—";
        }

        if (fecha) {
            fecha.textContent =
                boton.dataset.actividadFecha
                ?? "—";
        }

        if (detalle) {
            detalle.textContent =
                boton.dataset.actividadDetalleTexto
                ?? "—";
        }


        modal.classList.add(
            "modal--visible",
        );

        modal.setAttribute(
            "aria-hidden",
            "false",
        );

        document.body.style.overflow =
            "hidden";
    },
);