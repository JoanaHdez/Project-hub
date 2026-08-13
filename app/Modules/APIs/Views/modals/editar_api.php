<?php

$contenido = view(
    'App\Modules\APIs\Views\forms\api',
    [
        'modo'      => 'editar',
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
        form="form-editar-api"
        class="boton boton--primario"
    >
        Guardar cambios
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-editar-api',
    'titulo'    => 'Editar API',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>