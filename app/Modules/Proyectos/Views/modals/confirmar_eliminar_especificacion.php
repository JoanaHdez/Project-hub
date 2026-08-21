<?php

$contenido = '
    <div class="confirmacion-eliminar">

        <span class="confirmacion-eliminar__icono">
            ⚠️
        </span>

        <div>
            <strong data-confirmacion-especificacion-titulo>
                Eliminar ficha técnica
            </strong>

            <p data-confirmacion-especificacion-mensaje>
                ¿Deseas eliminar esta ficha técnica?
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
        data-confirmar-eliminar-especificacion
    >
        Eliminar
    </button>
';

?>

<?= view(
    'components/ui/modal',
    [
        'id' =>
            'modal-confirmar-eliminar-especificacion',

        'titulo' =>
            'Eliminar ficha técnica',

        'tamano' =>
            'pequeno',

        'contenido' =>
            $contenido,

        'acciones' =>
            $acciones,
    ],
    [
        'saveData' =>
            false,
    ]
) ?>