<?php

$contenido = '
    <form
        id="form-observaciones-api"
        autocomplete="off"
    >

        <div class="form-api-documentacion">

            <div class="form-api-documentacion__encabezado">

                <div>
                    <h4>
                        Observaciones
                    </h4>

                    <p>
                        Registra notas importantes relacionadas
                        con el uso y mantenimiento de la API.
                    </p>
                </div>

                <button
                    type="button"
                    class="boton boton--secundario boton--sm"
                    id="btn-agregar-observacion"
                >
                    + Agregar observación
                </button>

            </div>


            <div
                id="observaciones-lista"
                class="form-api-documentacion__lista"
            >
            </div>


            <div
                id="observaciones-vacio"
                class="estado-vacio"
            >
                No se han agregado observaciones.
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
        form="form-observaciones-api"
        class="boton boton--primario"
    >
        Guardar observaciones
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-observaciones-api',
    'titulo'    => 'Completar observaciones',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>