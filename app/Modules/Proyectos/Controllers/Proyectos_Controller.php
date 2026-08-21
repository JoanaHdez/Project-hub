<?php

namespace App\Modules\Proyectos\Controllers;

use App\Controllers\BaseController;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Services\Actividad_StorageService;

use App\Modules\Proyectos\Services\Especificacion_StorageService;


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

        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();


        /*==================================================
        =           TOTAL DE SISTEMAS POR PROYECTO          =
        ==================================================*/

        $totalesPorProyecto = [];

        foreach ($sistemas as $sistema) {

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
                    $totalesPorProyecto[$idProyecto]
                )
            ) {
                $totalesPorProyecto[$idProyecto] = 0;
            }

            $totalesPorProyecto[$idProyecto]++;
        }


        /*==================================================
        =              PREPARAR PROYECTOS                  =
        ==================================================*/

        foreach ($proyectos as &$proyecto) {

            $idProyecto =
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                );

            $proyecto['total_sistemas'] =
                $totalesPorProyecto[$idProyecto]
                ?? 0;
        }

        unset($proyecto);


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
                    'ok' => false,

                    'mensaje' =>
                    'No se recibieron datos válidos del proyecto.',
                ]);
        }


        /*==================================================
        =              OBTENER PROYECTOS                   =
        ==================================================*/

        $proyectos =
            $this->storage
            ->obtenerTodos();

        $estado =
            trim(
                (string) (
                    $datos['estado']
                    ?? ''
                )
            );


        /*==================================================
        =              CONSTRUIR PROYECTO                  =
        ==================================================*/

        $proyecto = [

            'id_proyecto' =>
            $this->storage
                ->generarNuevoId(
                    $proyectos
                ),

            'nombre' =>
            trim(
                (string) (
                    $datos['nombre']
                    ?? ''
                )
            ),

            'estado' =>
            $estado,

            'estado_tipo' =>
            $this->obtenerTipoEstado(
                $estado
            ),

            'origen' =>
            trim(
                (string) (
                    $datos['origen']
                    ?? ''
                )
            ),

            'descripcion' =>
            trim(
                (string) (
                    $datos['descripcion']
                    ?? ''
                )
            ),

            'repositorio_url' =>
            trim(
                (string) (
                    $datos['repositorio_url']
                    ?? ''
                )
            ),

            'ruta_local' =>
            trim(
                (string) (
                    $datos['ruta_local']
                    ?? ''
                )
            ),

            'url_servidor' =>
            trim(
                (string) (
                    $datos['url_servidor']
                    ?? ''
                )
            ),

            'id_especificacion' =>
            (string) (
                $datos['id_especificacion']
                ?? ''
            ),

            'responsable' =>
            trim(
                (string) (
                    $datos['responsable']
                    ?? ''
                )
            ),

            'observaciones' =>
            trim(
                (string) (
                    $datos['observaciones']
                    ?? ''
                )
            ),

            /*
             * Al crear un proyecto todavía
             * no tiene sistemas asociados.
             */
            'total_sistemas' =>
            0,

            'fecha_creacion' =>
            date('d/m/Y'),
        ];


        /*==================================================
        =                  GUARDAR                        =
        ==================================================*/

        $proyectos[] =
            $proyecto;

        $this->storage
            ->guardarTodos(
                $proyectos
            );


        /*==================================================
        =              REGISTRAR ACTIVIDAD                 =
        ==================================================*/

        $this->registrarActividad(
            'Agregó',
            (int) $proyecto['id_proyecto'],
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
                'ok' => true,

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
    }


    /*==================================================
    =               OBTENER PROYECTO                  =
    ==================================================*/

    public function obtener(
        int $idProyecto
    ) {
        $proyectos =
            $this->storage
            ->obtenerTodos();

        $proyectoEncontrado =
            null;

        foreach ($proyectos as $proyecto) {

            if (
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                ) === $idProyecto
            ) {
                $proyectoEncontrado =
                    $proyecto;

                break;
            }
        }

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

        $proyectoEncontrado['total_sistemas'] =
            count(
                $this->sistemaStorage
                    ->obtenerPorProyecto(
                        $idProyecto
                    )
            );


        /*==================================================
        =                  RESPUESTA                       =
        ==================================================*/

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


        /*==================================================
        =              BUSCAR PROYECTO                     =
        ==================================================*/

        $proyectos =
            $this->storage
            ->obtenerTodos();

        $indiceProyecto =
            null;

        $proyectoExistente =
            null;

        foreach (
            $proyectos as
            $indice => $proyecto
        ) {

            if (
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                ) === $idProyecto
            ) {
                $indiceProyecto =
                    $indice;

                $proyectoExistente =
                    $proyecto;

                break;
            }
        }

        if (
            $proyectoExistente === null ||
            $indiceProyecto === null
        ) {

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
        =              PREPARAR DATOS                      =
        ==================================================*/

        $estado =
            trim(
                (string) (
                    $datos['estado']
                    ?? ''
                )
            );

        $totalSistemas =
            count(
                $this->sistemaStorage
                    ->obtenerPorProyecto(
                        $idProyecto
                    )
            );


        /*==================================================
        =              ACTUALIZAR PROYECTO                 =
        ==================================================*/

        $proyectoActualizado =
            array_merge(
                $proyectoExistente,
                [
                    'id_proyecto' =>
                    $idProyecto,

                    'nombre' =>
                    trim(
                        (string) (
                            $datos['nombre']
                            ?? ''
                        )
                    ),

                    'estado' =>
                    $estado,

                    'estado_tipo' =>
                    $this->obtenerTipoEstado(
                        $estado
                    ),

                    'origen' =>
                    trim(
                        (string) (
                            $datos['origen']
                            ?? ''
                        )
                    ),

                    'descripcion' =>
                    trim(
                        (string) (
                            $datos['descripcion']
                            ?? ''
                        )
                    ),

                    'repositorio_url' =>
                    trim(
                        (string) (
                            $datos['repositorio_url']
                            ?? ''
                        )
                    ),

                    'ruta_local' =>
                    trim(
                        (string) (
                            $datos['ruta_local']
                            ?? ''
                        )
                    ),

                    'url_servidor' =>
                    trim(
                        (string) (
                            $datos['url_servidor']
                            ?? ''
                        )
                    ),

                    'id_especificacion' =>
                    (string) (
                        $datos['id_especificacion']
                        ?? ''
                    ),

                    'responsable' =>
                    trim(
                        (string) (
                            $datos['responsable']
                            ?? ''
                        )
                    ),

                    'observaciones' =>
                    trim(
                        (string) (
                            $datos['observaciones']
                            ?? ''
                        )
                    ),

                    'total_sistemas' =>
                    $totalSistemas,
                ]
            );


        /*==================================================
        =                  GUARDAR                        =
        ==================================================*/

        $proyectos[$indiceProyecto] =
            $proyectoActualizado;

        $this->storage
            ->guardarTodos(
                $proyectos
            );


        /*==================================================
        =              REGISTRAR ACTIVIDAD                 =
        ==================================================*/

        $this->registrarActividad(
            'Editó',
            $idProyecto,
            'Editó el proyecto "'
                . (
                    $proyectoActualizado['nombre']
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


        /*==================================================
        =              REGISTRAR ACTIVIDAD                 =
        ==================================================*/

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


        /*==================================================
        =                  RESPUESTA                       =
        ==================================================*/

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


        /*==================================================
        =              REGISTRAR ACTIVIDAD                 =
        ==================================================*/

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


        /*==================================================
        =                  RESPUESTA                       =
        ==================================================*/

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
        =          DATOS ANTES DE ELIMINAR                 =
        ==================================================*/

        $proyectos =
            $this->storage
            ->obtenerTodos();

        $proyectoEncontrado =
            null;

        foreach ($proyectos as $proyecto) {

            if (
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                ) === $idProyecto
            ) {
                $proyectoEncontrado =
                    $proyecto;

                break;
            }
        }


        /*==================================================
        =                  ELIMINAR                        =
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

        $nombreProyecto =
            $proyectoEncontrado['nombre']
            ?? 'Proyecto';

        $this->registrarActividad(
            'Eliminó',
            $idProyecto,
            'Eliminó el proyecto "'
                . $nombreProyecto
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
                'Proyecto eliminado correctamente.',
            ]);
    }



    /*==================================================
    =            GUARDAR ESPECIFICACIÓN                =
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


        /*==================================================
        =                  VALIDAR CÓDIGO                  =
        ==================================================*/

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
        =             OBTENER ESPECIFICACIONES             =
        ==================================================*/

        $especificaciones =
            $this->especificacionStorage
            ->obtenerTodos();


        /*==================================================
        =              VALIDAR CÓDIGO ÚNICO               =
        ==================================================*/

        foreach ($especificaciones as $especificacion) {

            $codigoExistente =
                strtolower(
                    trim(
                        (string) (
                            $especificacion['codigo']
                            ?? ''
                        )
                    )
                );

            if (
                $codigoExistente ===
                strtolower($codigo)
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
        }


        /*==================================================
        =               CONSTRUIR REGISTRO                 =
        ==================================================*/

        $especificacion = [

            'id_especificacion' =>
                $this->especificacionStorage
                ->generarNuevoId(
                    $especificaciones
                ),

            'codigo' =>
                $codigo,

            'framework' =>
                trim(
                    (string) (
                        $datos['framework']
                        ?? ''
                    )
                ),

            'version_framework' =>
                trim(
                    (string) (
                        $datos['version_framework']
                        ?? ''
                    )
                ),

            'php' =>
                trim(
                    (string) (
                        $datos['php']
                        ?? ''
                    )
                ),

            'base_datos' =>
                trim(
                    (string) (
                        $datos['base_datos']
                        ?? ''
                    )
                ),

            'repositorio' =>
                trim(
                    (string) (
                        $datos['repositorio']
                        ?? ''
                    )
                ),

            'entorno_local' =>
                trim(
                    (string) (
                        $datos['entorno_local']
                        ?? ''
                    )
                ),
        ];


        /*==================================================
        =                    GUARDAR                       =
        ==================================================*/

        $especificaciones[] =
            $especificacion;

        $this->especificacionStorage
            ->guardarTodos(
                $especificaciones
            );


        /*==================================================
        =                   RESPUESTA                      =
        ==================================================*/

        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'mensaje' =>
                    'Ficha técnica registrada correctamente.',

                'especificacion' =>
                    $especificacion,
            ]);
    }


    /*==================================================
    =            OBTENER ESPECIFICACIÓN                =
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


        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'especificacion' =>
                    $especificacion,
            ]);
    }


    /*==================================================
    =              REGISTRAR ACTIVIDAD                 =
    ==================================================*/

    private function registrarActividad(
        string $accion,
        int $idProyecto,
        string $detalle
    ): void {
        try {

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


    /*==================================================
    =              TIPO DE ESTADO                     =
    ==================================================*/

    private function obtenerTipoEstado(
        string $estado
    ): string {
        return match ($estado) {

            'Producción' =>
            'produccion',

            'Desarrollo' =>
            'desarrollo',

            'Detenido' =>
            'detenido',

            'Mantenimiento' =>
            'mantenimiento',

            default =>
            'inactivo',
        };
    }
}
