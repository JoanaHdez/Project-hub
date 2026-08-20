<?php

$rutaActual =
    trim(
        service('uri')->getPath(),
        '/'
    );

$seccionActiva =
    match (true) {

        $rutaActual === ''
        || $rutaActual === 'dashboard'
        || str_starts_with(
            $rutaActual,
            'actividad'
        ) =>
            'dashboard',

        str_starts_with(
            $rutaActual,
            'proyectos'
        ) =>
            'proyectos',

        str_starts_with(
            $rutaActual,
            'sistemas'
        ) =>
            'sistemas',

        str_starts_with(
            $rutaActual,
            'apis'
        ) =>
            'apis',

        str_starts_with(
            $rutaActual,
            'modulos'
        ) =>
            'modulos',

        str_starts_with(
            $rutaActual,
            'documentos'
        ) =>
            'documentos',

        default =>
            '',
    };

?>


<aside class="app-sidebar">

    <nav class="app-sidebar__nav">


        <a href="<?= site_url('/') ?>" class="
                app-sidebar__link
                <?= $seccionActiva === 'dashboard'
                    ? 'app-sidebar__link--activo'
                    : ''
                ?>
            " <?= $seccionActiva === 'dashboard'
                ? 'aria-current="page"'
                : ''
            ?>>
            Dashboard
        </a>


        <a href="<?= site_url('proyectos') ?>" class="
                app-sidebar__link
                <?= $seccionActiva === 'proyectos'
                    ? 'app-sidebar__link--activo'
                    : ''
                ?>
            " <?= $seccionActiva === 'proyectos'
                ? 'aria-current="page"'
                : ''
            ?>>
            Proyectos
        </a>


        <a href="<?= site_url('sistemas') ?>" class="
                app-sidebar__link
                <?= $seccionActiva === 'sistemas'
                    ? 'app-sidebar__link--activo'
                    : ''
                ?>
            " <?= $seccionActiva === 'sistemas'
                ? 'aria-current="page"'
                : ''
            ?>>
            Sistemas
        </a>


        <a href="<?= site_url('apis') ?>" class="
                app-sidebar__link
                <?= $seccionActiva === 'apis'
                    ? 'app-sidebar__link--activo'
                    : ''
                ?>
            " <?= $seccionActiva === 'apis'
                ? 'aria-current="page"'
                : ''
            ?>>
            APIs
        </a>


        <a href="<?= base_url('modulos') ?>" class="
                app-sidebar__link
                <?= $seccionActiva === 'modulos'
                    ? 'app-sidebar__link--activo'
                    : ''
                ?>
            " <?= $seccionActiva === 'modulos'
                ? 'aria-current="page"'
                : ''
            ?>>
            Módulos
        </a>


        <a href="<?= base_url('documentos') ?>" class="
                app-sidebar__link
                <?= $seccionActiva === 'documentos'
                    ? 'app-sidebar__link--activo'
                    : ''
                ?>
            " <?= $seccionActiva === 'documentos'
                ? 'aria-current="page"'
                : ''
            ?>>
            Documentos
        </a>


    </nav>

</aside>