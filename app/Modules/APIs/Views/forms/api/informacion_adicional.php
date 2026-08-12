<?php

$esDetalle = ($modo ?? 'crear') === 'detalle';

?>

<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Información adicional
    </legend>

    <div class="form-grid">

        <div class="form-grupo">

            <label for="api-responsable">
                Responsable
            </label>

            <input
                type="text"
                id="api-responsable"
                name="responsable"
                value="<?= esc($api['responsable'] ?? '', 'attr') ?>"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="api-observaciones">
                Observaciones
            </label>

            <textarea
                id="api-observaciones"
                name="observaciones"
                rows="4"
                <?= $esDetalle ? 'readonly' : '' ?>
            ><?= esc($api['observaciones'] ?? '') ?></textarea>

        </div>

    </div>

</fieldset>