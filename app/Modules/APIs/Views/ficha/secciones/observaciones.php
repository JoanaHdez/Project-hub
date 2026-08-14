<section
    class="ficha-seccion"
    data-ficha-contenido="observaciones"
>

    <div class="ficha-seccion__encabezado">

        <span class="ficha-seccion__numero">
            05
        </span>

        <div>
            <h3>
                Observaciones
            </h3>

            <p>
                Notas importantes relacionadas con el uso y mantenimiento de la API.
            </p>
        </div>

    </div>

    <div class="ficha-alertas">

        <article
            class="ficha-alerta ficha-alerta--info"
        >

            <div class="ficha-alerta__encabezado">

                <span
                    class="ficha-alerta__punto"
                ></span>

                <strong>
                    Información
                </strong>

            </div>

            <p>
                La API únicamente acepta solicitudes mediante
                el método HTTP <strong>POST</strong>.
            </p>

        </article>


        <article
            class="ficha-alerta ficha-alerta--warning"
        >

            <div class="ficha-alerta__encabezado">

                <span
                    class="ficha-alerta__punto"
                ></span>

                <strong>
                    Recomendación
                </strong>

            </div>

            <p>
                Verificar la configuración SMTP antes de
                desplegar el servicio en producción.
            </p>

        </article>


        <article
            class="ficha-alerta ficha-alerta--danger"
        >

            <div class="ficha-alerta__encabezado">

                <span
                    class="ficha-alerta__punto"
                ></span>

                <strong>
                    Importante
                </strong>

            </div>

            <p>
                El API Token debe permanecer en el archivo
                <strong>.env</strong> y nunca exponerse desde
                aplicaciones cliente.
            </p>

        </article>

    </div>

</section>