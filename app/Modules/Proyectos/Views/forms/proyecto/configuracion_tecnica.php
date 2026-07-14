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

                <select
                    id="especificacion-tecnica"
                    name="id_especificacion"
                >
                    <option value="">
                        Selecciona una especificación
                    </option>

                    <option value="1">
                        ET-01
                    </option>

                    <option value="2">
                        ET-02
                    </option>

                </select>

                <div class="configuracion-tecnica__acciones">

                    <?= view('components/ui/boton', [
                        'texto' => 'Ver ficha',
                        'url'   => '#',
                        'tipo'  => 'secundario',
                    ]) ?>

                    <?= view('components/ui/boton', [
                        'texto' => 'Nueva',
                        'url'   => '#',
                        'tipo'  => 'primario',
                        'icono' => '+',
                    ]) ?>

                </div>

            </div>

            <small class="form-ayuda">
                Próximamente este apartado se conectará con el módulo de
                Especificaciones Técnicas.
            </small>

        </div>

    </div>

</fieldset>