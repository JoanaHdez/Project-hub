<?php

$esDetalle = ($modo ?? 'crear') === 'detalle';

?>

<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Información general
    </legend>

    <p class="form-bloque__descripcion">
        Registra los datos principales para identificar el sistema.
    </p>

    <div class="form-grid">

        <div class="form-grupo form-grupo--completo">

            <label for="sistema-proyecto">
                Proyecto
            </label>

            <input
                type="text"
                id="sistema-proyecto"
                name="proyecto_nombre"
                value="<?= esc($sistema['proyecto_nombre'] ?? '', 'attr') ?>"
                readonly
            >

            <small class="form-ayuda">
                El sistema quedará asociado automáticamente a este proyecto.
            </small>

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="sistema-nombre">
                Nombre del sistema
                <span class="campo-obligatorio">*</span>
            </label>

            <input
                type="text"
                id="sistema-nombre"
                name="nombre"
                value="<?= esc($sistema['nombre'] ?? '', 'attr') ?>"
                maxlength="150"
                placeholder="Ej. Sistema de Reportes"
                <?= $esDetalle ? 'readonly' : '' ?>
                required
            >

        </div>

        <div class="form-grupo">

            <label for="sistema-estado">
                Estado
                <span class="campo-obligatorio">*</span>
            </label>

            <select
                id="sistema-estado"
                name="estado"
                <?= $esDetalle ? 'disabled' : '' ?>
                required
            >
                <option value="">
                    Selecciona una opción
                </option>

                <?php foreach (
                    ['Producción', 'Desarrollo', 'Detenido', 'Mantenimiento']
                    as $estado
                ): ?>

                    <option
                        value="<?= esc($estado, 'attr') ?>"
                        <?= ($sistema['estado'] ?? '') === $estado
                            ? 'selected'
                            : '' ?>
                    >
                        <?= esc($estado) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-grupo">

            <label for="sistema-tipo">
                Tipo
                <span class="campo-obligatorio">*</span>
            </label>

            <select
                id="sistema-tipo"
                name="tipo"
                <?= $esDetalle ? 'disabled' : '' ?>
                required
            >
                <option value="">
                    Selecciona una opción
                </option>

                <?php foreach (
                    ['Sistema', 'Agrupador', 'Módulo']
                    as $tipo
                ): ?>

                    <option
                        value="<?= esc($tipo, 'attr') ?>"
                        <?= ($sistema['tipo'] ?? '') === $tipo
                            ? 'selected'
                            : '' ?>
                    >
                        <?= esc($tipo) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-grupo">

            <label for="sistema-modo-visualizacion">
                Modo de visualización
                <span class="campo-obligatorio">*</span>
            </label>

            <select
                id="sistema-modo-visualizacion"
                name="modo_visualizacion"
                <?= $esDetalle ? 'disabled' : '' ?>
                required
            >
                <option value="">
                    Selecciona una opción
                </option>

                <option value="integrado">
                    Integrado
                </option>

                <option value="externo">
                    Externo
                </option>

                <option value="registro">
                    Solo registro
                </option>

            </select>

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="sistema-descripcion">
                Descripción
            </label>

            <textarea
                id="sistema-descripcion"
                name="descripcion"
                rows="4"
                placeholder="Describe brevemente la función del sistema"
                <?= $esDetalle ? 'readonly' : '' ?>
            ><?= esc($sistema['descripcion'] ?? '') ?></textarea>

        </div>

    </div>

</fieldset>