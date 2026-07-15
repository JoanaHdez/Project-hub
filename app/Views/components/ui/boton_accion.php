<?php

$icono         = $icono ?? '';
$mensaje       = $mensaje ?? 'Acción';
$url           = $url ?? '#';
$tipo          = $tipo ?? 'neutral';
$nuevaPestana  = $nuevaPestana ?? false;
$modalId       = $modalId ?? null;

?>

<a
    href="<?= esc($url) ?>"
    class="boton-accion boton-accion--<?= esc($tipo) ?>"
    aria-label="<?= esc($mensaje) ?>"

    <?php if ($modalId): ?>
        data-modal-abrir="<?= esc($modalId) ?>"
    <?php endif; ?>

    <?= $nuevaPestana ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
>

    <span class="boton-accion__icono">
        <?= esc($icono) ?>
    </span>

    <span class="boton-accion__mensaje">
        <?= esc($mensaje) ?>
    </span>

</a>