<?php

$estadoProduccion = view('components/ui/estado', [
    'texto' => 'Producción',
    'tipo'  => 'produccion',
]);

$acciones = '
    <div class="acciones-proyecto">
';

$acciones .= view('components/ui/boton_accion', [
    'icono'  => '✏️',
    'mensaje' => 'Editar proyecto',
    'url'     => '#',
    'tipo'    => 'editar',
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'  => '🗑️',
    'mensaje' => 'Eliminar proyecto',
    'url'     => '#',
    'tipo'    => 'eliminar',
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'         => '🌐',
    'mensaje'       => 'Abrir sistema',
    'url'           => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/',
    'tipo'          => 'sistema',
    'nuevaPestana'  => true,
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'         => '🐙',
    'mensaje'       => 'Abrir repositorio',
    'url'           => '#',
    'tipo'          => 'repositorio',
    'nuevaPestana'  => true,
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'  => '📄',
    'mensaje' => 'Ver ficha técnica',
    'url'     => '#',
    'tipo'    => 'ficha',
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