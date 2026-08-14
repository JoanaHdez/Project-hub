<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <div class="confirmacion-eliminar__icono">
            ⚠️
        </div>

        <div>

            <strong data-confirmacion-api-titulo>
                Confirmar acción
            </strong>

            <p data-confirmacion-api-mensaje>
                Confirma la acción para continuar.
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
        class="boton"
        data-confirmar-accion-api
    >
        Confirmar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-confirmar-accion-api',
    'titulo'    => 'Confirmar acción',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>