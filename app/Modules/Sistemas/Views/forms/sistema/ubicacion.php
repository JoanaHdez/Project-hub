<?php

$esDetalle = ($modo ?? 'crear') === 'detalle';

?>

<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Ubicación
    </legend>

    <p class="form-bloque__descripcion">
        Registra las rutas y direcciones necesarias para localizar el sistema.
    </p>

    <div class="form-grid">

        <div class="form-grupo form-grupo--completo">

            <label for="sistema-url">
                URL del sistema
            </label>

            <input
                type="url"
                id="sistema-url"
                name="url"
                value="<?= esc($sistema['url'] ?? '', 'attr') ?>"
                placeholder="https://misistema.com"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="sistema-repositorio">
                Repositorio Git
            </label>

            <input
                type="url"
                id="sistema-repositorio"
                name="repositorio_url"
                value="<?= esc($sistema['repositorio_url'] ?? '', 'attr') ?>"
                placeholder="https://github.com/usuario/repositorio"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

        <div class="form-grupo">

            <label for="sistema-ruta-local">
                Ruta local
            </label>

            <input
                type="text"
                id="sistema-ruta-local"
                name="ruta_local"
                value="<?= esc($sistema['ruta_local'] ?? '', 'attr') ?>"
                placeholder="C:\laragon\www\Sistema"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

        <div class="form-grupo">

            <label for="sistema-url-servidor">
                URL del servidor
            </label>

            <input
                type="url"
                id="sistema-url-servidor"
                name="url_servidor"
                value="<?= esc($sistema['url_servidor'] ?? '', 'attr') ?>"
                placeholder="https://servidor.com"
                <?= $esDetalle ? 'readonly' : '' ?>
            >

        </div>

    </div>

</fieldset>