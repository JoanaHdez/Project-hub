<?php

$titulo = $titulo ?? '';
$descripcion = $descripcion ?? '';
$contenido = $contenido ?? '';

?>

<section class="documentacion-card">

    <div class="documentacion-card__encabezado">

        <h3>
            <?= esc($titulo) ?>
        </h3>

        <?php if ($descripcion !== ''): ?>

            <p>
                <?= esc($descripcion) ?>
            </p>

        <?php endif; ?>

    </div>

    <div class="documentacion-card__contenido">

        <?= $contenido ?>

    </div>

</section>