<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <span class="confirmacion-eliminar__icono">
            ⚠️
        </span>

        <div>
            <strong data-confirmacion-titulo>
                Confirmar acción
            </strong>

            <p data-confirmacion-mensaje>
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
        data-confirmar-accion-proyecto
    >
        Confirmar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-confirmar-accion-proyecto',
    'titulo'    => 'Confirmar acción',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>