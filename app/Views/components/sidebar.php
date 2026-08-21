<?php

$uri =
    service('uri');

$segmento =
    strtolower(
        trim(
            $uri->getSegment(1)
        )
    );

$rolUsuario =
    (string) session()
        ->get('usuario_rol');


/*==================================================
=              SECCIÓN ACTIVA                      =
==================================================*/

$seccionActiva =
    match ($segmento) {

        '',
        'dashboard',
        'actividad' =>
            'dashboard',

        'proyectos' =>
            'proyectos',

        'sistemas' =>
            'sistemas',

        'apis' =>
            'apis',

        'modulos' =>
            'modulos',

        'documentos' =>
            'documentos',

        'mis-sistemas' =>
            'mis-sistemas',

        default =>
            '',
    };

?>


<aside class="app-sidebar">

    <nav class="app-sidebar__nav">


        <?php if ($rolUsuario === 'administrador'): ?>


            <!--==========================================
            =                 DASHBOARD                  =
            ===========================================-->

            <a
                href="<?= site_url('/') ?>"
                class="
                    app-sidebar__link
                    <?= $seccionActiva === 'dashboard'
                        ? 'app-sidebar__link--activo'
                        : ''
                    ?>
                "
                <?= $seccionActiva === 'dashboard'
                    ? 'aria-current="page"'
                    : ''
                ?>
            >
                Dashboard
            </a>


            <!--==========================================
            =                 PROYECTOS                  =
            ===========================================-->

            <a
                href="<?= site_url('proyectos') ?>"
                class="
                    app-sidebar__link
                    <?= $seccionActiva === 'proyectos'
                        ? 'app-sidebar__link--activo'
                        : ''
                    ?>
                "
                <?= $seccionActiva === 'proyectos'
                    ? 'aria-current="page"'
                    : ''
                ?>
            >
                Proyectos
            </a>


            <!--==========================================
            =                  SISTEMAS                  =
            ===========================================-->

            <a
                href="<?= site_url('sistemas') ?>"
                class="
                    app-sidebar__link
                    <?= $seccionActiva === 'sistemas'
                        ? 'app-sidebar__link--activo'
                        : ''
                    ?>
                "
                <?= $seccionActiva === 'sistemas'
                    ? 'aria-current="page"'
                    : ''
                ?>
            >
                Sistemas
            </a>


            <!--==========================================
            =                    APIs                    =
            ===========================================-->

            <a
                href="<?= site_url('apis') ?>"
                class="
                    app-sidebar__link
                    <?= $seccionActiva === 'apis'
                        ? 'app-sidebar__link--activo'
                        : ''
                    ?>
                "
                <?= $seccionActiva === 'apis'
                    ? 'aria-current="page"'
                    : ''
                ?>
            >
                APIs
            </a>


            <!--==========================================
            =                  MÓDULOS                   =
            ===========================================-->

            <a
                href="<?= base_url('modulos') ?>"
                class="
                    app-sidebar__link
                    <?= $seccionActiva === 'modulos'
                        ? 'app-sidebar__link--activo'
                        : ''
                    ?>
                "
                <?= $seccionActiva === 'modulos'
                    ? 'aria-current="page"'
                    : ''
                ?>
            >
                Módulos
            </a>


            <!--==========================================
            =                DOCUMENTOS                  =
            ===========================================-->

            <a
                href="<?= base_url('documentos') ?>"
                class="
                    app-sidebar__link
                    <?= $seccionActiva === 'documentos'
                        ? 'app-sidebar__link--activo'
                        : ''
                    ?>
                "
                <?= $seccionActiva === 'documentos'
                    ? 'aria-current="page"'
                    : ''
                ?>
            >
                Documentos
            </a>


        <?php elseif ($rolUsuario === 'usuario'): ?>


            <!--==========================================
            =                MIS SISTEMAS                =
            ===========================================-->

            <a
                href="<?= base_url('mis-sistemas') ?>"
                class="
                    app-sidebar__link
                    <?= $seccionActiva === 'mis-sistemas'
                        ? 'app-sidebar__link--activo'
                        : ''
                    ?>
                "
                <?= $seccionActiva === 'mis-sistemas'
                    ? 'aria-current="page"'
                    : ''
                ?>
            >
                Mis sistemas
            </a>


        <?php endif; ?>


    </nav>

</aside>