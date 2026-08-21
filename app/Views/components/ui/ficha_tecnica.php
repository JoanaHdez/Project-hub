<?php

$codigo = $codigo ?? 'Sin código';
$datos = $datos ?? [];

$mapaCampos = [
    'Framework' => 'framework',
    'Versión del framework' => 'version-framework',
    'PHP' => 'php',
    'Base de datos' => 'base-datos',
    'Repositorio' => 'repositorio',
    'Entorno local' => 'entorno-local',
];

?>

<section class="ficha-tecnica">

    <header class="ficha-tecnica__encabezado">

        <span
            class="ficha-tecnica__codigo"
            data-ficha-codigo
        >
            <?= esc($codigo) ?>
        </span>

        <p>
            Configuración tecnológica asociada al proyecto.
        </p>

    </header>

    <dl class="ficha-tecnica__datos">

        <?php foreach ($datos as $etiqueta => $valor): ?>

            <?php

            $campo =
                $mapaCampos[$etiqueta]
                ?? null;

            ?>

            <div class="ficha-tecnica__fila">

                <dt>
                    <?= esc($etiqueta) ?>
                </dt>

                <dd
                    <?= $campo
                        ? 'data-ficha-' . esc($campo, 'attr')
                        : ''
                    ?>
                >
                    <?= esc(
                        $valor
                        ?: 'No disponible'
                    ) ?>
                </dd>

            </div>

        <?php endforeach; ?>

    </dl>

</section>