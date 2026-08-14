<div id="modal-ficha-tecnica" class="modal-ficha" aria-hidden="true">
    <div class="modal-ficha__fondo" data-cerrar-ficha></div>

    <section class="modal-ficha__contenedor" role="dialog" aria-modal="true" aria-labelledby="titulo-ficha-tecnica">

        <?= view(
            'App\Modules\APIs\Views\ficha\encabezado'
        ) ?>

        <div class="modal-ficha__cuerpo">

            <?= view(
                'App\Modules\APIs\Views\ficha\sidebar'
            ) ?>

            <main class="modal-ficha__contenido">

                <?= view(
                    'App\Modules\APIs\Views\ficha\secciones\general'
                ) ?>

                <?= view(
                    'App\Modules\APIs\Views\ficha\secciones\ubicacion'
                ) ?>

                <?= view(
                    'App\Modules\APIs\Views\ficha\secciones\arquitectura'
                ) ?>

                <?= view(
                    'App\Modules\APIs\Views\ficha\secciones\dependencias'
                ) ?>

                <?= view(
                    'App\Modules\APIs\Views\ficha\secciones\observaciones'
                ) ?>


                <?= view(
                    'App\Modules\APIs\Views\ficha\secciones\historial'
                ) ?>

            </main>

        </div>

    </section>

</div>