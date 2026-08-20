<section class="dashboard-seccion">

    <!--==============================================
    =                 ENCABEZADO                    =
    ===============================================-->

    <div class="dashboard-seccion__encabezado">

        <h3>
            Accesos rápidos
        </h3>

        <p>
            Accede directamente a las acciones principales
            de Project Hub.
        </p>

    </div>


    <!--==============================================
    =              ACCESOS RÁPIDOS                  =
    ===============================================-->

    <div class="accesos-rapidos">


        <!--==========================================
        =                 PROYECTOS                  =
        ===========================================-->

        <a
            href="<?= base_url('proyectos') ?>"
            class="acceso-rapido"
        >

            <span class="acceso-rapido__icono">
                📁
            </span>

            <span class="acceso-rapido__contenido">

                <strong>
                    Proyectos
                </strong>

                <small>
                    Consulta y administra los proyectos.
                </small>

            </span>

        </a>


        <!--==========================================
        =                  SISTEMAS                  =
        ===========================================-->

        <a
            href="<?= base_url('sistemas') ?>"
            class="acceso-rapido"
        >

            <span class="acceso-rapido__icono">
                🖥️
            </span>

            <span class="acceso-rapido__contenido">

                <strong>
                    Sistemas
                </strong>

                <small>
                    Consulta y administra los sistemas.
                </small>

            </span>

        </a>


        <!--==========================================
        =                  MÓDULOS                   =
        ===========================================-->

        <a
            href="<?= base_url('modulos') ?>"
            class="acceso-rapido"
        >

            <span class="acceso-rapido__icono">
                🧩
            </span>

            <span class="acceso-rapido__contenido">

                <strong>
                    Módulos
                </strong>

                <small>
                    Explora los módulos registrados.
                </small>

            </span>

        </a>


        <!--==========================================
        =                    APIs                    =
        ===========================================-->

        <a
            href="<?= base_url('apis') ?>"
            class="acceso-rapido"
        >

            <span class="acceso-rapido__icono">
                🔌
            </span>

            <span class="acceso-rapido__contenido">

                <strong>
                    APIs
                </strong>

                <small>
                    Consulta y administra las APIs.
                </small>

            </span>

        </a>

    </div>

</section>