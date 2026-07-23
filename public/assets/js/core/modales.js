console.log("modales.js cargado");
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

    if (!modal) {
      return;
    }

    modal.classList.add("modal--visible");
    modal.setAttribute("aria-hidden", "false");

    document.body.style.overflow = "hidden";
  }

  if (botonCerrar) {
    const modal = botonCerrar.closest(".modal");

    if (!modal) {
      return;
    }

    modal.classList.remove("modal--visible");
    modal.setAttribute("aria-hidden", "true");

    document.body.style.overflow = "";
  }
});

document.addEventListener("keydown", (event) => {
  if (event.key !== "Escape") {
    return;
  }

  const modalVisible = document.querySelector(".modal.modal--visible");

  if (!modalVisible) {
    return;
  }

  modalVisible.classList.remove("modal--visible");
  modalVisible.setAttribute("aria-hidden", "true");

  document.body.style.overflow = "";
});