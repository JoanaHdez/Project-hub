<?php

$esDetalle = ($modo ?? 'crear') === 'detalle';

?>

<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Ubicación
    </legend>

    <div class="form-grid">

        <div class="form-grupo form-grupo--completo">

            <label for="nueva-api-endpoint">
                Endpoint
                <span class="campo-obligatorio">*</span>
            </label>

            <input
                type="text"
                id="nueva-api-endpoint"
                name="endpoint"
                value="<?= esc($api['endpoint'] ?? '', 'attr') ?>"
                placeholder="/api/recurso"
                <?= $esDetalle ? 'readonly' : '' ?>
                required
            >

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="nueva-api-url">
                URL completa
            </label>

            <input
                type="url"
                id="nueva-api-url"
                name="url"
                value="<?= esc($api['url'] ?? '', 'attr') ?>"
                placeholder="https://servidor.com/api/recurso"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

        <div class="form-grupo">

            <label for="nueva-api-autenticacion">
                Autenticación
            </label>

            <input
                type="text"
                id="nueva-api-autenticacion"
                name="autenticacion"
                value="<?= esc($api['autenticacion'] ?? '', 'attr') ?>"
                placeholder="Ej. API Token"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

        <div class="form-grupo">

            <label for="nueva-api-ruta-local">
                Ruta local
            </label>

            <input
                type="text"
                id="nueva-api-ruta-local"
                name="ruta_local"
                value="<?= esc($api['ruta_local'] ?? '', 'attr') ?>"
                placeholder="C:\laragon\www\Proyecto"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

        <div class="form-grupo">

            <label for="nueva-api-repositorio">
                Repositorio
            </label>

            <input
                type="url"
                id="nueva-api-repositorio"
                name="repositorio_url"
                value="<?= esc($api['repositorio_url'] ?? '', 'attr') ?>"
                placeholder="https://github.com/usuario/repositorio"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

        <div class="form-grupo">

            <label for="nueva-api-servidor">
                URL del servidor
            </label>

            <input
                type="url"
                id="nueva-api-servidor"
                name="url_servidor"
                value="<?= esc($api['url_servidor'] ?? '', 'attr') ?>"
                placeholder="https://servidor.com"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

    </div>

</fieldset>