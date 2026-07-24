export function mostrarNotificacion({
  tipo = "info",
  titulo = "",
  mensaje = "",
  duracion = 3000,
}) {
  const toastAnterior = document.querySelector(".toast");

  if (toastAnterior) {
    toastAnterior.remove();
  }

  const toast = document.createElement("div");

  toast.className = `toast toast--${tipo}`;
  toast.setAttribute("role", "status");
  toast.setAttribute("aria-live", "polite");

  toast.innerHTML = `
    <div class="toast__contenido">
      ${
        titulo
          ? `<span class="toast__titulo">${titulo}</span>`
          : ""
      }

      <span class="toast__mensaje">${mensaje}</span>
    </div>
  `;

  document.body.appendChild(toast);

  requestAnimationFrame(() => {
    toast.classList.add("toast--visible");
  });

  window.setTimeout(() => {
    toast.classList.remove("toast--visible");

    window.setTimeout(() => {
      toast.remove();
    }, 250);
  }, duracion);
}