<?php

$esDetalle = ($modo ?? 'crear') === 'detalle';

?>

<fieldset class="form-bloque">
    <legend class="form-bloque__titulo">
        Información general
    </legend>

    <p class="form-bloque__descripcion">
        Registra los datos principales para identificar el proyecto.
    </p>

    <div class="form-grid">

        <div class="form-grupo form-grupo--completo">
            <label for="proyecto-nombre">
                Nombre del proyecto
            </label>

            <input type="text" id="proyecto-nombre" name="nombre" value="<?= esc($proyecto['nombre'] ?? '', 'attr') ?>"
                placeholder="Ej. Sistema de Eventos" maxlength="150"
                <?= $esDetalle ? 'readonly' : '' ?>>
        </div>

        <div class="form-grupo">
            <label for="proyecto-estado">
                Estado
            </label>

            <select id="proyecto-estado" name="estado" <?= $esDetalle ? 'disabled' : '' ?>>
                <option value="">Selecciona una opción</option>

                <?php foreach (['Producción', 'Desarrollo', 'Detenido', 'Mantenimiento'] as $estado): ?>
                <option value="<?= esc($estado, 'attr') ?>"
                    <?= ($proyecto['estado'] ?? '') === $estado ? 'selected' : '' ?>>
                    <?= esc($estado) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-grupo">
            <label for="proyecto-origen">
                Origen
            </label>

            <select id="proyecto-origen" name="origen" <?= $esDetalle ? 'disabled' : '' ?>>
                <option value="">Selecciona una opción</option>

                <?php foreach (['Trabajo', 'Personal', 'Práctica', 'Otro'] as $origen): ?>
                <option value="<?= esc($origen, 'attr') ?>"
                    <?= ($proyecto['origen'] ?? '') === $origen ? 'selected' : '' ?>>
                    <?= esc($origen) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-grupo form-grupo--completo">
            <label for="proyecto-descripcion">
                Descripción
            </label>

            <textarea id="proyecto-descripcion" name="descripcion" rows="4"
                placeholder="Describe brevemente el propósito y alcance del proyecto" <?= $esDetalle ? 'readonly' : '' ?>>
                <?= esc($proyecto['descripcion'] ?? '') ?></textarea>
        </div>

    </div>
</fieldset>