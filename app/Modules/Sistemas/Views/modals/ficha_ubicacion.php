<?php

$contenido = '
    <p>
        Aquí se mostrará la información de ubicación del sistema seleccionado.
    </p>
';

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
    'id'        => 'modal-ficha-ubicacion',
    'titulo'    => 'Ficha de ubicación',
    'tamano'    => 'mediano',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>