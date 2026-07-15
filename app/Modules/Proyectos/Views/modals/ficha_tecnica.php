<?php

$contenido = view(
    'components/ui/ficha_tecnica',
    [
        'codigo' => 'ET-01',

        'datos' => [
            'Framework' => 'CodeIgniter',
            'Versión del framework' => '4.7.4',
            'PHP' => '8.2.31',
            'Base de datos' => 'MySQL',
            'Repositorio' => 'GitHub',
            'Entorno local' => 'Laragon',
        ],
    ],
    [
        'saveData' => false,
    ]
);

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
    'id' => 'modal-ficha-tecnica',
    'titulo' => 'Ficha técnica',
    'tamano' => 'mediano',
    'contenido' => $contenido,
    'acciones' => $acciones,
], [
    'saveData' => false,
]) ?>