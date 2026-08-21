/*==================================================
=                     MODALES                       =
==================================================*/

document.addEventListener("click", (event) => {
  const botonAbrir = event.target.closest("[data-modal-abrir]");
  const botonCerrar = event.target.closest("[data-modal-cerrar]");

  if (botonAbrir) {
    event.preventDefault();

    if (botonAbrir.disabled) {
      return;
    }

    const modalId = botonAbrir.dataset.modalAbrir;
    const modal = document.getElementById(modalId);

    if (modal) {
      modal.classList.add("modal--visible");
      modal.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";
    }
  }

  if (botonCerrar) {
    const modal = botonCerrar.closest(".modal");

    if (modal) {
      modal.classList.remove("modal--visible");
      modal.setAttribute("aria-hidden", "true");
      document.body.style.overflow = "";
    }
  }
});

document.addEventListener("keydown", (event) => {
  if (event.key !== "Escape") {
    return;
  }

  const modalVisible = document.querySelector(".modal.modal--visible");

  if (modalVisible) {
    modalVisible.classList.remove("modal--visible");
    modalVisible.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }
});

/*==================================================
=              BÚSQUEDA EN TABLAS                  =
==================================================*/

document.addEventListener("input", (event) => {
  const buscador = event.target.closest("[data-tabla-busqueda]");

  if (!buscador) {
    return;
  }

  const tablaId = buscador.dataset.tablaBusqueda;
  const tabla = document.getElementById(tablaId);

  if (!tabla) {
    return;
  }

  const termino = normalizarTexto(buscador.value);
  const filas = tabla.querySelectorAll("tbody tr");

  filas.forEach((fila) => {
    if (fila.querySelector(".tabla-vacia")) {
      return;
    }

    const contenido = normalizarTexto(fila.textContent);

    fila.hidden = !contenido.includes(termino);
  });
});

/*==================================================*
*=              FORMULARIOS DE APIs                 =*
*==================================================*/

if (document.getElementById("form-nueva-api")) {
  import("./apis/formulario.js")
    .then(({ inicializarFormularioApi }) => {
      inicializarFormularioApi();
    })
    .catch((error) => {
      console.error(
        "No fue posible inicializar los formularios de APIs:",
        error,
      );
    });
}


/*==================================================
=                  MENÚ USUARIO                    =
==================================================*/

const botonMenuUsuario =
    document.getElementById(
        'btn-menu-usuario'
    );

const menuUsuario =
    document.getElementById(
        'menu-usuario'
    );


if (
    botonMenuUsuario &&
    menuUsuario
) {

    botonMenuUsuario.addEventListener(
        'click',
        (event) => {

            event.stopPropagation();

            const abierto =
                botonMenuUsuario
                .getAttribute(
                    'aria-expanded'
                ) === 'true';

            botonMenuUsuario
                .setAttribute(
                    'aria-expanded',
                    String(!abierto)
                );

            menuUsuario.hidden =
                abierto;
        }
    );


    document.addEventListener(
        'click',
        (event) => {

            if (
                menuUsuario.hidden
            ) {
                return;
            }

            if (
                menuUsuario.contains(
                    event.target
                ) ||
                botonMenuUsuario.contains(
                    event.target
                )
            ) {
                return;
            }

            menuUsuario.hidden =
                true;

            botonMenuUsuario
                .setAttribute(
                    'aria-expanded',
                    'false'
                );
        }
    );


    document.addEventListener(
        'keydown',
        (event) => {

            if (
                event.key !== 'Escape'
            ) {
                return;
            }

            menuUsuario.hidden =
                true;

            botonMenuUsuario
                .setAttribute(
                    'aria-expanded',
                    'false'
                );
        }
    );
}








