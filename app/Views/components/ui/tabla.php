<?php

$columnas = $columnas ?? [];
$filas = $filas ?? [];
$mensajeVacio = $mensajeVacio ?? 'No hay registros disponibles.';
$descripcionVacia = $descripcionVacia ?? '';
$iconoVacio = $iconoVacio ?? '📂';
$claseAdicional = $claseAdicional ?? '';

$totalColumnas = count($columnas);

?>

<div class="tabla-contenedor <?= esc($claseAdicional, 'attr') ?>">

    <table class="tabla">

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
                    'icono'      => $iconoVacio,
                    'titulo'     => $mensajeVacio,
                    'descripcion' => $descripcionVacia,
                    'columnas'   => $totalColumnas,
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