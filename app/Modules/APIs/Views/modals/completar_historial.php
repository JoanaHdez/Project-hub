<?php

$contenido = '
    <form
        id="form-historial-api"
        autocomplete="off"
    >

        <div class="form-api-documentacion">

            <div class="form-api-documentacion__encabezado">

                <div>
                    <h4>
                        Cambios de la API
                    </h4>

                    <p>
                        Registra las versiones y cambios
                        importantes realizados en la API.
                    </p>
                </div>

                <button
                    type="button"
                    class="boton boton--secundario boton--sm"
                    id="btn-agregar-historial"
                >
                    + Agregar cambio
                </button>

            </div>


            <div
                id="historial-lista"
                class="form-api-documentacion__lista"
            >
            </div>


            <div
                id="historial-vacio"
                class="estado-vacio"
            >
                No se han agregado cambios al historial.
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
        form="form-historial-api"
        class="boton boton--primario"
    >
        Guardar historial
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-historial-api',
    'titulo'    => 'Completar historial',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>