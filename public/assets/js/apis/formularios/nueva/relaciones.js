/*==================================================*
*=          PROYECTO → SISTEMAS ASOCIADOS           =*
*==================================================*/

export function inicializarRelacionProyectoSistema({
  proyectoId = "nueva-api-proyecto",
  sistemaId = "nueva-api-sistema",
} = {}) {
  const selectProyecto =
    document.getElementById(
      proyectoId,
    );

  const selectSistema =
    document.getElementById(
      sistemaId,
    );

  if (!selectProyecto || !selectSistema) {
    return;
  }

  selectProyecto.addEventListener(
    "change",
    async () => {
      await cargarSistemasPorProyecto({
        selectProyecto,
        selectSistema,
      });
    },
  );
}


/*==================================================*
*=           CARGAR SISTEMAS DEL PROYECTO           =*
*==================================================*/

export async function cargarSistemasPorProyecto({
  selectProyecto,
  selectSistema,
  idSistemaSeleccionado = null,
}) {
  if (!selectProyecto || !selectSistema) {
    return;
  }

  const idProyecto =
    selectProyecto.value;

  selectSistema.innerHTML = `
    <option value="">
      Sin sistema asociado
    </option>
  `;

  if (!idProyecto) {
    selectSistema.disabled = false;
    return;
  }

  selectSistema.disabled = true;

  try {
    const respuesta = await fetch(
      `/proyectos/${idProyecto}/sistemas`,
      {
        method: "GET",
        headers: {
          "X-Requested-With":
            "XMLHttpRequest",
        },
      },
    );

    const resultado =
      await respuesta.json();

    if (
      !respuesta.ok ||
      !resultado.ok
    ) {
      throw new Error(
        resultado.mensaje ||
          "No fue posible obtener los sistemas.",
      );
    }

    const sistemas =
      Array.isArray(
        resultado.sistemas,
      )
        ? resultado.sistemas
        : [];

    sistemas.forEach((sistema) => {
      const opcion =
        document.createElement(
          "option",
        );

      opcion.value =
        sistema.id_sistema ?? "";

      opcion.textContent =
        sistema.nombre ??
        "Sistema sin nombre";

      selectSistema.appendChild(
        opcion,
      );
    });

    if (
      idSistemaSeleccionado !== null &&
      idSistemaSeleccionado !== ""
    ) {
      selectSistema.value =
        String(
          idSistemaSeleccionado,
        );
    }
  } catch (error) {
    console.error(
      "Error al cargar los sistemas del proyecto:",
      error,
    );
  } finally {
    selectSistema.disabled = false;
  }
}