/*==================================================*
*=            ACTUALIZAR FICHA TÉCNICA             =*
*==================================================*/

export function actualizarFichaTecnica({
  nombre = "",
  proyecto = "",
  metodo = "",
  estado = "",
  descripcion = "",
  autenticacion = "",
  endpoint = "",
  url = "",
  repositorio = "",
  ruta = "",
  servidor = "",
} = {}) {

    /*==================================================*
*=                 ENCABEZADO                      =*
*==================================================*/

actualizarTexto(
  "titulo-ficha-tecnica",
  nombre || "—",
);

actualizarTexto(
  "ficha-encabezado-proyecto",
  proyecto || "Sin proyecto",
);

actualizarTexto(
  "ficha-encabezado-metodo",
  metodo || "—",
);

actualizarTexto(
  "ficha-encabezado-estado-texto",
  estado || "—",
);

  /*==================================================*
  *=                 DATOS ACTUALES                  =*
  *==================================================*/

  actualizarTexto(
    "ficha-api-proyecto",
    proyecto || "—",
  );

  actualizarTexto(
    "ficha-api-estado",
    estado || "—",
  );

  actualizarTexto(
    "ficha-api-repositorio",
    repositorio || "—",
  );

  actualizarTexto(
    "ficha-api-ruta",
    ruta || "—",
  );

  actualizarTexto(
    "ficha-api-servidor",
    servidor || "—",
  );
}


/*==================================================*
*=                ACTUALIZAR TEXTO                  =*
*==================================================*/

function actualizarTexto(
  id,
  valor,
) {
  const elemento =
    document.getElementById(
      id,
    );

  if (!elemento) {
    return;
  }

  elemento.textContent =
    valor;
}
