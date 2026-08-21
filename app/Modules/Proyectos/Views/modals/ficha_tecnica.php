<?php

$contenido = view(
    'components/ui/ficha_tecnica',
    [
        'codigo' => '—',

        'datos' => [
            'Framework' => '—',
            'Versión del framework' => '—',
            'PHP' => '—',
            'Base de datos' => '—',
            'Repositorio' => '—',
            'Entorno local' => '—',
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