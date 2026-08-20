<?= $this->extend('layouts/head') ?>

<?= $this->section('title') ?>
Historial de actividad | Project Hub
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<section class="actividad-page">


    <!--==============================================
    =                 ENCABEZADO                    =
    ===============================================-->

    <header class="actividad-header">

        <div class="actividad-header__contenido">

            <span class="actividad-header__eyebrow">
                Auditoría
            </span>

            <h1 class="actividad-header__titulo">
                Historial de actividad
            </h1>

            <p class="actividad-header__descripcion">
                Consulta los movimientos registrados
                dentro de Project Hub.
            </p>

        </div>


        <div class="actividad-header__acciones">

            <a
                href="<?= base_url('dashboard') ?>"
                class="boton boton--secundario"
            >
                ← Volver al Dashboard
            </a>

        </div>

    </header>


    <!--==============================================
    =                   FILTROS                     =
    ===============================================-->

    <section class="actividad-filtros">

        <div class="actividad-filtros__grid">


            <!--======================================
            =                  BUSCAR                =
            =======================================-->

            <div class="actividad-filtro">

                <label for="actividad-buscar">
                    Buscar
                </label>

                <input
                    type="search"
                    id="actividad-buscar"
                    placeholder="Usuario, bloque, acción..."
                    autocomplete="off"
                >

            </div>


            <!--======================================
            =                  BLOQUE                =
            =======================================-->

            <div class="actividad-filtro">

                <label for="actividad-bloque">
                    Bloque
                </label>

                <select id="actividad-bloque">

                    <option value="">
                        Todos
                    </option>

                    <option value="proyectos">
                        Proyectos
                    </option>

                    <option value="sistemas">
                        Sistemas
                    </option>

                    <option value="módulos">
                        Módulos
                    </option>

                    <option value="apis">
                        APIs
                    </option>

                    <option value="documentos">
                        Documentos
                    </option>

                </select>

            </div>


            <!--======================================
            =                  ACCIÓN                =
            =======================================-->

            <div class="actividad-filtro">

                <label for="actividad-accion">
                    Acción
                </label>

                <select id="actividad-accion">

                    <option value="">
                        Todas
                    </option>

                    <option value="agregó">
                        Agregó
                    </option>

                    <option value="editó">
                        Editó
                    </option>

                    <option value="activó">
                        Activó
                    </option>

                    <option value="desactivó">
                        Desactivó
                    </option>

                    <option value="eliminó">
                        Eliminó
                    </option>

                    <option value="editó arquitectura">
                        Editó arquitectura
                    </option>

                    <option value="eliminó arquitectura">
                        Eliminó arquitectura
                    </option>

                    <option value="editó dependencias">
                        Editó dependencias
                    </option>

                    <option value="eliminó dependencias">
                        Eliminó dependencias
                    </option>

                    <option value="editó observaciones">
                        Editó observaciones
                    </option>

                    <option value="eliminó observaciones">
                        Eliminó observaciones
                    </option>

                    <option value="editó historial">
                        Editó historial
                    </option>

                    <option value="eliminó historial">
                        Eliminó historial
                    </option>

                </select>

            </div>


            <!--======================================
            =                  FECHA                 =
            =======================================-->

            <div class="actividad-filtro">

                <label for="actividad-fecha">
                    Fecha
                </label>

                <input
                    type="date"
                    id="actividad-fecha"
                >

            </div>

        </div>


        <div class="actividad-filtros__acciones">

            <button
                type="button"
                class="boton boton--secundario"
                data-limpiar-filtros-actividad
            >
                Limpiar filtros
            </button>

        </div>

    </section>


    <!--==============================================
    =                   HISTORIAL                   =
    ===============================================-->

    <section class="actividad-listado">

        <?php if (!empty($actividades)): ?>

            <div class="actividad-tabla-contenedor">

                <table class="actividad-tabla">

                    <thead>

                        <tr>
                            <th>Usuario</th>
                            <th>Bloque</th>
                            <th>Acción</th>
                            <th>Fecha y hora</th>
                            <th>Detalles</th>
                        </tr>

                    </thead>


                    <tbody data-actividad-tabla-body>

                        <?php foreach ($actividades as $actividad): ?>

                            <?php

                            $fechaOriginal =
                                $actividad['fecha_hora']
                                ?? '';

                            $timestamp =
                                $fechaOriginal !== ''
                                    ? strtotime(
                                        $fechaOriginal
                                    )
                                    : false;

                            $fechaFormateada =
                                $timestamp !== false
                                    ? date(
                                        'd/m/Y H:i',
                                        $timestamp
                                    )
                                    : '—';

                            $fechaFiltro =
                                $timestamp !== false
                                    ? date(
                                        'Y-m-d',
                                        $timestamp
                                    )
                                    : '';

                            $usuario =
                                $actividad['usuario_nombre']
                                ?? 'Usuario actual';

                            $bloque =
                                $actividad['bloque']
                                ?? '';

                            $accion =
                                $actividad['accion']
                                ?? '';

                            $detalle =
                                $actividad['detalle']
                                ?? '';

                            ?>

                            <tr
                                data-actividad-fila

                                data-usuario="<?= esc(
                                    strtolower(
                                        $usuario
                                    ),
                                    'attr'
                                ) ?>"

                                data-bloque="<?= esc(
                                    strtolower(
                                        $bloque
                                    ),
                                    'attr'
                                ) ?>"

                                data-accion="<?= esc(
                                    strtolower(
                                        $accion
                                    ),
                                    'attr'
                                ) ?>"

                                data-fecha="<?= esc(
                                    $fechaFiltro,
                                    'attr'
                                ) ?>"

                                data-texto="<?= esc(
                                    strtolower(
                                        $usuario
                                        . ' '
                                        . $bloque
                                        . ' '
                                        . $accion
                                        . ' '
                                        . $detalle
                                    ),
                                    'attr'
                                ) ?>"
                            >

                                <td>

                                    <div class="actividad-usuario">

                                        <span
                                            class="actividad-usuario__avatar"
                                            aria-hidden="true"
                                        >
                                            👤
                                        </span>

                                        <span>
                                            <?= esc(
                                                $usuario
                                            ) ?>
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <span class="actividad-bloque">
                                        <?= esc(
                                            $bloque ?: '—'
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <span class="actividad-accion">
                                        <?= esc(
                                            $accion ?: '—'
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <span class="actividad-fecha">
                                        <?= esc(
                                            $fechaFormateada
                                        ) ?>
                                    </span>

                                </td>


                                <td>

                                    <button
                                        type="button"
                                        class="
                                            boton
                                            boton--secundario
                                            boton--pequeno
                                        "
                                        data-actividad-detalle

                                        data-actividad-usuario="<?= esc(
                                            $usuario,
                                            'attr'
                                        ) ?>"

                                        data-actividad-bloque="<?= esc(
                                            $bloque,
                                            'attr'
                                        ) ?>"

                                        data-actividad-accion="<?= esc(
                                            $accion,
                                            'attr'
                                        ) ?>"

                                        data-actividad-fecha="<?= esc(
                                            $fechaFormateada,
                                            'attr'
                                        ) ?>"

                                        data-actividad-detalle-texto="<?= esc(
                                            $detalle,
                                            'attr'
                                        ) ?>"
                                    >
                                        Ver detalles
                                    </button>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>


            <!--==========================================
            =              SIN RESULTADOS               =
            ===========================================-->

            <div
                class="actividad-sin-resultados"
                data-actividad-sin-resultados
                hidden
            >

                <span>
                    🔎
                </span>

                <strong>
                    No se encontraron actividades
                </strong>

                <p>
                    Ajusta o elimina alguno de los filtros.
                </p>

            </div>

        <?php else: ?>

            <div class="actividad-vacio">

                <span class="actividad-vacio__icono">
                    📝
                </span>

                <strong>
                    Aún no hay actividad registrada
                </strong>

                <p>
                    Los movimientos realizados en Project Hub
                    aparecerán aquí.
                </p>

            </div>

        <?php endif; ?>

    </section>

</section>


<!--==============================================
=               MODAL DETALLE                   =
===============================================-->

<?php

$contenido = '
    <div class="actividad-detalle">

        <div class="actividad-detalle__fila">

            <span>
                Usuario
            </span>

            <strong data-actividad-modal-usuario>
                —
            </strong>

        </div>


        <div class="actividad-detalle__fila">

            <span>
                Bloque
            </span>

            <strong data-actividad-modal-bloque>
                —
            </strong>

        </div>


        <div class="actividad-detalle__fila">

            <span>
                Acción
            </span>

            <strong data-actividad-modal-accion>
                —
            </strong>

        </div>


        <div class="actividad-detalle__fila">

            <span>
                Fecha y hora
            </span>

            <strong data-actividad-modal-fecha>
                —
            </strong>

        </div>


        <div class="actividad-detalle__descripcion">

            <span>
                Detalle
            </span>

            <p data-actividad-modal-detalle>
                —
            </p>

        </div>

    </div>
';

$acciones = '
    <button
        type="button"
        class="boton boton--secundario"
        data-modal-cerrar
    >
        Cerrar
    </button>
';

?>

<?= view(
    'components/ui/modal',
    [
        'id' =>
            'modal-actividad-detalle',

        'titulo' =>
            'Detalle de actividad',

        'tamano' =>
            'pequeno',

        'contenido' =>
            $contenido,

        'acciones' =>
            $acciones,
    ],
    [
        'saveData' =>
            false,
    ]
) ?>


<script
    type="module"
    src="<?= base_url(
        'assets/js/dashboard/historial.js'
    ) ?>"
></script>

<?= $this->endSection() ?>