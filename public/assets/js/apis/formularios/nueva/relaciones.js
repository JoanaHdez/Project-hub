/*==================================================*
*=          PROYECTO → SISTEMAS ASOCIADOS           =*
*==================================================*/

export function inicializarRelacionProyectoSistema() {
  const selectProyecto = document.getElementById(
    "nueva-api-proyecto",
  );

  const selectSistema = document.getElementById(
    "nueva-api-sistema",
  );

  if (!selectProyecto || !selectSistema) {
    return;
  }

  selectProyecto.addEventListener(
    "change",
    async () => {
      const idProyecto = selectProyecto.value;

      selectSistema.innerHTML = `
        <option value="">
          Sin sistema asociado
        </option>
      `;

      if (!idProyecto) {
        return;
      }

      selectSistema.disabled = true;

      try {
        const respuesta = await fetch(
          `/proyectos/${idProyecto}/sistemas`,
          {
            method: "GET",
            headers: {
              "X-Requested-With": "XMLHttpRequest",
            },
          },
        );

        const resultado = await respuesta.json();

        if (!respuesta.ok || !resultado.ok) {
          throw new Error(
            resultado.mensaje ||
              "No fue posible obtener los sistemas.",
          );
        }

        const sistemas = Array.isArray(
          resultado.sistemas,
        )
          ? resultado.sistemas
          : [];

        sistemas.forEach((sistema) => {
          const opcion =
            document.createElement("option");

          opcion.value =
            sistema.id_sistema ?? "";

          opcion.textContent =
            sistema.nombre ??
            "Sistema sin nombre";

          selectSistema.appendChild(opcion);
        });
      } catch (error) {
        console.error(
          "Error al cargar los sistemas del proyecto:",
          error,
        );
      } finally {
        selectSistema.disabled = false;
      }
    },
  );
}