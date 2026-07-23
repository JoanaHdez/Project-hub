<?php

$proyectos = $proyectos ?? [];

$columnas = [
    'Proyecto',
    'Estado',
    'Origen',
    'Sistemas registrados',
    'Fecha de creación',
    'Acciones',
];

$filas = [];

foreach ($proyectos as $proyecto) {
    $idProyecto = (int) ($proyecto['id_proyecto'] ?? 0);

    $estado = view('components/ui/estado', [
        'texto' => $proyecto['estado'] ?? 'Sin estado',
        'tipo'  => $proyecto['estado_tipo'] ?? 'inactivo',
    ]);

    $acciones = '<div class="acciones-proyecto">';

    /*
     * Ver detalle:
     * por ahora utiliza la ficha técnica existente.
     */
    $acciones .= view('components/ui/boton_accion', [
        'icono'   => '📄',
        'mensaje' => 'Ver detalle del proyecto',
        'url'     => '#',
        'tipo'    => 'ficha',
        'modalId' => 'modal-detalle-proyecto',
    ], [
        'saveData' => false,
    ]);

    $acciones .= view('components/ui/boton_accion', [
        'icono'   => '✏️',
        'mensaje' => 'Editar proyecto',
        'url'     => '#',
        'tipo'    => 'editar',
        'modalId' => 'modal-editar-proyecto',
    ], [
        'saveData' => false,
    ]);

    $acciones .= view('components/ui/boton_accion', [
        'icono'   => '🗑️',
        'mensaje' => 'Eliminar o desactivar proyecto',
        'url'     => '#',
        'tipo'    => 'eliminar',
        'modalId' => 'modal-eliminar-proyecto',
    ], [
        'saveData' => false,
    ]);

    /*
     * Conservamos la acción para consultar los sistemas asociados.
     */
    $acciones .= view('components/ui/boton_accion', [
        'icono'   => '🌐',
        'mensaje' => 'Ver sistemas asociados',
        'url'     => '#',
        'tipo'    => 'sistema',
        'modalId' => 'modal-sistemas-asociados',
    ], [
        'saveData' => false,
    ]);

    /*
     * El repositorio solo se habilita cuando existe una URL.
     */
    if (!empty($proyecto['repositorio_url'])) {
        $acciones .= view('components/ui/boton_accion', [
            'icono'        => '🐙',
            'mensaje'      => 'Abrir repositorio',
            'url'          => $proyecto['repositorio_url'],
            'tipo'         => 'repositorio',
            'nuevaPestana' => true,
        ], [
            'saveData' => false,
        ]);
    }

    $acciones .= '</div>';

    $filas[] = [
        esc($proyecto['nombre'] ?? 'Proyecto sin nombre'),
        $estado,
        esc($proyecto['origen'] ?? 'Sin especificar'),
        esc((string) ($proyecto['total_sistemas'] ?? 0)),
        esc($proyecto['fecha_creacion'] ?? 'Sin fecha'),
        $acciones,
    ];
}

$totalProyectos = count($proyectos);
?>

<section class="proyectos-seccion">

    <div class="proyectos-seccion__encabezado">
        <h3>Listado de proyectos</h3>

        <p>
            Consulta y administra los proyectos disponibles.
        </p>
    </div>

    <?= view('components/ui/barra_herramientas', [
        'idBusqueda'     => 'buscar-proyecto',
        'nombreBusqueda' => 'buscar_proyecto',
        'placeholder'    => 'Buscar proyecto...',
        'tablaObjetivo'  => 'tabla-proyectos',
    ]) ?>

    <?= view('components/ui/tabla', [
        'idTabla'          => 'tabla-proyectos',
        'columnas'         => $columnas,
        'filas'            => $filas,
        'mensajeVacio'     => 'Aún no hay proyectos registrados',
        'descripcionVacia' => 'Los proyectos aparecerán cuando exista información.',
        'iconoVacio'       => '📁',
        'totalRegistros'   => $totalProyectos,
        'paginaActual'     => 1,
        'totalPaginas'     => 1,
        'inicioRegistro'   => $totalProyectos > 0 ? 1 : 0,
        'finRegistro'      => $totalProyectos,
    ]) ?>

</section>