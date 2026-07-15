<section class="proyectos-seccion">
    <div class="proyectos-seccion__encabezado">
        <h3>Proyectos recientes</h3>

        <p>
            Consulta los últimos proyectos registrados en Project Hub.
        </p>
    </div>

    <div class="proyectos-destacados">

        <?= view('components/ui/tarjeta_proyecto', [
            'nombre'     => 'Extorsión',
            'estado'     => 'Producción',
            'tipoEstado' => 'produccion',
            'codigoEt'   => 'ET-01',
            'urlFicha'   => '#',
            'modalFichaId' => 'modal-ficha-tecnica',
        ]) ?>

        <?= view('components/ui/tarjeta_proyecto', [
            'nombre'     => 'Eventos',
            'estado'     => 'Producción',
            'tipoEstado' => 'produccion',
            'codigoEt'   => 'ET-01',
            'urlFicha'   => '#',
            'modalFichaId' => 'modal-ficha-tecnica',
        ]) ?>

        <?= view('components/ui/tarjeta_proyecto', [
            'nombre'     => 'Project Hub',
            'estado'     => 'Desarrollo',
            'tipoEstado' => 'desarrollo',
            'codigoEt'   => 'ET-01',
            'urlFicha'   => '#',
            'modalFichaId' => 'modal-ficha-tecnica',
        ]) ?>

        <?= view('components/ui/tarjeta_proyecto', [
            'nombre'     => 'Nuevo proyecto',
            'estado'     => 'Detenido',
            'tipoEstado' => 'detenido',
            'codigoEt'   => 'ET-02',
            'urlFicha'   => '#',
            'modalFichaId' => 'modal-ficha-tecnica',
        ]) ?>

    </div>
</section>