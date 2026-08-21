<?php

$contenido = '

<form
    id="form-nueva-ficha-tecnica"
    autocomplete="off"
>

    <div class="formulario-proyecto">


        <!--==========================================
        =                  CÓDIGO                    =
        ===========================================-->

        <div class="form-bloque">

            <div class="form-grid">

                <div class="form-grupo form-grupo--completo">

                    <label for="ficha-tecnica-codigo">
                        Código
                    </label>

                    <input
                        type="text"
                        id="ficha-tecnica-codigo"
                        name="codigo"
                        placeholder="Ej. ET-03"
                        required
                    >

                </div>

            </div>

        </div>


        <!--==========================================
        =                 FRAMEWORK                  =
        ===========================================-->

        <div class="form-bloque">

            <div class="form-grid">

                <div class="form-grupo">

                    <label for="ficha-tecnica-framework">
                        Framework
                    </label>

                    <input
                        type="text"
                        id="ficha-tecnica-framework"
                        name="framework"
                        placeholder="Ej. CodeIgniter"
                    >

                </div>


                <div class="form-grupo">

                    <label for="ficha-tecnica-version-framework">
                        Versión del framework
                    </label>

                    <input
                        type="text"
                        id="ficha-tecnica-version-framework"
                        name="version_framework"
                        placeholder="Ej. 4.7.4"
                    >

                </div>

            </div>

        </div>


        <!--==========================================
        =              ENTORNO TÉCNICO               =
        ===========================================-->

        <div class="form-bloque">

            <div class="form-grid">

                <div class="form-grupo">

                    <label for="ficha-tecnica-php">
                        PHP
                    </label>

                    <input
                        type="text"
                        id="ficha-tecnica-php"
                        name="php"
                        placeholder="Ej. 8.3.16"
                    >

                </div>


                <div class="form-grupo">

                    <label for="ficha-tecnica-base-datos">
                        Base de datos
                    </label>

                    <input
                        type="text"
                        id="ficha-tecnica-base-datos"
                        name="base_datos"
                        placeholder="Ej. MySQL"
                    >

                </div>

            </div>

        </div>


        <!--==========================================
        =              HERRAMIENTAS                  =
        ===========================================-->

        <div class="form-bloque">

            <div class="form-grid">

                <div class="form-grupo">

                    <label for="ficha-tecnica-repositorio">
                        Repositorio
                    </label>

                    <input
                        type="text"
                        id="ficha-tecnica-repositorio"
                        name="repositorio"
                        placeholder="Ej. GitHub"
                    >

                </div>


                <div class="form-grupo">

                    <label for="ficha-tecnica-entorno-local">
                        Entorno local
                    </label>

                    <input
                        type="text"
                        id="ficha-tecnica-entorno-local"
                        name="entorno_local"
                        placeholder="Ej. Laragon"
                    >

                </div>

            </div>

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
        form="form-nueva-ficha-tecnica"
        class="boton boton--primario"
    >
        Guardar ficha
    </button>

';

?>


<?= view(
    'components/ui/modal',
    [
        'id' =>
            'modal-nueva-ficha-tecnica',

        'titulo' =>
            'Nueva ficha técnica',

        'tamano' =>
            'mediano',

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