<?php

$contenido = view(
    'App\Modules\Sistemas\Views\forms\sistema',
    [
        'modo' => 'editar',
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
        form="form-editar-sistema"
        class="boton boton--primario"
    >
        Guardar cambios
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-editar-sistema',
    'titulo'    => 'Editar sistema',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>