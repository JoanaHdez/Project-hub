<fieldset class="form-bloque">

    <legend class="form-bloque__titulo">
        Configuración técnica
    </legend>

    <p class="form-bloque__descripcion">
        Selecciona la especificación técnica que utilizará este proyecto.
    </p>

    <div class="form-grid">

        <div class="form-grupo form-grupo--completo">

            <label for="especificacion-tecnica">
                Especificación técnica
            </label>

            <div class="configuracion-tecnica">

                <select id="especificacion-tecnica" name="id_especificacion">
                    <option value="1" <?= ($proyecto['id_especificacion'] ?? '') === '1' ? 'selected' : '' ?>>
                        ET-01
                    </option>

                    <option value="2" <?= ($proyecto['id_especificacion'] ?? '') === '2' ? 'selected' : '' ?>>
                        ET-02
                    </option>

                </select>

                <div class="configuracion-tecnica__acciones">

                    <?= view('components/ui/boton', [
                        'texto' => 'Ver ficha',
                        'url'   => '#',
                        'tipo'  => 'secundario',
                        'modalId'  => 'modal-ficha-tecnica',
                    ]) ?>

                    <button type="button" class="boton boton--primario" disabled aria-disabled="true"
                        title="El módulo de Especificaciones Técnicas aún no está disponible">
                        <span class="boton__icono">+</span>
                        <span>Nueva</span>
                    </button>

                </div>

            </div>

            <small class="form-ayuda">
                Próximamente este apartado se conectará con el módulo de
                Especificaciones Técnicas.
            </small>

        </div>

    </div>

</fieldset>