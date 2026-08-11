<?php

$contenido = '
    <div class="confirmacion-eliminar">
        <div class="confirmacion-eliminar__icono">
            ⚠️
        </div>

        <div>
            <strong>
                ¿Qué deseas hacer con este sistema?
            </strong>

            <p>
                Estás administrando
                <strong data-eliminar-sistema-nombre>
                    "Sistema"
                </strong>.
            </p>

            <p>
                Puedes desactivarlo temporalmente o eliminarlo
                definitivamente.
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
        data-boton-estado-sistema
        data-sistema-accion="desactivar"
    >
        Desactivar
    </button>

    <button
        type="button"
        class="boton boton--peligro"
        data-sistema-accion="eliminar"
    >
        Eliminar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-eliminar-sistema',
    'titulo'    => 'Administrar sistema',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>