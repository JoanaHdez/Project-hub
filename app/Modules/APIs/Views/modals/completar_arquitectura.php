<?php

$contenido = '
    <form
        id="form-arquitectura-api"
        autocomplete="off"
    >

        <div class="form-grupo">

            <label for="arquitectura-modulo">
                Módulo
            </label>

            <input
                type="text"
                id="arquitectura-modulo"
                name="modulo"
                placeholder="Ej. ConstanciaAPI"
            >

        </div>


        <div class="form-api-documentacion">

            <div class="form-api-documentacion__encabezado">

                <div>
                    <h4>
                        Componentes
                    </h4>

                    <p>
                        Registra las carpetas y archivos relacionados
                        con la API.
                    </p>
                </div>

                <button
                    type="button"
                    class="boton boton--secundario boton--sm"
                    id="btn-agregar-componente-arquitectura"
                >
                    + Agregar componente
                </button>

            </div>


            <div
                id="arquitectura-componentes"
                class="form-api-documentacion__lista"
            >
            </div>


            <div
                id="arquitectura-componentes-vacio"
                class="estado-vacio"
            >
                No se han agregado componentes.
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
        form="form-arquitectura-api"
        class="boton boton--primario"
    >
        Guardar arquitectura
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-arquitectura-api',
    'titulo'    => 'Completar arquitectura',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>