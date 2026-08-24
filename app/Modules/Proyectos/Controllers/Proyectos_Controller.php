<?php

namespace App\Modules\Proyectos\Controllers;

use App\Controllers\BaseController;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Proyectos\Services\Especificacion_StorageService;
use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Services\Actividad_StorageService;

class Proyectos_Controller extends BaseController
{
    private Proyecto_StorageService $storage;

    private Sistema_StorageService $sistemaStorage;

    private Actividad_StorageService $actividadStorage;

    private Especificacion_StorageService $especificacionStorage;


    public function __construct()
    {
        $this->storage =
            new Proyecto_StorageService();

        $this->sistemaStorage =
            new Sistema_StorageService();

        $this->actividadStorage =
            new Actividad_StorageService();

        $this->especificacionStorage =
            new Especificacion_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $proyectos =
            $this->storage
            ->obtenerTodos();

        /*
         * Sistemas todavía continúa trabajando
         * con su StorageService actual.
         *
         * Lo migraremos posteriormente.
         */
        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();

        $especificaciones =
            $this->especificacionStorage
            ->obtenerTodos();


        /*==================================================
        =           TOTAL DE SISTEMAS POR PROYECTO          =
        ==================================================*/

        $totalesPorProyecto = [];

        foreach (
            $sistemas
            as $sistema
        ) {

            $idProyecto =
                (int) (
                    $sistema['id_proyecto']
                    ?? 0
                );


            if ($idProyecto <= 0) {
                continue;
            }


            if (
                !isset(
                    $totalesPorProyecto[
                        $idProyecto
                    ]
                )
            ) {
                $totalesPorProyecto[
                    $idProyecto
                ] = 0;
            }


            $totalesPorProyecto[
                $idProyecto
            ]++;
        }


        /*==================================================
        =              PREPARAR PROYECTOS                  =
        ==================================================*/

        foreach (
            $proyectos
            as &$proyecto
        ) {

            $idProyecto =
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                );


            $proyecto['total_sistemas'] =
                $totalesPorProyecto[
                    $idProyecto
                ]
                ?? 0;
        }

        unset(
            $proyecto
        );


        /*==================================================
        =                    VISTA                          =
        ==================================================*/

        return view(
            'App\Modules\Proyectos\Views\index',
            [
                'title' =>
                    'Proyectos | Project Hub',

                'proyectos' =>
                    $proyectos,

                'especificaciones' =>
                    $especificaciones,
            ]
        );
    }


    /*==================================================
    =                 GUARDAR PROYECTO                 =
    ==================================================*/

    public function guardar()
    {
        $datos =
            $this->request
            ->getJSON(true);


        if (!is_array($datos)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se recibieron datos válidos del proyecto.',
                ]);
        }


        try {

            /*==================================================
            =                    CREAR                         =
            ==================================================*/

            $proyecto =
                $this->storage
                ->crear(
                    $datos
                );


            if ($proyecto === null) {

                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No fue posible registrar el proyecto.',
                    ]);
            }


            /*
             * Al crear el proyecto todavía
             * no tiene sistemas asociados.
             */
            $proyecto['total_sistemas'] =
                0;


            /*==================================================
            =              REGISTRAR ACTIVIDAD                 =
            ==================================================*/

            $this->registrarActividad(
                'Agregó',
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                ),
                'Agregó el proyecto "'
                    . (
                        $proyecto['nombre']
                        ?? 'Proyecto'
                    )
                    . '".'
            );


            /*==================================================
            =                  RESPUESTA                       =
            ==================================================*/

            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Proyecto registrado correctamente.',

                    'proyecto' =>
                        $proyecto,

                    'fila_html' =>
                        view(
                            'components/ui/fila_proyecto',
                            [
                                'proyecto' =>
                                    $proyecto,
                            ],
                            [
                                'saveData' =>
                                    false,
                            ]
                        ),
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al registrar proyecto: {mensaje}',
                [
                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'Ocurrió un error al registrar el proyecto.',
                ]);
        }
    }


    /*==================================================
    =               OBTENER PROYECTO                  =
    ==================================================*/

    public function obtener(
        int $idProyecto
    ) {

        $proyectoEncontrado =
            $this->storage
            ->obtenerPorId(
                $idProyecto
            );


        if ($proyectoEncontrado === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el proyecto solicitado.',
                ]);
        }


        /*==================================================
        =              TOTAL REAL DE SISTEMAS              =
        ==================================================*/

        $proyectoEncontrado[
            'total_sistemas'
        ] =
            count(
                $this->sistemaStorage
                ->obtenerPorProyecto(
                    $idProyecto
                )
            );


        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'proyecto' =>
                    $proyectoEncontrado,
            ]);
    }


    /*==================================================
    =               ACTUALIZAR PROYECTO               =
    ==================================================*/

    public function actualizar(
        int $idProyecto
    ) {

        $datos =
            $this->request
            ->getJSON(true);


        if (!is_array($datos)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Los datos enviados no son válidos.',
                ]);
        }


        try {

            /*==================================================
            =                 ACTUALIZAR                       =
            ==================================================*/

            $proyectoActualizado =
                $this->storage
                ->actualizar(
                    $idProyecto,
                    $datos
                );


            if ($proyectoActualizado === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'El proyecto solicitado no existe.',
                    ]);
            }


            /*==================================================
            =              TOTAL DE SISTEMAS                   =
            ==================================================*/

            $proyectoActualizado[
                'total_sistemas'
            ] =
                count(
                    $this->sistemaStorage
                    ->obtenerPorProyecto(
                        $idProyecto
                    )
                );


            /*==================================================
            =              REGISTRAR ACTIVIDAD                 =
            ==================================================*/

            $this->registrarActividad(
                'Editó',
                $idProyecto,
                'Editó el proyecto "'
                    . (
                        $proyectoActualizado[
                            'nombre'
                        ]
                        ?? 'Proyecto'
                    )
                    . '".'
            );


            /*==================================================
            =                  RESPUESTA                       =
            ==================================================*/

            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Proyecto actualizado correctamente.',

                    'proyecto' =>
                        $proyectoActualizado,

                    'fila_html' =>
                        view(
                            'components/ui/fila_proyecto',
                            [
                                'proyecto' =>
                                    $proyectoActualizado,
                            ],
                            [
                                'saveData' =>
                                    false,
                            ]
                        ),
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar proyecto {id}: {mensaje}',
                [
                    'id' =>
                        $idProyecto,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'Ocurrió un error al actualizar el proyecto.',
                ]);
        }
    }


    /*==================================================
    =              DESACTIVAR PROYECTO                =
    ==================================================*/

    public function desactivar(
        int $idProyecto
    ) {

        $proyecto =
            $this->storage
            ->desactivar(
                $idProyecto
            );


        if ($proyecto === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el proyecto solicitado.',
                ]);
        }


        $this->registrarActividad(
            'Desactivó',
            $idProyecto,
            'Desactivó el proyecto "'
                . (
                    $proyecto['nombre']
                    ?? 'Proyecto'
                )
                . '".'
        );


        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'mensaje' =>
                    'Proyecto desactivado correctamente.',

                'proyecto' =>
                    $proyecto,
            ]);
    }


    /*==================================================
    =                ACTIVAR PROYECTO                  =
    ==================================================*/

    public function activar(
        int $idProyecto
    ) {

        $proyecto =
            $this->storage
            ->activar(
                $idProyecto
            );


        if ($proyecto === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el proyecto solicitado.',
                ]);
        }


        $this->registrarActividad(
            'Activó',
            $idProyecto,
            'Activó el proyecto "'
                . (
                    $proyecto['nombre']
                    ?? 'Proyecto'
                )
                . '".'
        );


        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'mensaje' =>
                    'Proyecto activado correctamente.',

                'proyecto' =>
                    $proyecto,
            ]);
    }


    /*==================================================
    =                ELIMINAR PROYECTO                 =
    ==================================================*/

    public function eliminar(
        int $idProyecto
    ) {

        /*==================================================
        =             OBTENER PROYECTO                     =
        ==================================================*/

        $proyecto =
            $this->storage
            ->obtenerPorId(
                $idProyecto
            );


        if ($proyecto === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el proyecto solicitado.',
                ]);
        }


        /*==================================================
        =              VALIDAR SISTEMAS                    =
        ==================================================*/

        $sistemasAsociados =
            $this->sistemaStorage
            ->obtenerPorProyecto(
                $idProyecto
            );


        if (!empty($sistemasAsociados)) {

            return $this->response
                ->setStatusCode(409)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se puede eliminar este proyecto porque tiene sistemas asociados.',
                ]);
        }


        try {

            /*==================================================
            =                   ELIMINAR                       =
            ==================================================*/

            $eliminado =
                $this->storage
                ->eliminar(
                    $idProyecto
                );


            if (!$eliminado) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró el proyecto solicitado.',
                    ]);
            }


            /*==================================================
            =              REGISTRAR ACTIVIDAD                 =
            ==================================================*/

            $this->registrarActividad(
                'Eliminó',
                $idProyecto,
                'Eliminó el proyecto "'
                    . (
                        $proyecto['nombre']
                        ?? 'Proyecto'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Proyecto eliminado correctamente.',
                ]);

        } catch (\Throwable $error) {

            /*
             * La BD también protege al proyecto
             * cuando existen registros relacionados
             * mediante llaves foráneas.
             */
            log_message(
                'error',
                'No fue posible eliminar el proyecto {id}: {mensaje}',
                [
                    'id' =>
                        $idProyecto,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(409)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se puede eliminar el proyecto porque existen registros asociados.',
                ]);
        }
    }


    /*==================================================
    =            GUARDAR ESPECIFICACIÓN               =
    ==================================================*/

    public function guardarEspecificacion()
    {

        $datos =
            $this->request
            ->getJSON(true);


        if (!is_array($datos)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se recibieron datos válidos de la ficha técnica.',
                ]);
        }


        $codigo =
            trim(
                (string) (
                    $datos['codigo']
                    ?? ''
                )
            );


        if ($codigo === '') {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El código de la ficha técnica es obligatorio.',
                ]);
        }


        /*==================================================
        =              VALIDAR CÓDIGO ÚNICO               =
        ==================================================*/

        if (
            $this->especificacionStorage
            ->existeCodigo(
                $codigo
            )
        ) {

            return $this->response
                ->setStatusCode(409)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Ya existe una ficha técnica con ese código.',
                ]);
        }


        try {

            /*==================================================
            =                    CREAR                         =
            ==================================================*/

            $especificacion =
                $this->especificacionStorage
                ->crear(
                    $datos
                );


            if ($especificacion === null) {

                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No fue posible registrar la ficha técnica.',
                    ]);
            }


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Ficha técnica registrada correctamente.',

                    'especificacion' =>
                        $especificacion,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al registrar especificación técnica: {mensaje}',
                [
                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'Ocurrió un error al registrar la ficha técnica.',
                ]);
        }
    }


    /*==================================================
    =            OBTENER ESPECIFICACIÓN               =
    ==================================================*/

    public function obtenerEspecificacion(
        int $idEspecificacion
    ) {

        $especificacion =
            $this->especificacionStorage
            ->obtenerPorId(
                $idEspecificacion
            );


        if ($especificacion === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la ficha técnica solicitada.',
                ]);
        }


        /*==================================================
        =            PROYECTOS ASOCIADOS                  =
        ==================================================*/

        $proyectos =
            $this->storage
            ->obtenerTodos();


        $proyectosAsociados =
            array_values(
                array_filter(
                    $proyectos,
                    static function (
                        array $proyecto
                    ) use (
                        $idEspecificacion
                    ): bool {

                        return (
                            (int) (
                                $proyecto[
                                    'id_especificacion'
                                ]
                                ?? 0
                            )
                        ) ===
                        $idEspecificacion;
                    }
                )
            );


        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'especificacion' =>
                    $especificacion,

                'proyectos_asociados' =>
                    $proyectosAsociados,
            ]);
    }


    /*==================================================
    =           ACTUALIZAR ESPECIFICACIÓN             =
    ==================================================*/

    public function actualizarEspecificacion(
        int $idEspecificacion
    ) {

        $datos =
            $this->request
            ->getJSON(true);


        if (!is_array($datos)) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Los datos enviados no son válidos.',
                ]);
        }


        /*==================================================
        =              VALIDAR EXISTENCIA                  =
        ==================================================*/

        $existente =
            $this->especificacionStorage
            ->obtenerPorId(
                $idEspecificacion
            );


        if ($existente === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la ficha técnica solicitada.',
                ]);
        }


        $codigo =
            trim(
                (string) (
                    $datos['codigo']
                    ?? ''
                )
            );


        if ($codigo === '') {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El código de la ficha técnica es obligatorio.',
                ]);
        }


        /*==================================================
        =              VALIDAR CÓDIGO ÚNICO               =
        ==================================================*/

        if (
            $this->especificacionStorage
            ->existeCodigo(
                $codigo,
                $idEspecificacion
            )
        ) {

            return $this->response
                ->setStatusCode(409)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Ya existe una ficha técnica con ese código.',
                ]);
        }


        try {

            $especificacionActualizada =
                $this->especificacionStorage
                ->actualizar(
                    $idEspecificacion,
                    $datos
                );


            if ($especificacionActualizada === null) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No se encontró la ficha técnica solicitada.',
                    ]);
            }


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Ficha técnica actualizada correctamente.',

                    'especificacion' =>
                        $especificacionActualizada,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar especificación {id}: {mensaje}',
                [
                    'id' =>
                        $idEspecificacion,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        $error->getMessage()
                        ?: 'Ocurrió un error al actualizar la ficha técnica.',
                ]);
        }
    }


    /*==================================================
    =            ELIMINAR ESPECIFICACIÓN              =
    ==================================================*/

    public function eliminarEspecificacion(
        int $idEspecificacion
    ) {

        /*==================================================
        =              COMPROBAR EXISTENCIA               =
        ==================================================*/

        $especificacion =
            $this->especificacionStorage
            ->obtenerPorId(
                $idEspecificacion
            );


        if ($especificacion === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró la ficha técnica solicitada.',
                ]);
        }


        /*==================================================
        =             COMPROBAR ASOCIACIONES              =
        ==================================================*/

        $proyectos =
            $this->storage
            ->obtenerTodos();


        $proyectosAsociados =
            array_values(
                array_filter(
                    $proyectos,
                    static function (
                        array $proyecto
                    ) use (
                        $idEspecificacion
                    ): bool {

                        return (
                            (int) (
                                $proyecto[
                                    'id_especificacion'
                                ]
                                ?? 0
                            )
                        ) ===
                        $idEspecificacion;
                    }
                )
            );


        if (!empty($proyectosAsociados)) {

            $nombresProyectos =
                array_values(
                    array_map(
                        static fn(
                            array $proyecto
                        ): string =>
                            (string) (
                                $proyecto[
                                    'nombre'
                                ]
                                ?? 'Proyecto'
                            ),
                        $proyectosAsociados
                    )
                );


            return $this->response
                ->setStatusCode(409)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se puede eliminar esta ficha técnica porque está asociada a uno o más proyectos.',

                    'proyectos_asociados' =>
                        $nombresProyectos,
                ]);
        }


        /*==================================================
        =                   ELIMINAR                       =
        ==================================================*/

        try {

            $eliminado =
                $this->especificacionStorage
                ->eliminar(
                    $idEspecificacion
                );


            if (!$eliminado) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No fue posible encontrar la ficha técnica.',
                    ]);
            }


            $totalEspecificaciones =
                count(
                    $this->especificacionStorage
                    ->obtenerTodos()
                );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'La ficha técnica fue eliminada correctamente.',

                    'id_especificacion' =>
                        $idEspecificacion,

                    'total_especificaciones' =>
                        $totalEspecificaciones,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al eliminar especificación {id}: {mensaje}',
                [
                    'id' =>
                        $idEspecificacion,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(409)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se puede eliminar la ficha técnica porque existen registros asociados.',
                ]);
        }
    }


    /*==================================================
    =              REGISTRAR ACTIVIDAD                =
    ==================================================*/

    private function registrarActividad(
        string $accion,
        int $idProyecto,
        string $detalle
    ): void {

        try {

            /*
             * Auditoría todavía continúa usando
             * Actividad_StorageService.
             *
             * Se migrará en su propia etapa.
             */
            $this->actividadStorage
                ->registrar([
                    'bloque' =>
                        'Proyectos',

                    'accion' =>
                        $accion,

                    'entidad_tipo' =>
                        'Proyecto',

                    'entidad_id' =>
                        $idProyecto,

                    'detalle' =>
                        $detalle,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'No fue posible registrar actividad del proyecto {id}: {mensaje}',
                [
                    'id' =>
                        $idProyecto,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );
        }
    }
}