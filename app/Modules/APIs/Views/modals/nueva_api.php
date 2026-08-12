<?php

$contenido = view(
    'App\Modules\APIs\Views\forms\api',
    [
        'modo'      => 'crear',
        'proyectos' => $proyectos ?? [],
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
        form="form-nueva-api"
        class="boton boton--primario"
    >
        Guardar API
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-nueva-api',
    'titulo'    => 'Nueva API',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>