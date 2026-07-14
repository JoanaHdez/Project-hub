<?php
$texto = $texto ?? 'Sin estado';
$tipo  = $tipo ?? 'neutral';
?>

<span class="estado estado--<?= esc($tipo) ?>">
    <?= esc($texto) ?>
</span>

