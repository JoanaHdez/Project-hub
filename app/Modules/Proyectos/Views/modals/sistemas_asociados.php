<?php

$columnas = [
    'Nombre',
    'Tipo',
    'Estado',
];

$filas = [
    [
        esc('Pláticas de Extorsión'),
        esc('Sistema'),
        view('components/ui/estado', [
            'texto' => 'Producción',
            'tipo'  => 'produccion',
        ], [
            'saveData' => false,
        ]),
    ],
    [
        esc('APIs'),
        esc('Agrupador'),
        view('components/ui/estado', [
            'texto' => 'Producción',
            'tipo'  => 'produccion',
        ], [
            'saveData' => false,
        ]),
    ],
    [
        esc('Reportes'),
        esc('Módulo'),
        view('components/ui/estado', [
            'texto' => 'Producción',
            'tipo'  => 'produccion',
        ], [
            'saveData' => false,
        ]),
    ],
];

$contenido = '
    <div class="sistemas-asociados__proyecto">
        <span>Proyecto</span>
        <strong>Extorsión</strong>
    </div>
';

$contenido .= view('components/ui/tabla', [
    'columnas'       => $columnas,
    'filas'          => $filas,
    'totalRegistros' => 3,
    'paginaActual'   => 1,
    'totalPaginas'   => 1,
    'inicioRegistro' => 1,
    'finRegistro'    => 3,
], [
    'saveData' => false,
]);

$acciones = '
    <button
        type="button"
        class="boton boton--secundario"
        data-modal-cerrar
    >
        Cerrar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-sistemas-asociados',
    'titulo'    => 'Sistemas asociados',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>