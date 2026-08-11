<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <span class="confirmacion-eliminar__icono">
            ⚠️
        </span>

        <div>
            <strong data-confirmacion-sistema-titulo>
                Confirmar acción
            </strong>

            <p data-confirmacion-sistema-mensaje>
                ¿Deseas continuar con esta acción?
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
        data-confirmar-accion-sistema
    >
        Confirmar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-confirmar-accion-sistema',
    'titulo'    => 'Confirmar acción',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>