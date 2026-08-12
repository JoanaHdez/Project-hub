export function inicializarFormularioNuevaApi() {
  const formulario = document.getElementById(
    "form-nueva-api",
  );

  if (!formulario) {
    return;
  }

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

      /*
       * Reiniciar siempre el select
       * de sistemas.
       */
      selectSistema.innerHTML = `
        <option value="">
          Sin sistema asociado
        </option>
      `;

      /*
       * Si no existe proyecto seleccionado,
       * no consultamos sistemas.
       */
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

        /*
         * Si el proyecto no tiene sistemas,
         * mantenemos únicamente la opción
         * "Sin sistema asociado".
         */
        if (sistemas.length === 0) {
          return;
        }

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