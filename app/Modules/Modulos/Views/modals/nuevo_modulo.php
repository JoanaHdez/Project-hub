<?php

$contenido = '
    <form
        id="form-nuevo-modulo"
        autocomplete="off"
        data-modo="nuevo"
    >

        <input
            type="hidden"
            id="modulo-id"
            name="id_modulo"
            value=""
        >


        <!--==========================================
        =              IMAGEN DEL MÓDULO            =
        ===========================================-->

        <div
            class="modulo-imagen-editor"
            data-modulo-imagen-editor
            hidden
        >

            <div
                class="modulo-imagen-editor__vista"
                data-modulo-imagen-vista
            >

                <img
                    src=""
                    alt="Vista previa del módulo"
                    class="modulo-imagen-editor__archivo"
                    data-modulo-imagen-preview
                    hidden
                >

                <span
                    class="modulo-imagen-editor__vacia"
                    data-modulo-imagen-vacia
                >
                    Vista previa
                </span>


                <!--==================================
                =          BOTÓN EDITAR IMAGEN       =
                ===================================-->

                <button
                    type="button"
                    class="modulo-imagen-editor__boton"
                    data-modulo-imagen-seleccionar
                    aria-label="Cambiar imagen del módulo"
                    title="Cambiar imagen"
                    hidden
                >
                    ✎
                </button>

            </div>


            <!--======================================
            =          SELECTOR DE ARCHIVO           =
            =======================================-->

            <input
                type="file"
                id="modulo-imagen"
                name="imagen"
                accept="image/jpeg,image/png,image/webp"
                data-modulo-imagen-input
                hidden
            >

        </div>


        <!--==========================================
        =              DATOS DEL MÓDULO             =
        ===========================================-->

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
        <span data-modulo-texto-cerrar>
            Cancelar
        </span>
    </button>

    <button
        type="button"
        class="boton boton--peligro"
        data-modulo-eliminar
        hidden
    >
        Eliminar
    </button>

    <button
        type="button"
        class="boton boton--primario"
        data-modulo-editar
        hidden
    >
        Editar
    </button>

    <button
        type="submit"
        form="form-nuevo-modulo"
        class="boton boton--primario"
        data-modulo-guardar
    >
        <span data-modulo-texto-guardar>
            Guardar módulo
        </span>
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