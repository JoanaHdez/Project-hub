<?php

$contenido = '
    <form
        id="form-nuevo-modulo"
        autocomplete="off"
    >

        <div class="form-grid">

            <div class="form-grupo">

                <label for="modulo-tipo">
                    Tipo
                </label>

                <input
                    type="text"
                    id="modulo-tipo"
                    name="tipo"
                    placeholder="Ej. Reportes"
                >

            </div>

            <div class="form-grupo">

                <label for="modulo-nombre">
                    Nombre
                </label>

                <input
                    type="text"
                    id="modulo-nombre"
                    name="nombre"
                    placeholder="Ej. Dashboard"
                    required
                >

            </div>

            <div class="form-grupo form-grupo--completo">

                <label for="modulo-descripcion">
                    Descripción
                </label>

                <textarea
                    id="modulo-descripcion"
                    name="descripcion"
                    rows="4"
                    placeholder="Describe la función de este módulo."
                ></textarea>

            </div>

            <div class="form-grupo form-grupo--completo">

                <label for="modulo-url">
                    URL
                </label>

                <input
                    type="url"
                    id="modulo-url"
                    name="url"
                    placeholder="https://..."
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
        Cancelar
    </button>

    <button
        type="submit"
        form="form-nuevo-modulo"
        class="boton boton--primario"
    >
        Guardar módulo
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-nuevo-modulo',
    'titulo'    => 'Nuevo módulo',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>