/*==================================================*
*=                IMAGEN DEL MÓDULO                =*
*==================================================*/

export function inicializarImagenModulo() {

    const modal =
        document.getElementById(
            "modal-nuevo-modulo",
        );

    if (!modal) {
        return;
    }

    const botonSeleccionar =
        modal.querySelector(
            "[data-modulo-imagen-seleccionar]",
        );

    const inputImagen =
        modal.querySelector(
            "[data-modulo-imagen-input]",
        );

    const previewImagen =
        modal.querySelector(
            "[data-modulo-imagen-preview]",
        );

    const imagenVacia =
        modal.querySelector(
            "[data-modulo-imagen-vacia]",
        );

    if (
        !botonSeleccionar ||
        !inputImagen ||
        !previewImagen ||
        !imagenVacia
    ) {
        return;
    }


    /*==================================================*
    *=          ABRIR EXPLORADOR DE ARCHIVOS          =*
    *==================================================*/

    botonSeleccionar.addEventListener(
        "click",
        () => {

            const formulario =
                document.getElementById(
                    "form-nuevo-modulo",
                );

            if (
                formulario?.dataset.modo !==
                "editar"
            ) {
                return;
            }

            inputImagen.click();
        },
    );


    /*==================================================*
    *=              SELECCIONAR IMAGEN                =*
    *==================================================*/

    inputImagen.addEventListener(
        "change",
        () => {

            const archivo =
                inputImagen.files?.[0];

            if (!archivo) {
                return;
            }


            /*==================================================*
            *=              VALIDAR FORMATO                    =*
            *==================================================*/

            const formatosPermitidos = [
                "image/jpeg",
                "image/png",
                "image/webp",
            ];

            if (
                !formatosPermitidos.includes(
                    archivo.type,
                )
            ) {
                inputImagen.value = "";

                return;
            }


            /*==================================================*
            *=              VISTA PREVIA LOCAL                 =*
            *==================================================*/

            const urlTemporal =
                URL.createObjectURL(
                    archivo,
                );

            const urlAnterior =
                previewImagen.dataset
                    .urlTemporal ?? "";

            if (urlAnterior) {
                URL.revokeObjectURL(
                    urlAnterior,
                );
            }

            previewImagen.dataset.urlTemporal =
                urlTemporal;

            previewImagen.src =
                urlTemporal;

            previewImagen.hidden =
                false;

            imagenVacia.hidden =
                true;
        },
    );
}