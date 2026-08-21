<?php

$modo = $modo ?? 'crear';

$esDetalle = $modo === 'detalle';
$esEdicion = $modo === 'editar';
$esCreacion = $modo === 'crear';
?>

<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Configuración técnica
    </legend>

    <p class="form-bloque__descripcion">
        Selecciona la especificación técnica que utilizará este proyecto.
    </p>

    <div class="form-grid">

        <div class="form-grupo form-grupo--completo">

            <label for="especificacion-tecnica">
                Especificación técnica
            </label>

            <div class="configuracion-tecnica">

                <select id="especificacion-tecnica" name="id_especificacion" <?= $esDetalle ? 'disabled' : '' ?>>
                    <option value="">
    Selecciona una ficha técnica
</option>

<?php foreach ($especificaciones as $especificacion): ?>

    <?php

    $idEspecificacion =
        (string) (
            $especificacion['id_especificacion']
            ?? ''
        );

    $seleccionada =
        (string) (
            $proyecto['id_especificacion']
            ?? ''
        ) === $idEspecificacion;

    ?>

    <option
        value="<?= esc(
            $idEspecificacion,
            'attr'
        ) ?>"
        <?= $seleccionada
            ? 'selected'
            : ''
        ?>
    >
        <?= esc(
            $especificacion['codigo']
            ?? 'Ficha técnica'
        ) ?>
    </option>

<?php endforeach; ?>

                </select>

                <div class="configuracion-tecnica__acciones">

                    <?= view('components/ui/boton', [
                        'texto' => 'Ver ficha',
                        'url'   => '#',
                        'tipo'  => 'secundario',
                        'modalId'  => 'modal-ficha-tecnica',
                    ]) ?>

                    <?php if ($esCreacion): ?>

                        <?= view('components/ui/boton', [
                            'texto'   => 'Nueva',
                            'tipo'    => 'primario',
                            'icono'   => '',
                            'url'     => '#',
                            'modalId' => 'modal-nueva-ficha-tecnica',
                        ]) ?>

                    <?php else: ?>

                        <button
                            type="button"
                            class="boton boton--primario"
                            disabled
                            aria-disabled="true"
                            title="Solo es posible crear una nueva ficha al registrar un proyecto.">
                            <span class="boton__icono">+</span>
                            <span>Nueva</span>
                        </button>

                    <?php endif; ?>

                </div>

            </div>

            <small class="form-ayuda">
                Próximamente este apartado se conectará con el módulo de
                Especificaciones Técnicas.
            </small>

        </div>

    </div>

</fieldset>