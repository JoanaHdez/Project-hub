document.addEventListener("DOMContentLoaded", () => {
  const botonAbrir = document.getElementById("abrir-ficha-tecnica");
  const modal = document.getElementById("modal-ficha-tecnica");
  const botonCerrar = document.getElementById("cerrar-ficha-tecnica");

  const fondo = modal?.querySelector(".modal-ficha__fondo");

  const botonesNavegacion = modal?.querySelectorAll(
    "[data-ficha-seccion]",
  ) ?? [];

  const secciones = modal?.querySelectorAll(
    "[data-ficha-contenido]",
  ) ?? [];

  if (!modal) {
    return;
  }

  function abrirFichaTecnica() {
    modal.classList.add("modal-ficha--visible");
    modal.setAttribute("aria-hidden", "false");

    document.body.style.overflow = "hidden";
  }

  function cerrarFichaTecnica() {
    modal.classList.remove("modal-ficha--visible");
    modal.setAttribute("aria-hidden", "true");

    document.body.style.overflow = "";
  }

  function cambiarSeccionFicha(nombreSeccion) {
    if (!nombreSeccion) {
      return;
    }

    botonesNavegacion.forEach((boton) => {
      const esActivo =
        boton.dataset.fichaSeccion === nombreSeccion;

      boton.classList.toggle(
        "ficha-nav__item--activo",
        esActivo,
      );

      boton.setAttribute(
        "aria-selected",
        esActivo ? "true" : "false",
      );
    });

    secciones.forEach((seccion) => {
      const esActiva =
        seccion.dataset.fichaContenido === nombreSeccion;

      seccion.classList.toggle(
        "ficha-seccion--activa",
        esActiva,
      );
    });
  }

  botonAbrir?.addEventListener("click", abrirFichaTecnica);

  botonCerrar?.addEventListener("click", cerrarFichaTecnica);

  fondo?.addEventListener("click", cerrarFichaTecnica);

  botonesNavegacion.forEach((boton) => {
    boton.addEventListener("click", () => {
      cambiarSeccionFicha(
        boton.dataset.fichaSeccion,
      );
    });
  });

  document.addEventListener("keydown", (event) => {
    if (
      event.key === "Escape" &&
      modal.classList.contains("modal-ficha--visible")
    ) {
      cerrarFichaTecnica();
    }
  });
});