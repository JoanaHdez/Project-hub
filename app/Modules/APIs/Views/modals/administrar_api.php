<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <div class="confirmacion-eliminar__icono">
            ⚠️
        </div>

        <div>

            <strong>
                ¿Qué deseas hacer con esta API?
            </strong>

            <p>
                Estás administrando
                <strong data-administrar-api-nombre>
                    "API"
                </strong>.
            </p>

            <p>
                Puedes desactivarla temporalmente o eliminarla
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
        data-boton-estado-api
        data-api-accion="desactivar"
    >
        Desactivar
    </button>

    <button
        type="button"
        class="boton boton--peligro"
        data-api-accion="eliminar"
    >
        Eliminar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-administrar-api',
    'titulo'    => 'Administrar API',
    'tamano'    => 'pequeno',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>