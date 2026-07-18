<?php

$titulo      = $titulo ?? '';
$descripcion = $descripcion ?? '';
$accion      = $accion ?? null;

?>

<section class="encabezado-modulo">
    <div class="encabezado-modulo__contenido">
        <h2><?= esc($titulo) ?></h2>

        <?php if ($descripcion): ?>
            <p><?= esc($descripcion) ?></p>
        <?php endif; ?>
    </div>

    <?php if ($accion): ?>
        <div class="encabezado-modulo__accion">
            <?= $accion ?>
        </div>
    <?php endif; ?>
</section>

