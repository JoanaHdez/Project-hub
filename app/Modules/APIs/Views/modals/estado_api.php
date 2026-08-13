<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <span class="confirmacion-eliminar__icono">
            ⚠️
        </span>

        <div>

            <strong data-estado-api-titulo>
                ¿Deseas cambiar el estado de esta API?
            </strong>

            <p data-estado-api-mensaje>
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
        class="boton boton--secundario"
        id="btn-confirmar-estado-api"
    >
        Confirmar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-estado-api',
    'titulo'    => 'Cambiar estado de API',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>