<?php

$contenido = view('App\Modules\Proyectos\Views\forms\proyecto');

$acciones = '
    <button
        type="button"
        class="boton boton--secundario"
        data-modal-cerrar
    >
        Cancelar
    </button>

    <button
        type="button"
        class="boton boton--primario"
    >
        Guardar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-nuevo-proyecto',
    'titulo'    => 'Nuevo proyecto',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
]) ?>