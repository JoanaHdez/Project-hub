<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <span class="confirmacion-eliminar__icono">
            ⚠️
        </span>

        <div>
            <strong>
                ¿Qué deseas hacer con el proyecto
                <span data-eliminar-proyecto-nombre>
                    seleccionado
                </span>?
            </strong>

            <p>
                Puedes desactivarlo para conservar su información
                o eliminarlo definitivamente.
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
    data-proyecto-accion="desactivar"
    data-boton-estado-proyecto
>
    Desactivar
</button>

    <button
        type="button"
        class="boton boton--peligro"
        data-proyecto-accion="eliminar"
    >
        Eliminar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-eliminar-proyecto',
    'titulo'    => 'Eliminar o desactivar proyecto',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>