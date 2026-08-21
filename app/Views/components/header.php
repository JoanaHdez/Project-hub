<?php

$nombreUsuario =
    (string) (
        session()
        ->get('usuario_nombre')
        ?? 'Usuario'
    );

$rolUsuario =
    (string) (
        session()
        ->get('usuario_rol')
        ?? 'usuario'
    );

$nombreRol =
    match ($rolUsuario) {

        'administrador' =>
            'Administrador',

        'usuario' =>
            'Usuario',

        default =>
            ucfirst($rolUsuario),
    };

?>


<header class="app-header">

    <!--==============================================
    =                    MARCA                       =
    ===============================================-->

    <div class="app-header__brand">

        <span class="app-header__logo">
            PH
        </span>

        <div>

            <h1 class="app-header__title">
                Project Hub
            </h1>

            <p class="app-header__subtitle">
                Integración y control de sistemas
            </p>

        </div>

    </div>


    <!--==============================================
    =                   USUARIO                      =
    ===============================================-->

    <div class="app-header__user">

        <button
            type="button"
            class="app-header__user-button"
            id="btn-menu-usuario"
            aria-expanded="false"
            aria-controls="menu-usuario"
        >

            <!-- Icono usuario -->

            <span
                class="app-header__user-icon"
                aria-hidden="true"
            >
                <svg
                    viewBox="0 0 24 24"
                    width="20"
                    height="20"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path
                        d="M20 21a8 8 0 0 0-16 0"
                    ></path>

                    <circle
                        cx="12"
                        cy="7"
                        r="4"
                    ></circle>
                </svg>
            </span>


            <!-- Información -->

            <span class="app-header__user-info">

                <strong class="app-header__user-name">
                    <?= esc($nombreUsuario) ?>
                </strong>

                <span class="app-header__user-role">
                    <?= esc($nombreRol) ?>
                </span>

            </span>


            <!-- Flecha -->

            <span
                class="app-header__user-arrow"
                aria-hidden="true"
            >
                ▾
            </span>

        </button>


        <!--==========================================
        =               MENÚ USUARIO                =
        ===========================================-->

        <div
            class="app-header__user-menu"
            id="menu-usuario"
            hidden
        >

            <div class="app-header__user-menu-info">

                <strong>
                    <?= esc($nombreUsuario) ?>
                </strong>

                <span>
                    <?= esc($nombreRol) ?>
                </span>

            </div>

            <div class="app-header__user-menu-divider"></div>

            <a
                href="<?= site_url('logout') ?>"
                class="app-header__logout"
            >
                <span aria-hidden="true">
                    ↪
                </span>

                <span>
                    Cerrar sesión
                </span>
            </a>

        </div>

    </div>

</header>