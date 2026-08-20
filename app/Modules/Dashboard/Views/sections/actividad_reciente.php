<section class="dashboard-seccion dashboard-actividad">

    <div class="dashboard-seccion__encabezado">

        <h3>
            Actividad reciente
        </h3>

        <p>
            Consulta los últimos movimientos realizados
            dentro de Project Hub.
        </p>

    </div>


    <?php if (!empty($actividades)): ?>

    <div class="dashboard-actividad__tabla-contenedor">

        <table class="dashboard-actividad__tabla">

            <thead>

                <tr>
                    <th>Usuario</th>
                    <th>Bloque</th>
                    <th>Acción</th>
                    <th>Fecha y hora</th>
                    <th></th>
                </tr>

            </thead>


            <tbody>

                <?php foreach ($actividades as $actividad): ?>

                <?php

                        $fechaOriginal =
                            $actividad['fecha_hora']
                            ?? '';

                        $fechaFormateada =
                            $fechaOriginal !== ''
                            ? date(
                                'd/m/Y H:i',
                                strtotime(
                                    $fechaOriginal
                                )
                            )
                            : '—';

                        ?>

                <tr>

                    <td>

                        <span class="actividad-usuario">

                            <span class="actividad-usuario__avatar" aria-hidden="true">
                                👤
                            </span>

                            <span>
                                <?= esc(
                                            $actividad['usuario_nombre']
                                                ?? 'Usuario actual'
                                        ) ?>
                            </span>

                        </span>

                    </td>


                    <td>

                        <span class="actividad-bloque" data-bloque="<?= esc(
                                                        strtolower(
                                                            $actividad['bloque']
                                                                ?? ''
                                                        ),
                                                        'attr'
                                                    ) ?>">
                            <?= esc(
                                        $actividad['bloque']
                                            ?? '—'
                                    ) ?>
                        </span>

                    </td>


                    <td>

                        <span class="actividad-accion">

                            <?= esc(
                                        $actividad['accion']
                                            ?? '—'
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

                        <button type="button" class="
                                        boton
                                        boton--secundario
                                        boton--pequeno
                                    " data-actividad-detalle data-actividad-usuario="<?= esc(
                                                                $actividad['usuario_nombre']
                                                                    ?? 'Usuario actual',
                                                                'attr'
                                                            ) ?>" data-actividad-bloque="<?= esc(
                                                                $actividad['bloque']
                                                                    ?? '',
                                                                'attr'
                                                            ) ?>" data-actividad-accion="<?= esc(
                                                                $actividad['accion']
                                                                    ?? '',
                                                                'attr'
                                                            ) ?>" data-actividad-fecha="<?= esc(
                                                                $fechaFormateada,
                                                                'attr'
                                                            ) ?>" data-actividad-detalle-texto="<?= esc(
                                                                        $actividad['detalle']
                                                                            ?? '',
                                                                        'attr'
                                                                    ) ?>">
                            Detalles
                        </button>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>


    <?php else: ?>

    <div class="dashboard-actividad__vacio">

        <span class="dashboard-actividad__icono">
            📝
        </span>

        <strong>
            Aún no hay actividad registrada
        </strong>

        <p>
            Conforme se realicen cambios en Project Hub,
            los movimientos más recientes aparecerán aquí.
        </p>

    </div>

    <?php endif; ?>


    <!--==============================================
    =               MODAL DETALLES                  =
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

</section>