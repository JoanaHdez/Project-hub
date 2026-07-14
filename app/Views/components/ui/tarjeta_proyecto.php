<?php

$nombre = $nombre ?? 'Proyecto';
$estado = $estado ?? 'Sin estado';
$tipoEstado = $tipoEstado ?? 'neutral';
$codigoEt = $codigoEt ?? 'Sin especificación';
$urlFicha = $urlFicha ?? '#';

?>

<article class="tarjeta-proyecto">
    <div class="tarjeta-proyecto__encabezado">
        <h4><?= esc($nombre) ?></h4>

        <?= view('components/ui/estado', [
            'texto' => $estado,
            'tipo'  => $tipoEstado,
        ]) ?>
    </div>

    <div class="tarjeta-proyecto__contenido">
        <span class="tarjeta-proyecto__et">
            <?= esc($codigoEt) ?>
        </span>

        <a href="<?= esc($urlFicha) ?>" class="tarjeta-proyecto__enlace">
            Ver ficha técnica
        </a>
    </div>
</article>