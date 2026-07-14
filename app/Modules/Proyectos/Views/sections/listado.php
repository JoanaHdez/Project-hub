<section class="proyectos-seccion">
    <div class="proyectos-seccion__encabezado">
        <h3>Listado de proyectos</h3>

        <p>
            Consulta y administra los proyectos disponibles.
        </p>
    </div>

    <div class="proyectos-filtros">
        <label for="buscar-proyecto" class="sr-only">
            Buscar proyecto
        </label>

        <input
            type="search"
            id="buscar-proyecto"
            name="buscar_proyecto"
            placeholder="Buscar proyecto..."
        >
    </div>

    <div class="tabla-contenedor">
        <table class="tabla">
            <thead>
                <tr>
                    <th>Proyecto</th>
                    <th>Estado</th>
                    <th>Responsable</th>
                    <th>Especificación técnica</th>
                    <th>Última actualización</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
    <tr>
        <td>Extorsión</td>

        <td>
            <?= view('components/ui/estado', [
                'texto' => 'Producción',
                'tipo'  => 'produccion',
            ]) ?>
        </td>

        <td>Joana Herrera</td>

        <td>ET-01</td>

        <td>13/07/2026</td>

        <td>
            <div class="acciones-proyecto">

                <?= view('components/ui/boton_accion', [
                    'icono'  => '✏️',
                    'mensaje' => 'Editar proyecto',
                    'url'     => '#',
                    'tipo'    => 'editar',
                ]) ?>

                <?= view('components/ui/boton_accion', [
                    'icono'  => '🗑️',
                    'mensaje' => 'Eliminar proyecto',
                    'url'     => '#',
                    'tipo'    => 'eliminar',
                ]) ?>

                <?= view('components/ui/boton_accion', [
                    'icono'         => '🌐',
                    'mensaje'       => 'Abrir sistema',
                    'url'           => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/',
                    'tipo'          => 'sistema',
                    'nuevaPestana'  => true,
                ]) ?>

                <?= view('components/ui/boton_accion', [
                    'icono'         => '🐙',
                    'mensaje'       => 'Abrir repositorio',
                    'url'           => '#',
                    'tipo'          => 'repositorio',
                    'nuevaPestana'  => true,
                ]) ?>

                <?= view('components/ui/boton_accion', [
                    'icono'  => '📄',
                    'mensaje' => 'Ver ficha técnica',
                    'url'     => '#',
                    'tipo'    => 'ficha',
                ]) ?>

            </div>
        </td>
    </tr>
</tbody>
        </table>
    </div>
</section>