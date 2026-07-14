<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Información adicional
    </legend>

    <p class="form-bloque__descripcion">
        Agrega datos complementarios que ayuden a identificar y administrar el proyecto.
    </p>

    <div class="form-grid">

        <div class="form-grupo">
            <label for="proyecto-responsable">
                Responsable
            </label>

            <input
                type="text"
                id="proyecto-responsable"
                name="responsable"
                placeholder="Nombre de la persona responsable"
            >

            <small class="form-ayuda">
                Este campo se relacionará con usuarios cuando se implemente el sistema de permisos.
            </small>
        </div>

        <div class="form-grupo form-grupo--completo">
            <label for="proyecto-observaciones">
                Observaciones
            </label>

            <textarea
                id="proyecto-observaciones"
                name="observaciones"
                rows="4"
                placeholder="Agrega notas técnicas, pendientes o información relevante"
            ></textarea>
        </div>

    </div>

</fieldset>