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

