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

            <input
                type="text"
                id="proyecto-nombre"
                name="nombre"
                placeholder="Ej. Sistema de Eventos"
                maxlength="150"
                required
            >
        </div>

        <div class="form-grupo">
            <label for="proyecto-estado">
                Estado
            </label>

            <select
                id="proyecto-estado"
                name="estado"
                required
            >
                <option value="">Selecciona una opción</option>
                <option value="Producción">Producción</option>
                <option value="Desarrollo">Desarrollo</option>
                <option value="Detenido">Detenido</option>
                <option value="Mantenimiento">Mantenimiento</option>
            </select>
        </div>

        <div class="form-grupo">
            <label for="proyecto-origen">
                Origen
            </label>

            <select
                id="proyecto-origen"
                name="origen"
            >
                <option value="">Selecciona una opción</option>
                <option value="Trabajo">Trabajo</option>
                <option value="Personal">Personal</option>
                <option value="Práctica">Práctica</option>
                <option value="Otro">Otro</option>
            </select>
        </div>

        <div class="form-grupo form-grupo--completo">
            <label for="proyecto-descripcion">
                Descripción
            </label>

            <textarea
                id="proyecto-descripcion"
                name="descripcion"
                rows="4"
                placeholder="Describe brevemente el propósito y alcance del proyecto"
            ></textarea>
        </div>

    </div>
</fieldset>