<?php

$proyectos =
    $proyectos
    ?? [];

$especificaciones =
    $especificaciones
    ?? [];


/*==================================================
=       MAPA DE ESPECIFICACIONES TÉCNICAS          =
==================================================*/

$especificacionesPorId = [];

foreach (
    $especificaciones
    as $especificacion
) {

    $idEspecificacion =
        (int) (
            $especificacion[
                'id_especificacion'
            ]
            ?? 0
        );

    if ($idEspecificacion <= 0) {
        continue;
    }

    $especificacionesPorId[
        $idEspecificacion
    ] =
        $especificacion;
}


/*==================================================
=             ORDENAR PROYECTOS                    =
==================================================*/

$proyectosOrdenados =
    $proyectos;


/*
 * El ID mayor corresponde al proyecto
 * registrado más recientemente.
 */
usort(
    $proyectosOrdenados,
    static function (
        array $a,
        array $b
    ): int {

        return (
            (int) (
                $b['id_proyecto']
                ?? 0
            )
        ) <=> (
            (int) (
                $a['id_proyecto']
                ?? 0
            )
        );
    }
);


/*==================================================
=             ÚLTIMOS 4 PROYECTOS                  =
==================================================*/

$proyectosRecientes =
    array_slice(
        $proyectosOrdenados,
        0,
        4
    );

?>

<section class="proyectos-seccion">

    <div class="proyectos-seccion__encabezado">

        <h3>
            Proyectos recientes
        </h3>

        <p>
            Consulta los últimos proyectos registrados en Project Hub.
        </p>

    </div>


    <div class="proyectos-destacados">

        <?php if (
            empty(
                $proyectosRecientes
            )
        ): ?>

            <p>
                Aún no hay proyectos registrados.
            </p>

        <?php else: ?>

            <?php foreach (
                $proyectosRecientes
                as $proyecto
            ): ?>


                <?php

                /*==========================================
                =                 ESTADO                  =
                ==========================================*/

                $estado =
                    trim(
                        (string) (
                            $proyecto['estado']
                            ?? ''
                        )
                    );

                if ($estado === '') {
                    $estado =
                        'Sin estado';
                }


                $tipoEstado =
                    match (
                        mb_strtolower(
                            $estado,
                            'UTF-8'
                        )
                    ) {

                        'producción',
                        'produccion' =>
                            'produccion',

                        'desarrollo' =>
                            'desarrollo',

                        'detenido' =>
                            'detenido',

                        'mantenimiento' =>
                            'mantenimiento',

                        default =>
                            'neutral',
                    };


                /*==========================================
                =          ESPECIFICACIÓN TÉCNICA         =
                ==========================================*/

                $idEspecificacion =
                    (int) (
                        $proyecto[
                            'id_especificacion'
                        ]
                        ?? 0
                    );


                $especificacion =
                    $especificacionesPorId[
                        $idEspecificacion
                    ]
                    ?? null;


                $codigoEt =
                    is_array(
                        $especificacion
                    )
                        ? (
                            $especificacion[
                                'codigo'
                            ]
                            ?? 'Sin especificación'
                        )
                        : 'Sin especificación';

                ?>


                <?= view(
                    'components/ui/tarjeta_proyecto',
                    [
                        'nombre' =>
                            $proyecto[
                                'nombre'
                            ]
                            ?? 'Proyecto',

                        'estado' =>
                            $estado,

                        'tipoEstado' =>
                            $tipoEstado,

                        'codigoEt' =>
                            $codigoEt,

                        'idEspecificacion' =>
                            $idEspecificacion > 0
                                ? $idEspecificacion
                                : null,

                        'urlFicha' =>
                            '#',

                        'modalFichaId' =>
                            $idEspecificacion > 0
                                ? 'modal-ficha-tecnica'
                                : null,
                    ],
                    [
                        'saveData' =>
                            false,
                    ]
                ) ?>


            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</section>