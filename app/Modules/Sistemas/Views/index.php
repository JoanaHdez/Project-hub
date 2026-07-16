<?= $this->extend('layouts/head') ?>

<?= $this->section('content') ?>

<section class="sistemas">

    <header class="encabezado-modulo">
        <div class="encabezado-modulo__contenido">
            <h2>Sistemas</h2>

            <p>
                Selecciona un sistema para visualizarlo dentro de Project Hub.
            </p>
        </div>
    </header>

    <div class="sistemas-integrador">

        <aside class="sistemas-catalogo">

            <div class="sistemas-catalogo__encabezado">
                <h3>Catálogo</h3>

                <input type="search" id="buscar-sistema" placeholder="Buscar sistema...">
            </div>

            <div class="sistemas-catalogo__lista">

                <?php foreach ($sistemas as $sistema): ?>

                <button type="button" class="sistema-selector"
                    data-sistema-nombre="<?= esc($sistema['nombre'], 'attr') ?>"
                    data-sistema-url="<?= esc($sistema['url'], 'attr') ?>">
                    <span class="sistema-selector__nombre">
                        <?= esc($sistema['nombre']) ?>
                    </span>

                    <span class="sistema-selector__proyecto">
                        <?= esc($sistema['proyecto']) ?>
                    </span>

                    <?= view('components/ui/estado', [
                            'texto' => $sistema['estado'],
                            'tipo'  => strtolower($sistema['estado']) === 'producción'
                                ? 'produccion'
                                : 'desarrollo',
                        ], [
                            'saveData' => false,
                        ]) ?>
                </button>

                <?php endforeach; ?>

            </div>

        </aside>

        <section class="sistemas-visor">

            <header class="sistemas-visor__encabezado">
                <div>
                    <span>Sistema seleccionado</span>

                    <h3 id="visor-sistema-nombre">
                        Ningún sistema seleccionado
                    </h3>
                </div>

                <div class="sistemas-visor__acciones">
                    <button type="button" id="recargar-sistema" class="boton boton--secundario" disabled>
                        Recargar
                    </button>

                    <button type="button" class="boton boton--secundario" id="btn-ficha-ubicacion"
                        data-modal-abrir="modal-ficha-ubicacion" disabled>
                        Ficha de ubicación
                    </button>

                    <a href="#" id="abrir-sistema-externo" class="boton boton--primario" target="_blank"
                        rel="noopener noreferrer" aria-disabled="true">
                        Abrir en otra pestaña
                    </a>
                </div>
            </header>

            <div class="sistemas-visor__contenido">

                <div id="visor-sistema-vacio" class="sistemas-visor__vacio">
                    <span>🖥️</span>

                    <strong>Selecciona un sistema</strong>

                    <p>
                        El sistema elegido se visualizará en este espacio.
                    </p>
                </div>

                <div id="visor-sistema-cargando" class="sistemas-visor__cargando" hidden>
                    <span>⏳</span>
                    <strong>Cargando sistema...</strong>
                </div>

                <iframe id="visor-sistema-frame" class="sistemas-visor__iframe"
                    title="Visualización del sistema seleccionado" hidden></iframe>

            </div>

        </section>

    </div>

</section>

<?= view('App\Modules\Sistemas\Views\modals\ficha_ubicacion') ?>

<?= $this->endSection() ?>