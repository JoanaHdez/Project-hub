<?php

$estadoProduccion = view('components/ui/estado', [
    'texto' => 'Producción',
    'tipo'  => 'produccion',
]);

$acciones = '
    <div class="acciones-proyecto">
';

$acciones .= view('components/ui/boton_accion', [
    'icono'   => '✏️',
    'mensaje' => 'Editar proyecto',
    'url'     => '#',
    'tipo'    => 'editar',
    'modalId' => 'modal-editar-proyecto',
    [
        'saveData' => false,
    ]
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'   => '🗑️',
    'mensaje' => 'Eliminar proyecto',
    'url'     => '#',
    'tipo'    => 'eliminar',
    'modalId' => 'modal-eliminar-proyecto',
    [
        'saveData' => false,
    ]
]);

$acciones .= view('components/ui/boton_accion', [

    'icono' => '🌐',

    'mensaje' => 'Ver sistemas asociados',

    'url' => '#',

    'tipo' => 'sistema',

    'modalId' => 'modal-sistemas-asociados',

], [
    'saveData' => false,
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'         => '🐙',
    'mensaje'       => 'Abrir repositorio',
    'url'           => '#',
    'tipo'          => 'repositorio',
    'nuevaPestana'  => true,
    [
        'saveData' => false,
    ]
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'   => '📄',
    'mensaje' => 'Ver ficha técnica',
    'url'     => '#',
    'tipo'    => 'ficha',
    'modalId' => 'modal-ficha-tecnica',
    [
        'saveData' => false,
    ]
]);

$acciones .= '
    </div>
';

$columnas = [
    'Proyecto',
    'Estado',
    'Responsable',
    'Especificación técnica',
    'Última actualización',
    'Acciones',
];

$filas = [
    [
        esc('Extorsión'),
        $estadoProduccion,
        esc('Joana Herrera'),
        esc('ET-01'),
        esc('13/07/2026'),
        $acciones,
    ],
];


/* $filas = [];
 */
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
        'idTabla'           => 'tabla-proyectos',
        'columnas'          => $columnas,
        'filas'             => $filas,
        'mensajeVacio'      => 'Aún no hay proyectos registrados',
        'descripcionVacia'  => 'Los proyectos aparecerán cuando exista información.',
        'iconoVacio'        => '📁',
        'totalRegistros'    => 1,
        'paginaActual'      => 1,
        'totalPaginas'      => 1,
        'inicioRegistro'    => 1,
        'finRegistro'       => 1,
    ]) ?>

</section>