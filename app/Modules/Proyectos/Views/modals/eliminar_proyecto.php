<?php

$contenido = '
    <div class="confirmacion-eliminar">
        <span class="confirmacion-eliminar__icono">⚠️</span>

        <div>
            <strong>¿Deseas eliminar este proyecto?</strong>

            <p>
                Esta acción eliminará el proyecto seleccionado.
                Por ahora es una simulación y no modificará información.
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
    >
        Eliminar proyecto
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-eliminar-proyecto',
    'titulo'    => 'Eliminar proyecto',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>