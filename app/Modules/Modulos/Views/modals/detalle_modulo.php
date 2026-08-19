<?php

$contenido = '
    <form
        id="form-detalle-modulo"
        autocomplete="off"
    >

        <div class="form-grid">

            <div class="form-grupo">

                <label for="detalle-modulo-tipo">
                    Tipo
                </label>

                <input
                    type="text"
                    id="detalle-modulo-tipo"
                    name="tipo"
                    readonly
                >

            </div>

            <div class="form-grupo">

                <label for="detalle-modulo-nombre">
                    Nombre
                </label>

                <input
                    type="text"
                    id="detalle-modulo-nombre"
                    name="nombre"
                    readonly
                >

            </div>

            <div class="form-grupo form-grupo--completo">

                <label for="detalle-modulo-descripcion">
                    Descripción
                </label>

                <textarea
                    id="detalle-modulo-descripcion"
                    name="descripcion"
                    rows="4"
                    readonly
                ></textarea>

            </div>

            <div class="form-grupo form-grupo--completo">

                <label for="detalle-modulo-url">
                    URL
                </label>

                <input
                    type="text"
                    id="detalle-modulo-url"
                    name="url"
                    readonly
                >

            </div>

        </div>

    </form>
';

$acciones = '
    <button
        type="button"
        class="boton boton--secundario"
        data-modal-cerrar
    >
        Cerrar
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-detalle-modulo',
    'titulo'    => 'Ficha del módulo',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>