<?php

$contenido = view(
    'App\Modules\Proyectos\Views\forms\proyecto',
    [
        'modo' => 'editar',
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
        form="form-proyecto"
        class="boton boton--primario"
    >
        Guardar cambios
    </button>
';

?>

<?= view('components/ui/modal', [

    'id' => 'modal-editar-proyecto',

    'titulo' => 'Editar proyecto',

    'tamano' => 'grande',

    'contenido' => $contenido,

    'acciones' => $acciones,

], [
    'saveData' => false,
]) ?>