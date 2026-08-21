<?php

$especificaciones =
    $especificaciones
    ?? [];

$proyectos =
    $proyectos
    ?? [];

$columnas = [
    'Código',
    'Framework',
    'Versión',
    'PHP',
    'Base de datos',
    'Proyectos asociados',
    'Acciones',
];


/*==================================================
=       CONTAR PROYECTOS POR ESPECIFICACIÓN        =
==================================================*/

$proyectosPorEspecificacion = [];

foreach ($proyectos as $proyecto) {

    $idEspecificacion =
        (int) (
            $proyecto['id_especificacion']
            ?? 0
        );

    if ($idEspecificacion <= 0) {
        continue;
    }

    if (
        !isset(
            $proyectosPorEspecificacion[
                $idEspecificacion
            ]
        )
    ) {
        $proyectosPorEspecificacion[
            $idEspecificacion
        ] = [];
    }

    $proyectosPorEspecificacion[
        $idEspecificacion
    ][] =
        $proyecto;
}


$totalEspecificaciones =
    count(
        $especificaciones
    );

?>


<section class="proyectos-seccion">

    <!--==============================================
    =                  ENCABEZADO                   =
    ===============================================-->

    <div class="proyectos-seccion__encabezado">

        <h3>
            Especificaciones técnicas
        </h3>

        <p>
            Consulta y administra las fichas técnicas
            disponibles para los proyectos.
        </p>

    </div>


    <!--==============================================
    =              BARRA DE HERRAMIENTAS            =
    ===============================================-->

    <?= view(
        'components/ui/barra_herramientas',
        [
            'idBusqueda' =>
                'buscar-especificacion',

            'nombreBusqueda' =>
                'buscar_especificacion',

            'placeholder' =>
                'Buscar ficha técnica...',

            'tablaObjetivo' =>
                'tabla-especificaciones',
        ]
    ) ?>


    <!--==============================================
    =                    TABLA                      =
    ===============================================-->

    <div class="tabla-componente">

        <div class="tabla-contenedor">

            <table id="tabla-especificaciones" class="tabla">

                <thead>

                    <tr>

                        <?php foreach (
                            $columnas
                            as $columna
                        ): ?>

                        <th>
                            <?= esc(
                                    $columna
                                ) ?>
                        </th>

                        <?php endforeach; ?>

                    </tr>

                </thead>


                <tbody>

                    <?php if (
                        empty(
                            $especificaciones
                        )
                    ): ?>


                    <!--==================================
                        =            TABLA VACÍA             =
                        ===================================-->

                    <?= view(
                            'components/ui/tabla_vacia',
                            [
                                'icono' =>
                                    '🧩',

                                'titulo' =>
                                    'Aún no hay fichas técnicas',

                                'descripcion' =>
                                    'Las fichas aparecerán cuando exista información.',

                                'columnas' =>
                                    count(
                                        $columnas
                                    ),
                            ]
                        ) ?>


                    <?php else: ?>


                    <?php foreach (
                            $especificaciones
                            as $especificacion
                        ): ?>


                    <?php

                            $idEspecificacion =
                                (int) (
                                    $especificacion[
                                        'id_especificacion'
                                    ]
                                    ?? 0
                                );

                            $proyectosAsociados =
                                $proyectosPorEspecificacion[
                                    $idEspecificacion
                                ]
                                ?? [];

                            $totalProyectos =
                                count(
                                    $proyectosAsociados
                                );

                            ?>


                    <tr data-especificacion-id="<?= esc(
                                    (string) $idEspecificacion,
                                    'attr'
                                ) ?>">


                        <!--==========================
                                =           CÓDIGO           =
                                ===========================-->

                        <td>

                            <strong>
                                <?= esc(
                                            $especificacion[
                                                'codigo'
                                            ]
                                            ?? '—'
                                        ) ?>
                            </strong>

                        </td>


                        <!--==========================
                                =         FRAMEWORK          =
                                ===========================-->

                        <td>
                            <?= esc(
                                        $especificacion[
                                            'framework'
                                        ]
                                        ?? '—'
                                    ) ?>
                        </td>


                        <!--==========================
                                =          VERSIÓN           =
                                ===========================-->

                        <td>
                            <?= esc(
                                        $especificacion[
                                            'version_framework'
                                        ]
                                        ?? '—'
                                    ) ?>
                        </td>


                        <!--==========================
                                =             PHP            =
                                ===========================-->

                        <td>
                            <?= esc(
                                        $especificacion[
                                            'php'
                                        ]
                                        ?? '—'
                                    ) ?>
                        </td>


                        <!--==========================
                                =       BASE DE DATOS        =
                                ===========================-->

                        <td>
                            <?= esc(
                                        $especificacion[
                                            'base_datos'
                                        ]
                                        ?? '—'
                                    ) ?>
                        </td>


                        <!--==========================
                                =    PROYECTOS ASOCIADOS     =
                                ===========================-->

                        <td>
                            <?= esc(
                                        (string) $totalProyectos
                                    ) ?>
                        </td>


                        <!--==========================
                                =          ACCIONES          =
                                ===========================-->

                        <td>

                            <div class="tabla-acciones">


                                <!-- VER DETALLES -->

                                <button type="button" class="
                                                boton-accion
                                                boton-accion--ver
                                            " data-ver-especificacion data-modal-abrir="modal-ficha-tecnica"
                                    data-especificacion-id="<?= esc(
                                                (string) $idEspecificacion,
                                                'attr'
                                            ) ?>" title="Ver ficha técnica" aria-label="Ver ficha técnica">
                                    📄
                                </button>


                                <!-- EDITAR -->

                                <button type="button" class="
                                                boton-accion
                                                boton-accion--editar
                                            " data-editar-especificacion data-especificacion-id="<?= esc(
                                                (string) $idEspecificacion,
                                                'attr'
                                            ) ?>" title="Editar ficha técnica" aria-label="Editar ficha técnica">
                                    ✏️
                                </button>


                                <!-- ELIMINAR -->

                                <button type="button" class="
                                                boton-accion
                                                boton-accion--eliminar
                                            " data-eliminar-especificacion data-especificacion-id="<?= esc(
                                                (string) $idEspecificacion,
                                                'attr'
                                            ) ?>" data-total-proyectos="<?= esc(
                                                (string) $totalProyectos,
                                                'attr'
                                            ) ?>" title="Eliminar ficha técnica" aria-label="Eliminar ficha técnica">
                                    🗑️
                                </button>


                            </div>

                        </td>

                    </tr>


                    <?php endforeach; ?>


                    <?php endif; ?>

                </tbody>

            </table>

        </div>


        <!--==============================================
        =               PIE DE LA TABLA                 =
        ===============================================-->

        <?php if (
            $totalEspecificaciones > 0
        ): ?>

        <div class="tabla-pie">

            <p class="tabla-pie__contador">

                Mostrando

                <strong>
                    1
                </strong>

                a

                <strong>
                    <?= esc(
                            $totalEspecificaciones
                        ) ?>
                </strong>

                de

                <strong>
                    <?= esc(
                            $totalEspecificaciones
                        ) ?>
                </strong>

                registros

            </p>


            <nav class="tabla-paginacion" aria-label="Paginación de fichas técnicas">

                <button type="button" class="tabla-paginacion__boton" disabled>
                    ‹
                </button>


                <button type="button" class="
                            tabla-paginacion__boton
                            tabla-paginacion__boton--activo
                        " aria-current="page">
                    1
                </button>


                <button type="button" class="tabla-paginacion__boton" disabled>
                    ›
                </button>

            </nav>

        </div>

        <?php endif; ?>

    </div>

</section>