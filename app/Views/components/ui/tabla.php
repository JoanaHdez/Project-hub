<?php

$columnas = $columnas ?? [];
$filas = $filas ?? [];
$mensajeVacio = $mensajeVacio ?? 'No hay registros disponibles.';
$descripcionVacia = $descripcionVacia ?? '';
$iconoVacio = $iconoVacio ?? '📂';
$claseAdicional = $claseAdicional ?? '';
$idTabla = $idTabla ?? 'tabla-registros';

$totalRegistros = $totalRegistros ?? count($filas);
$paginaActual = $paginaActual ?? 1;
$totalPaginas = $totalPaginas ?? 1;
$inicioRegistro = $inicioRegistro ?? ($totalRegistros > 0 ? 1 : 0);
$finRegistro = $finRegistro ?? $totalRegistros;

$totalColumnas = count($columnas);

?>

<div class="tabla-componente">

    <div class="tabla-contenedor <?= esc($claseAdicional, 'attr') ?>">

        <table
            id="<?= esc($idTabla, 'attr') ?>"
            class="tabla">

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

                <?php if (empty($filas)): ?>

                    <?= view('components/ui/tabla_vacia', [
                        'icono'       => $iconoVacio,
                        'titulo'      => $mensajeVacio,
                        'descripcion' => $descripcionVacia,
                        'columnas'    => $totalColumnas,
                    ]) ?>

                <?php else: ?>

                    <?php foreach ($filas as $fila): ?>
                        <tr>

                            <?php foreach ($fila as $celda): ?>
                                <td>
                                    <?= $celda ?>
                                </td>
                            <?php endforeach; ?>

                        </tr>
                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <?php if ($totalRegistros > 0): ?>

        <div class="tabla-pie">

            <p class="tabla-pie__contador">
                Mostrando
                <strong><?= esc($inicioRegistro) ?></strong>
                a
                <strong><?= esc($finRegistro) ?></strong>
                de
                <strong><?= esc($totalRegistros) ?></strong>
                registros
            </p>

            <nav
                class="tabla-paginacion"
                aria-label="Paginación de registros">
                <button
                    type="button"
                    class="tabla-paginacion__boton"
                    <?= $paginaActual <= 1 ? 'disabled' : '' ?>
                    aria-label="Página anterior">
                    ‹
                </button>

                <?php for ($pagina = 1; $pagina <= $totalPaginas; $pagina++): ?>

                    <button
                        type="button"
                        class="tabla-paginacion__boton
                            <?= $pagina === $paginaActual
                                ? 'tabla-paginacion__boton--activo'
                                : '' ?>"
                        aria-current="<?= $pagina === $paginaActual ? 'page' : 'false' ?>">
                        <?= esc($pagina) ?>
                    </button>

                <?php endfor; ?>

                <button
                    type="button"
                    class="tabla-paginacion__boton"
                    <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>
                    aria-label="Página siguiente">
                    ›
                </button>
            </nav>

        </div>

    <?php endif; ?>

</div>