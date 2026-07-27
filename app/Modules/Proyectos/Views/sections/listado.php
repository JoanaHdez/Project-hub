<?php

$proyectos = $proyectos ?? [];

$columnas = [
    'Proyecto',
    'Estado',
    'Origen',
    'Sistemas registrados',
    'Fecha de creación',
    'Acciones',
];

$totalProyectos = count($proyectos);
?>

<section class="proyectos-seccion">

    <div class="proyectos-seccion__encabezado">
        <h3>Listado de proyectos</h3>

        <p>
            Consulta y administra los proyectos disponibles.
        </p>
    </div>

    <?= view('components/ui/barra_herramientas', [
        'idBusqueda'     => 'buscar-proyecto',
        'nombreBusqueda' => 'buscar_proyecto',
        'placeholder'    => 'Buscar proyecto...',
        'tablaObjetivo'  => 'tabla-proyectos',
    ]) ?>

    <div class="tabla-componente">

        <div class="tabla-contenedor">

            <table id="tabla-proyectos" class="tabla">

                <thead>
                    <tr>
                        <?php foreach ($columnas as $columna): ?>
                        <th>
                            <?= esc($columna) ?>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($proyectos)): ?>

                    <?= view('components/ui/tabla_vacia', [
            'icono'       => '📁',
            'titulo'      => 'Aún no hay proyectos registrados',
            'descripcion' => 'Los proyectos aparecerán cuando exista información.',
            'columnas'    => count($columnas),
        ]) ?>

                    <?php else: ?>

                    <?php foreach ($proyectos as $proyectoFila): ?>

                    <?= view('components/ui/fila_proyecto', [
                'proyecto' => $proyectoFila,
            ], [
                'saveData' => false,
            ]) ?>

                    <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

        <?php if ($totalProyectos > 0): ?>

        <div class="tabla-pie">

            <p class="tabla-pie__contador">
                Mostrando
                <strong>1</strong>
                a
                <strong><?= esc($totalProyectos) ?></strong>
                de
                <strong><?= esc($totalProyectos) ?></strong>
                registros
            </p>

            <nav class="tabla-paginacion" aria-label="Paginación de registros">

                <button type="button" class="tabla-paginacion__boton" disabled aria-label="Página anterior">
                    ‹
                </button>

                <button type="button" class="tabla-paginacion__boton tabla-paginacion__boton--activo"
                    aria-current="page">
                    1
                </button>

                <button type="button" class="tabla-paginacion__boton" disabled aria-label="Página siguiente">
                    ›
                </button>

            </nav>

        </div>

        <?php endif; ?>

    </div>

</section>