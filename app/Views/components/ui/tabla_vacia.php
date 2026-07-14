<?php
$icono      = $icono ?? '📂';
$titulo     = $titulo ?? 'No hay registros';
$descripcion = $descripcion ?? '';
$columnas   = $columnas ?? 1;
?>

<tr>
    <td colspan="<?= esc($columnas) ?>" class="tabla-vacia">
        <div class="tabla-vacia__contenido">
            <span class="tabla-vacia__icono">
                <?= esc($icono) ?>
            </span>

            <strong><?= esc($titulo) ?></strong>

            <?php if ($descripcion): ?>
                <p><?= esc($descripcion) ?></p>
            <?php endif; ?>
        </div>
    </td>
</tr>

<!-- En el listado de Proyectos reemplaza esto: -->

<!-- <tr>
    <td colspan="6" class="tabla__vacio">
        Aún no hay proyectos registrados.
    </td>
</tr> -->

<!-- Por esto: -->

<!-- <?= view('components/ui/tabla_vacia', [
    'icono' => '📁',
    'titulo' => 'Aún no hay proyectos registrados',
    'descripcion' => 'Los proyectos aparecerán en esta tabla cuando exista información.',
    'columnas' => 6,
]) ?> -->