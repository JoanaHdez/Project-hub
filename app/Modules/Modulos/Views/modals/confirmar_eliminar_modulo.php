<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <div class="confirmacion-eliminar__icono">
            ⚠️
        </div>

        <div>

            <strong>
                Eliminar módulo
            </strong>

            <p>
                ¿Confirmas que deseas eliminar este módulo?
                Esta acción eliminará definitivamente su registro.
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
        data-confirmar-eliminar-modulo
    >
        Eliminar módulo
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-confirmar-eliminar-modulo',
    'titulo'    => 'Eliminar módulo',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>