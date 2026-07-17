<?php

$titulo = $titulo ?? '';
$subtitulo = $subtitulo ?? '';
$estado = $estado ?? '';

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

    <div class="selector__contenido">

        <strong class="selector__titulo">
            <?= esc($titulo) ?>
        </strong>

        <?php if ($subtitulo !== ''): ?>

            <span class="selector__subtitulo">
                <?= esc($subtitulo) ?>
            </span>

        <?php endif; ?>

    </div>

    <?php

$badgeClase = $badgeClase ?? 'badge';

?>

<?php if ($estado !== ''): ?>

    <span class="<?= esc($badgeClase) ?>">
        <?= esc($estado) ?>
    </span>

<?php endif; ?>

</button>