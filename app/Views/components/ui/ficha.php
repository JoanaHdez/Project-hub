<?php

$titulo = $titulo ?? '';
$campos = $campos ?? [];

?>

<section class="ficha">

    <?php if ($titulo !== ''): ?>
        <h3 class="ficha__titulo">
            <?= esc($titulo) ?>
        </h3>
    <?php endif; ?>

    <div class="ficha__contenido">

        <?php foreach ($campos as $campo): ?>

            <div class="ficha__item">

                <span class="ficha__etiqueta">
                    <?= esc($campo['etiqueta'] ?? '') ?>
                </span>

                <strong
                    class="ficha__valor"
                    <?= isset($campo['id'])
                        ? 'id="' . esc($campo['id'], 'attr') . '"'
                        : ''
                    ?>
                >
                    <?= esc($campo['valor'] ?? '—') ?>
                </strong>

            </div>

        <?php endforeach; ?>

    </div>

</section>