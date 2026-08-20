/*==================================================*
*=              ACTIVIDAD RECIENTE                 =*
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


        /*==================================================*
        *=                  MODAL                           =*
        *==================================================*/

        const modal =
            document.getElementById(
                "modal-actividad-detalle",
            );

        if (!modal) {
            return;
        }


        /*==================================================*
        *=                  CAMPOS                         =*
        *==================================================*/

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


        /*==================================================*
        *=                ASIGNAR DATOS                    =*
        *==================================================*/

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


        /*==================================================*
        *=                  ABRIR                           =*
        *==================================================*/

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