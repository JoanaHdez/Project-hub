<?php

$nombre       = $nombre ?? 'Proyecto';
$estado       = $estado ?? 'Sin estado';
$tipoEstado   = $tipoEstado ?? 'neutral';
$codigoEt     = $codigoEt ?? 'Sin especificación';
$urlFicha     = $urlFicha ?? '#';
$modalFichaId = $modalFichaId ?? null;

?>

<article class="tarjeta-proyecto">
    <div class="tarjeta-proyecto__encabezado">
        <h4><?= esc($nombre) ?></h4>

        <?= view('components/ui/estado', [
            'texto' => $estado,
            'tipo'  => $tipoEstado,
        ], [
            'saveData' => false,
        ]) ?>
    </div>

    <div class="tarjeta-proyecto__contenido">
        <span class="tarjeta-proyecto__et">
            <?= esc($codigoEt) ?>
        </span>

        <a
            href="<?= esc($urlFicha, 'attr') ?>"
            class="tarjeta-proyecto__enlace"
            <?php if ($modalFichaId): ?>
                data-modal-abrir="<?= esc($modalFichaId, 'attr') ?>"
            <?php endif; ?>
        >
            Ver ficha técnica
        </a>
    </div>
</article>