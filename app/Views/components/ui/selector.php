<?php

$titulo = $titulo ?? '';
$subtitulo = $subtitulo ?? '';
$estado = $estado ?? '';

$badgeClase = $badgeClase ?? 'badge';

$atributos = $atributos ?? [];

$clases = 'selector';

if (!empty($atributos['class'])) {
    $clases .= ' ' . $atributos['class'];
    unset($atributos['class']);
}

?>

<button
    type="button"
    class="<?= esc($clases) ?>"
    <?php foreach ($atributos as $nombre => $valor): ?>
        <?= esc($nombre, 'attr') ?>="<?= esc($valor, 'attr') ?>"
    <?php endforeach; ?>
>

    <div class="selector__informacion">

        <strong class="selector__titulo">
            <?= esc($titulo) ?>
        </strong>

        <?php if ($subtitulo !== ''): ?>
            <span class="selector__subtitulo">
                <?= esc($subtitulo) ?>
            </span>
        <?php endif; ?>

    </div>

    <?php if ($estado !== ''): ?>

        <div class="selector__estado">

            <span class="<?= esc($badgeClase) ?>">
                <?= esc($estado) ?>
            </span>

        </div>

    <?php endif; ?>

</button>