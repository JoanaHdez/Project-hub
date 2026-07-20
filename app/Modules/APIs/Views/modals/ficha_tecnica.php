<div
    id="modal-ficha-tecnica"
    class="modal-ficha"
    aria-hidden="true"
>
    <div
        class="modal-ficha__fondo"
        data-cerrar-ficha
    ></div>

    <section
        class="modal-ficha__contenedor"
        role="dialog"
        aria-modal="true"
        aria-labelledby="titulo-ficha-tecnica"
    >

        <header class="modal-ficha__encabezado">

            <div class="modal-ficha__titulo-contenedor">

                <span class="modal-ficha__etiqueta">
                    Ficha técnica
                </span>

                <h2
                    id="titulo-ficha-tecnica"
                    class="modal-ficha__titulo"
                >
                    API de Constancias
                </h2>

                <p class="modal-ficha__subtitulo">
                    Proyecto Extorsión
                </p>

            </div>

            <button
                type="button"
                id="cerrar-ficha-tecnica"
                class="modal-ficha__cerrar"
                aria-label="Cerrar ficha técnica"
            >
                ×
            </button>

        </header>

        <div class="modal-ficha__cuerpo">

            <aside class="modal-ficha__sidebar">

                <nav
                    class="ficha-nav"
                    aria-label="Secciones de la ficha técnica"
                >

                    <button
                        type="button"
                        class="ficha-nav__item ficha-nav__item--activo"
                        data-ficha-seccion="general"
                    >
                        <span class="ficha-nav__numero">01</span>
                        <span>General</span>
                    </button>

                    <button
                        type="button"
                        class="ficha-nav__item"
                        data-ficha-seccion="ubicacion"
                    >
                        <span class="ficha-nav__numero">02</span>
                        <span>Ubicación</span>
                    </button>

                    <button
                        type="button"
                        class="ficha-nav__item"
                        data-ficha-seccion="arquitectura"
                    >
                        <span class="ficha-nav__numero">03</span>
                        <span>Arquitectura</span>
                    </button>

                    <button
                        type="button"
                        class="ficha-nav__item"
                        data-ficha-seccion="dependencias"
                    >
                        <span class="ficha-nav__numero">04</span>
                        <span>Dependencias</span>
                    </button>

                    <button
                        type="button"
                        class="ficha-nav__item"
                        data-ficha-seccion="observaciones"
                    >
                        <span class="ficha-nav__numero">05</span>
                        <span>Observaciones</span>
                    </button>

                    <button
                        type="button"
                        class="ficha-nav__item"
                        data-ficha-seccion="historial"
                    >
                        <span class="ficha-nav__numero">06</span>
                        <span>Historial</span>
                    </button>

                </nav>

                <div class="ficha-resumen">

                    <span class="ficha-resumen__titulo">
                        Resumen
                    </span>

                    <div class="ficha-resumen__dato">
                        <small>Método</small>
                        <strong>POST</strong>
                    </div>

                    <div class="ficha-resumen__dato">
                        <small>Versión</small>
                        <strong>1.0</strong>
                    </div>

                    <div class="ficha-resumen__dato">
                        <small>Estado</small>
                        <strong>Producción</strong>
                    </div>

                </div>

            </aside>

            <main class="modal-ficha__contenido">

                <section
                    class="ficha-seccion ficha-seccion--activa"
                    data-ficha-contenido="general"
                >

                    <div class="ficha-seccion__encabezado">

                        <span class="ficha-seccion__numero">
                            01
                        </span>

                        <div>
                            <h3>Información general</h3>

                            <p>
                                Datos principales de identificación de la API.
                            </p>
                        </div>

                    </div>

                    <div class="ficha-datos">

                        <article class="ficha-dato">
                            <small>Nombre</small>
                            <strong>API de Constancias</strong>
                        </article>

                        <article class="ficha-dato">
                            <small>Proyecto</small>
                            <strong>Extorsión</strong>
                        </article>

                        <article class="ficha-dato">
                            <small>Método HTTP</small>
                            <strong>POST</strong>
                        </article>

                        <article class="ficha-dato">
                            <small>Estado</small>
                            <strong>Producción</strong>
                        </article>

                        <article class="ficha-dato ficha-dato--completo">

                            <small>Descripción</small>

                            <p>
                                Envía al usuario el enlace para consultar y
                                descargar su constancia de acreditación.
                            </p>

                        </article>

                    </div>

                </section>

                <section
                    class="ficha-seccion"
                    data-ficha-contenido="ubicacion"
                >

                    <div class="ficha-seccion__encabezado">

                        <span class="ficha-seccion__numero">
                            02
                        </span>

                        <div>
                            <h3>Ubicación</h3>

                            <p>
                                Rutas y direcciones donde se encuentra la API.
                            </p>
                        </div>

                    </div>

                    <div class="ficha-lista">

                        <div class="ficha-lista__item">
                            <small>Repositorio</small>
                            <strong>
                                https://github.com/ejemplo/extorsion
                            </strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Ruta local</small>
                            <strong>
                                C:\Laragon\www\ExtorsionF
                            </strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Servidor</small>
                            <strong>
                                https://cepyc.seguridadneza.gob.mx
                            </strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Endpoint</small>
                            <strong>
                                /api/constancia/enviar-constancia
                            </strong>
                        </div>

                    </div>

                </section>

                <section
                    class="ficha-seccion"
                    data-ficha-contenido="arquitectura"
                >

                    <div class="ficha-seccion__encabezado">

                        <span class="ficha-seccion__numero">
                            03
                        </span>

                        <div>
                            <h3>Arquitectura</h3>

                            <p>
                                Componentes internos relacionados con la API.
                            </p>
                        </div>

                    </div>

                    <div class="ficha-lista">

                        <div class="ficha-lista__item">
                            <small>Módulo</small>
                            <strong>ConstanciaAPI</strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Controlador</small>
                            <strong>ConstanciaAPI_Controller</strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Servicio</small>
                            <strong>ConstanciaCorreoService</strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Modelo</small>
                            <strong>RegistroModel</strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Vista</small>
                            <strong>correo_constancia.php</strong>
                        </div>

                    </div>

                </section>

                <section
                    class="ficha-seccion"
                    data-ficha-contenido="dependencias"
                >

                    <div class="ficha-seccion__encabezado">

                        <span class="ficha-seccion__numero">
                            04
                        </span>

                        <div>
                            <h3>Dependencias</h3>

                            <p>
                                Servicios y recursos necesarios para funcionar.
                            </p>
                        </div>

                    </div>

                    <div class="ficha-lista">

                        <div class="ficha-lista__item">
                            <small>Base de datos</small>
                            <strong>extorsion</strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Tabla</small>
                            <strong>registros</strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>SMTP</small>
                            <strong>
                                eventos-capacitaciones-cgsc@seguridadneza.gob.mx
                            </strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Configuración</small>
                            <strong>correoConstancia</strong>
                        </div>

                        <div class="ficha-lista__item">
                            <small>Autenticación</small>
                            <strong>API Token</strong>
                        </div>

                    </div>

                </section>

                <section
                    class="ficha-seccion"
                    data-ficha-contenido="observaciones"
                >

                    <div class="ficha-seccion__encabezado">

                        <span class="ficha-seccion__numero">
                            05
                        </span>

                        <div>
                            <h3>Observaciones</h3>

                            <p>
                                Consideraciones importantes para mantenimiento.
                            </p>
                        </div>

                    </div>

                    <ul class="ficha-observaciones">

                        <li>
                            Solo acepta peticiones mediante el método POST.
                        </li>

                        <li>
                            Requiere un token válido para autorizar la solicitud.
                        </li>

                        <li>
                            El correo debe estar registrado en la base de datos.
                        </li>

                        <li>
                            Utiliza el correo SMTP institucional.
                        </li>

                        <li>
                            El identificador se concatena en la URL de destino.
                        </li>

                    </ul>

                </section>

                <section
                    class="ficha-seccion"
                    data-ficha-contenido="historial"
                >

                    <div class="ficha-seccion__encabezado">

                        <span class="ficha-seccion__numero">
                            06
                        </span>

                        <div>
                            <h3>Historial</h3>

                            <p>
                                Cambios importantes realizados en la API.
                            </p>
                        </div>

                    </div>

                    <div class="ficha-historial">

                        <article class="ficha-historial__item">

                            <span>Versión 1.0</span>

                            <strong>
                                Creación inicial de la API.
                            </strong>

                            <small>
                                Julio de 2026
                            </small>

                        </article>

                        <article class="ficha-historial__item">

                            <span>Versión 1.1</span>

                            <strong>
                                Se agregaron validaciones de correo e identificador.
                            </strong>

                            <small>
                                Julio de 2026
                            </small>

                        </article>

                        <article class="ficha-historial__item">

                            <span>Versión 1.2</span>

                            <strong>
                                Se modularizó el servicio de correo.
                            </strong>

                            <small>
                                Julio de 2026
                            </small>

                        </article>

                    </div>

                </section>

            </main>

        </div>

    </section>

</div>