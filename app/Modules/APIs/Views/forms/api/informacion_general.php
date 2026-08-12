<?php

$esDetalle = ($modo ?? 'crear') === 'detalle';

?>

<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Información general
    </legend>

    <p class="form-bloque__descripcion">
        Registra los datos principales de la API.
    </p>

    <div class="form-grid">

        <div class="form-grupo">

            <label for="nueva-api-proyecto">
                Proyecto
                <span class="campo-obligatorio">*</span>
            </label>

            <select
    id="nueva-api-proyecto"
    name="id_proyecto"
    <?= $esDetalle ? 'disabled' : '' ?>
    required
>
    <option value="">
        Selecciona un proyecto
    </option>

    <?php foreach (($proyectos ?? []) as $proyecto): ?>

        <option
            value="<?= esc(
                $proyecto['id_proyecto'] ?? '',
                'attr'
            ) ?>"
        >
            <?= esc(
                $proyecto['nombre']
                ?? 'Proyecto sin nombre'
            ) ?>
        </option>

    <?php endforeach; ?>
</select>

        </div>

        <div class="form-grupo">

            <label for="nueva-api-sistema">
                Sistema asociado
            </label>

            <select
                id="nueva-api-sistema"
                name="id_sistema"
                <?= $esDetalle ? 'disabled' : '' ?>
            >
                <option value="">
                    Sin sistema asociado
                </option>
            </select>

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="nueva-api-nombre">
                Nombre de la API
                <span class="campo-obligatorio">*</span>
            </label>

            <input
                type="text"
                id="nueva-api-nombre"
                name="nombre"
                value="<?= esc($api['nombre'] ?? '', 'attr') ?>"
                placeholder="Ej. API de Invitaciones"
                <?= $esDetalle ? 'readonly' : '' ?>
                required
            >

        </div>

        <div class="form-grupo">

            <label for="nueva-api-estado">
                Estado
                <span class="campo-obligatorio">*</span>
            </label>

            <select
                id="nueva-api-estado"
                name="estado"
                <?= $esDetalle ? 'disabled' : '' ?>
                required
            >
                <option value="">
                    Selecciona una opción
                </option>

                <option value="Producción">
                    Producción
                </option>

                <option value="Desarrollo">
                    Desarrollo
                </option>

                <option value="Detenido">
                    Detenido
                </option>

                <option value="Mantenimiento">
                    Mantenimiento
                </option>
            </select>

        </div>

        <div class="form-grupo">

            <label for="nueva-api-metodo">
                Método
                <span class="campo-obligatorio">*</span>
            </label>

            <select
                id="nueva-api-metodo"
                name="metodo"
                <?= $esDetalle ? 'disabled' : '' ?>
                required
            >
                <option value="">
                    Selecciona una opción
                </option>

                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="PATCH">PATCH</option>
                <option value="DELETE">DELETE</option>
            </select>

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="nueva-api-descripcion">
                Descripción
            </label>

            <textarea
                id="nueva-api-descripcion"
                name="descripcion"
                rows="4"
                <?= $esDetalle ? 'readonly' : '' ?>
            ><?= esc($api['descripcion'] ?? '') ?></textarea>

        </div>

    </div>

</fieldset>