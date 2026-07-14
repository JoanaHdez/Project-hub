<?php

$icono   = $icono ?? '';
$mensaje = $mensaje ?? 'Acción';
$url     = $url ?? '#';
$tipo    = $tipo ?? 'neutral';
$nuevaPestana = $nuevaPestana ?? false;

?>

<a
    href="<?= esc($url) ?>"
    class="boton-accion boton-accion--<?= esc($tipo) ?>"
    aria-label="<?= esc($mensaje) ?>"
    <?= $nuevaPestana ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
>
    <span class="boton-accion__icono">
        <?= esc($icono) ?>
    </span>

    <span class="boton-accion__mensaje">
        <?= esc($mensaje) ?>
    </span>
</a>