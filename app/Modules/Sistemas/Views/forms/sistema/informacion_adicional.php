<?php

$esDetalle = ($modo ?? 'crear') === 'detalle';

?>

<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Información adicional
    </legend>

    <p class="form-bloque__descripcion">
        Agrega información complementaria para la administración del sistema.
    </p>

    <div class="form-grid">

        <div class="form-grupo">

            <label for="sistema-responsable">
                Responsable
                <span class="campo-obligatorio">*</span>
            </label>

            <input
                type="text"
                id="sistema-responsable"
                name="responsable"
                value="<?= esc($sistema['responsable'] ?? '', 'attr') ?>"
                placeholder="Nombre de la persona responsable"
                <?= $esDetalle ? 'readonly' : '' ?>
                required
            >

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="sistema-observaciones">
                Observaciones
            </label>

            <textarea
                id="sistema-observaciones"
                name="observaciones"
                rows="4"
                <?= $esDetalle ? 'readonly' : '' ?>
            ><?= esc($sistema['observaciones'] ?? '') ?></textarea>

        </div>

    </div>

</fieldset>