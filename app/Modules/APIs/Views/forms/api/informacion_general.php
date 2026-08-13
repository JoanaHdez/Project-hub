<?php

$modo = $modo ?? 'crear';

$esDetalle = $modo === 'detalle';

$prefijoId = match ($modo) {
    'editar'  => 'editar-api',
    'detalle' => 'detalle-api',
    default   => 'nueva-api',
};

$idProyecto = $prefijoId . '-proyecto';
$idSistema = $prefijoId . '-sistema';
$idNombre = $prefijoId . '-nombre';
$idEstado = $prefijoId . '-estado';
$idMetodo = $prefijoId . '-metodo';
$idDescripcion = $prefijoId . '-descripcion';

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

            <label for="<?= esc($idProyecto, 'attr') ?>">
                Proyecto
                <span class="campo-obligatorio">*</span>
            </label>

            <select
                id="<?= esc($idProyecto, 'attr') ?>"
                name="id_proyecto"
                <?= $esDetalle ? 'disabled' : '' ?>
                required
            >
                <option value="">
                    Selecciona un proyecto
                </option>

                <?php foreach (($proyectos ?? []) as $proyecto): ?>

                    <?php
                    $proyectoId = (string) (
                        $proyecto['id_proyecto'] ?? ''
                    );

                    $seleccionado =
                        (string) ($api['id_proyecto'] ?? '')
                        === $proyectoId;
                    ?>

                    <option
                        value="<?= esc($proyectoId, 'attr') ?>"
                        <?= $seleccionado ? 'selected' : '' ?>
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

            <label for="<?= esc($idSistema, 'attr') ?>">
                Sistema asociado
            </label>

            <select
                id="<?= esc($idSistema, 'attr') ?>"
                name="id_sistema"
                <?= $esDetalle ? 'disabled' : '' ?>
            >
                <option value="">
                    Sin sistema asociado
                </option>
            </select>

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="<?= esc($idNombre, 'attr') ?>">
                Nombre de la API
                <span class="campo-obligatorio">*</span>
            </label>

            <input
                type="text"
                id="<?= esc($idNombre, 'attr') ?>"
                name="nombre"
                value="<?= esc(
                    $api['nombre'] ?? '',
                    'attr'
                ) ?>"
                placeholder="Ej. API de Invitaciones"
                <?= $esDetalle ? 'readonly' : '' ?>
                required
            >

        </div>

        <div class="form-grupo">

            <label for="<?= esc($idEstado, 'attr') ?>">
                Estado
                <span class="campo-obligatorio">*</span>
            </label>

            <select
                id="<?= esc($idEstado, 'attr') ?>"
                name="estado"
                <?= $esDetalle ? 'disabled' : '' ?>
                required
            >
                <option value="">
                    Selecciona una opción
                </option>

                <?php
                $estados = [
                    'Producción',
                    'Desarrollo',
                    'Detenido',
                    'Mantenimiento',
                ];
                ?>

                <?php foreach ($estados as $estado): ?>

                    <option
                        value="<?= esc($estado, 'attr') ?>"
                        <?= (
                            ($api['estado'] ?? '') === $estado
                        ) ? 'selected' : '' ?>
                    >
                        <?= esc($estado) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-grupo">

            <label for="<?= esc($idMetodo, 'attr') ?>">
                Método
                <span class="campo-obligatorio">*</span>
            </label>

            <select
                id="<?= esc($idMetodo, 'attr') ?>"
                name="metodo"
                <?= $esDetalle ? 'disabled' : '' ?>
                required
            >
                <option value="">
                    Selecciona una opción
                </option>

                <?php
                $metodos = [
                    'GET',
                    'POST',
                    'PUT',
                    'PATCH',
                    'DELETE',
                ];
                ?>

                <?php foreach ($metodos as $metodo): ?>

                    <option
                        value="<?= esc($metodo, 'attr') ?>"
                        <?= (
                            strtoupper(
                                (string) ($api['metodo'] ?? '')
                            ) === $metodo
                        ) ? 'selected' : '' ?>
                    >
                        <?= esc($metodo) ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-grupo form-grupo--completo">

            <label for="<?= esc($idDescripcion, 'attr') ?>">
                Descripción
            </label>

            <textarea
                id="<?= esc($idDescripcion, 'attr') ?>"
                name="descripcion"
                rows="4"
                <?= $esDetalle ? 'readonly' : '' ?>
            ><?= esc(
                $api['descripcion'] ?? ''
            ) ?></textarea>

        </div>

    </div>

</fieldset>