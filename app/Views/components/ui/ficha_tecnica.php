<?php

$codigo = $codigo ?? 'Sin código';
$datos = $datos ?? [];

?>

<section class="ficha-tecnica">

    <header class="ficha-tecnica__encabezado">
        <span class="ficha-tecnica__codigo">
            <?= esc($codigo) ?>
        </span>

        <p>
            Configuración tecnológica asociada al proyecto.
        </p>
    </header>

    <dl class="ficha-tecnica__datos">

        <?php foreach ($datos as $etiqueta => $valor): ?>

            <div class="ficha-tecnica__fila">
                <dt><?= esc($etiqueta) ?></dt>

                <dd>
                    <?= esc($valor ?: 'No disponible') ?>
                </dd>
            </div>

        <?php endforeach; ?>

    </dl>

</section>