<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <div class="confirmacion-eliminar__icono">
            ⚠️
        </div>

        <div>

            <strong>
                Eliminar documento
            </strong>

            <p>
                ¿Seguro que deseas eliminar este documento?
                Esta acción no se puede deshacer.
            </p>

        </div>

    </div>
';

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
        class="boton boton--peligro"
        data-confirmar-eliminar-documento
    >
        Eliminar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-confirmar-eliminar-documento',
    'titulo'    => 'Confirmar eliminación',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>