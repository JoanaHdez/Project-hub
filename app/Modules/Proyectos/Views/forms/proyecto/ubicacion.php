<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Ubicación
    </legend>

    <p class="form-bloque__descripcion">
        Registra la información necesaria para localizar el proyecto en el entorno de desarrollo y producción.
    </p>

    <div class="form-grid">

        <div class="form-grupo form-grupo--completo">

            <label for="repositorio">
                Repositorio Git
            </label>

            <input
                type="url"
                id="repositorio"
                name="repositorio_url"
                placeholder="https://github.com/usuario/repositorio"
            >

        </div>

        <div class="form-grupo">

            <label for="ruta_local">
                Ruta local
            </label>

            <input
                type="text"
                id="ruta_local"
                name="ruta_local"
                placeholder="C:\laragon\www\Proyecto"
            >

        </div>

        <div class="form-grupo">

            <label for="url_servidor">
                URL del servidor
            </label>

            <input
                type="url"
                id="url_servidor"
                name="url_servidor"
                placeholder="https://misistema.com"
            >

        </div>

    </div>

</fieldset>