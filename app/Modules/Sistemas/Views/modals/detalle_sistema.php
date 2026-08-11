<?php

$contenido = view(
    'App\Modules\Sistemas\Views\forms\sistema',
    [
        'modo' => 'detalle',
        'sistema' => [
            'id_proyecto'        => '',
            'proyecto_nombre'    => '',
            'nombre'             => '',
            'estado'             => '',
            'tipo'               => '',
            'modo_visualizacion' => '',
            'descripcion'        => '',
            'url'                => '',
            'repositorio_url'    => '',
            'ruta_local'         => '',
            'url_servidor'       => '',
            'responsable'        => '',
            'observaciones'      => '',
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
    'id'        => 'modal-detalle-sistema',
    'titulo'    => 'Detalle del sistema',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>