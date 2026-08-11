<?php

$contenido = '
    <div class="sistemas-asociados">

        <div class="sistemas-asociados__proyecto">
            <span>Proyecto</span>

            <strong data-sistemas-proyecto-nombre>
                Proyecto seleccionado
            </strong>
        </div>

        <div
            class="sistemas-asociados__contenido"
            data-sistemas-asociados-contenido
        >
            <p>
                Cargando sistemas asociados...
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
        Cerrar
    </button>

    <button
        type="button"
        class="boton boton--primario"
        data-agregar-sistema-proyecto
    >
        + Agregar sistema
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-sistemas-asociados',
    'titulo'    => 'Sistemas asociados',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>