<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= esc(
            $title
            ?? 'Iniciar sesión | Project Hub'
        ) ?>
    </title>

    <link
        rel="stylesheet"
        href="<?= base_url(
            'assets/css/app.css'
        ) ?>"
    >

    <link
        rel="stylesheet"
        href="<?= base_url(
            'assets/css/auth/login.css'
        ) ?>"
    >

</head>


<body class="login-body">


    <main class="login-page">

        <section class="login-card">


            <!--==========================================
            =                   MARCA                    =
            ===========================================-->

            <div class="login-marca">

                <div class="login-marca__logo">
                    PH
                </div>

                <div>

                    <h1>
                        Project Hub
                    </h1>

                    <p>
                        Integración y control de sistemas
                    </p>

                </div>

            </div>


            <!--==========================================
            =                  ENCABEZADO                =
            ===========================================-->

            <div class="login-encabezado">

                <h2>
                    Iniciar sesión
                </h2>

                <p>
                    Ingresa tus credenciales para continuar.
                </p>

            </div>


            <!--==========================================
            =                    ERROR                   =
            ===========================================-->

            <?php if (
                session()
                ->getFlashdata('error')
            ): ?>

                <div class="login-error">

                    <?= esc(
                        session()
                        ->getFlashdata('error')
                    ) ?>

                </div>

            <?php endif; ?>


            <!--==========================================
            =                 FORMULARIO                 =
            ===========================================-->

            <form
                method="post"
                action="<?= base_url('login') ?>"
                class="login-form"
                autocomplete="off"
            >

                <?= csrf_field() ?>


                <div class="login-form__grupo">

                    <label for="login-correo">
                        Correo
                    </label>

                    <input
                        type="email"
                        id="login-correo"
                        name="correo"
                        placeholder="correo@ejemplo.com"
                        required
                        autofocus
                    >

                </div>


                <div class="login-form__grupo">

                    <label for="login-password">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="login-password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="login-form__boton"
                >
                    Iniciar sesión
                </button>

            </form>


        </section>

    </main>


</body>

</html>