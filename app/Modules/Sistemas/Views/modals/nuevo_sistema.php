<?php

$contenido = view(
    'App\Modules\Sistemas\Views\forms\sistema',
    [
        'modo' => 'crear',
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
        Cancelar
    </button>

    <button
        type="submit"
        form="form-nuevo-sistema"
        class="boton boton--primario"
    >
        Guardar sistema
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-nuevo-sistema',
    'titulo'    => 'Nuevo sistema',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>