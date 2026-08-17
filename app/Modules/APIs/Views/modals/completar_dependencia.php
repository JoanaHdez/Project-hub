<?php

$contenido = '
    <form
        id="form-dependencias-api"
        autocomplete="off"
    >

        <div class="form-api-documentacion">

            <div class="form-api-documentacion__encabezado">

                <div>
                    <h4>
                        Dependencias
                    </h4>

                    <p>
                        Registra los servicios, recursos o componentes
                        necesarios para el funcionamiento de la API.
                    </p>
                </div>

                <button
                    type="button"
                    class="boton boton--secundario boton--sm"
                    id="btn-agregar-dependencia"
                >
                    + Agregar dependencia
                </button>

            </div>


            <div
                id="dependencias-lista"
                class="form-api-documentacion__lista"
            >
            </div>


            <div
                id="dependencias-vacio"
                class="estado-vacio"
            >
                No se han agregado dependencias.
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
        form="form-dependencias-api"
        class="boton boton--primario"
    >
        Guardar dependencias
    </button>
';

?>

<?= view('components/ui/modal', [
    'id'        => 'modal-dependencias-api',
    'titulo'    => 'Completar dependencias',
    'tamano'    => 'grande',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]) ?>