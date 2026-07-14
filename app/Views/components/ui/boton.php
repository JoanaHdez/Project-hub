<?php

$texto   = $texto ?? 'Acción';
$url     = $url ?? '#';
$tipo    = $tipo ?? 'primario';
$icono   = $icono ?? null;
$modalId = $modalId ?? null;

?>

<a
    href="<?= esc($url, 'attr') ?>"
    class="boton boton--<?= esc($tipo, 'attr') ?>"
    <?php if ($modalId): ?>
        data-modal-abrir="<?= esc($modalId, 'attr') ?>"
    <?php endif; ?>
>
    <?php if ($icono): ?>
        <span class="boton__icono">
            <?= esc($icono) ?>
        </span>
    <?php endif; ?>

    <span><?= esc($texto) ?></span>
</a>