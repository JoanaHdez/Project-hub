<?php

$titulo   = $titulo ?? '';
$proyecto = $proyecto ?? '';
$estado   = $estado ?? '';
$metodo   = $metodo ?? '';

$atributos = $atributos ?? [];

$clases = 'api-selector';

if (!empty($atributos['class'])) {
    $clases .= ' ' . $atributos['class'];
    unset($atributos['class']);
}

$metodoClase = match (strtoupper($metodo)) {
    'GET'    => 'api-selector__metodo--get',
    'POST'   => 'api-selector__metodo--post',
    'PUT'    => 'api-selector__metodo--put',
    'PATCH'  => 'api-selector__metodo--patch',
    'DELETE' => 'api-selector__metodo--delete',
    default  => 'api-selector__metodo--default',
};

?>

<button
    type="button"
    class="<?= esc($clases, 'attr') ?>"
    <?php foreach ($atributos as $nombre => $valor): ?>
        <?= esc($nombre, 'attr') ?>="<?= esc($valor, 'attr') ?>"
    <?php endforeach; ?>
>

    <div class="api-selector__encabezado">

        <strong class="api-selector__titulo">
            <?= esc($titulo) ?>
        </strong>

        <?php if ($metodo !== ''): ?>
            <span class="api-selector__metodo <?= esc($metodoClase, 'attr') ?>">
                <?= esc(strtoupper($metodo)) ?>
            </span>
        <?php endif; ?>

    </div>

    <div class="api-selector__pie">

        <span class="api-selector__proyecto">
            <?= esc($proyecto) ?>
        </span>

        <?php if ($estado !== ''): ?>
            <span class="badge badge--success">
                <?= esc($estado) ?>
            </span>
        <?php endif; ?>

    </div>

</button>