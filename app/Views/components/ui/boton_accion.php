<?php

$accion = $accion ?? null;
$proyectoId = $proyectoId ?? null;
$icono         = $icono ?? '';
$mensaje       = $mensaje ?? 'Acción';
$url           = $url ?? '#';
$tipo          = $tipo ?? 'neutral';
$nuevaPestana  = $nuevaPestana ?? false;
$modalId       = $modalId ?? null;
$proyectoId    = $proyectoId ?? null;

?>

<a
    href="<?= esc($url) ?>"
    class="boton-accion boton-accion--<?= esc($tipo) ?>"
    aria-label="<?= esc($mensaje) ?>"

    <?php if ($accion): ?>
        data-accion="<?= esc($accion) ?>"
    <?php endif; ?>

    <?php if ($modalId): ?>
        data-modal-abrir="<?= esc($modalId) ?>"
    <?php endif; ?>

    <?php if ($proyectoId): ?>
        data-proyecto-id="<?= esc($proyectoId) ?>"
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