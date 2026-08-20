<?php

namespace App\Modules\Sistemas\Controllers;

use App\Controllers\BaseController;
use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Services\Actividad_StorageService;


class Sistemas_Controller extends BaseController
{
    private Sistema_StorageService $storage;
    private Proyecto_StorageService $proyectoStorage;
    private Actividad_StorageService $actividadStorage;


    public function __construct()
    {
        $this->storage =
            new Sistema_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();

        $this->actividadStorage =
            new Actividad_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $sistemasAlmacenados =
            $this->storage
            ->obtenerTodos();

        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();


        /*==================================================
        =              NOMBRES DE PROYECTOS                =
        ==================================================*/

        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {

            $idProyecto =
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                );

            if ($idProyecto <= 0) {
                continue;
            }

            $nombresProyectos[$idProyecto] =
                $proyecto['nombre']
                ?? 'Sin proyecto';
        }


        /*==================================================
        =              PREPARAR SISTEMAS                   =
        ==================================================*/

        $sistemas =
            array_map(
                static function (
                    array $sistema
                ) use (
                    $nombresProyectos
                ): array {

                    $idProyecto =
                        isset(
                            $sistema['id_proyecto']
                        )
                            ? (int) $sistema['id_proyecto']
                            : null;

                    return [
                        'id' =>
                            $sistema['id_sistema']
                            ?? null,

                        'id_proyecto' =>
                            $idProyecto,

                        'nombre' =>
                            $sistema['nombre']
                            ?? 'Sistema sin nombre',

                        'proyecto' =>
                            $idProyecto !== null
                                ? (
                                    $nombresProyectos[$idProyecto]
                                    ?? 'Sin proyecto'
                                )
                                : 'Sin proyecto',

                        'estado' =>
                            $sistema['estado']
                            ?? 'Sin estado',

                        'modo_visualizacion' =>
                            $sistema['modo_visualizacion']
                            ?? 'registro',

                        'url' =>
                            $sistema['url']
                            ?? '',

                        'repositorio' =>
                            $sistema['repositorio_url']
                            ?? '',

                        'ruta_local' =>
                            $sistema['ruta_local']
                            ?? '',

                        'servidor' =>
                            $sistema['url_servidor']
                            ?? '',
                    ];
                },
                $sistemasAlmacenados
            );


        /*==================================================
        =                    VISTA                          =
        ==================================================*/

        return view(
            'App\Modules\Sistemas\Views\index',
            [
                'title' =>
                    'Sistemas | Project Hub',

                'sistemas' =>
                    $sistemas,
            ]
        );
    }


    /*==================================================
    =              OBTENER POR PROYECTO               =
    ==================================================*/

    public function obtenerPorProyecto(
        int $idProyecto
    ) {
        $sistemas =
            $this->storage
            ->obtenerPorProyecto(
                $idProyecto
            );

        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'sistemas' =>
                    $sistemas,

                'total' =>
                    count(
                        $sistemas
                    ),
            ]);
    }


    /*==================================================
    =                 GUARDAR SISTEMA                  =
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
                        'Los datos enviados no son válidos.',
                ]);
        }


        /*==================================================
        =              PROYECTO ASOCIADO                  =
        ==================================================*/

        $idProyecto =
            (int) (
                $datos['id_proyecto']
                ?? 0
            );

        if ($idProyecto <= 0) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el proyecto asociado.',
                ]);
        }


        /*==================================================
        =              CONSTRUIR SISTEMA                   =
        ==================================================*/

        $sistemas =
            $this->storage
            ->obtenerTodos();

        $estado =
            trim(
                (string) (
                    $datos['estado']
                    ?? ''
                )
            );

        $sistema = [

            'id_sistema' =>
                $this->storage
                ->generarNuevoId(
                    $sistemas
                ),

            'id_proyecto' =>
                $idProyecto,

            'nombre' =>
                trim(
                    (string) (
                        $datos['nombre']
                        ?? ''
                    )
                ),

            'tipo' =>
                trim(
                    (string) (
                        $datos['tipo']
                        ?? 'Sistema'
                    )
                ),

            'estado' =>
                $estado,

            'estado_tipo' =>
                $this->obtenerTipoEstado(
                    $estado
                ),

            'modo_visualizacion' =>
                trim(
                    (string) (
                        $datos['modo_visualizacion']
                        ?? 'registro'
                    )
                ),

            'descripcion' =>
                trim(
                    (string) (
                        $datos['descripcion']
                        ?? ''
                    )
                ),

            'url' =>
                trim(
                    (string) (
                        $datos['url']
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

            'activo' =>
                true,
        ];


        /*==================================================
        =                  GUARDAR                        =
        ==================================================*/

        $sistemas[] =
            $sistema;

        $this->storage
            ->guardarTodos(
                $sistemas
            );


        /*==================================================
        =              TOTAL DEL PROYECTO                 =
        ==================================================*/

        $totalSistemasProyecto =
            count(
                $this->storage
                ->obtenerPorProyecto(
                    $idProyecto
                )
            );


        /*==================================================
        =              REGISTRAR ACTIVIDAD                =
        ==================================================*/

        $this->registrarActividad(
            'Agregó',
            (int) $sistema['id_sistema'],
            'Agregó el sistema "'
                . (
                    $sistema['nombre']
                    ?? 'Sistema'
                )
                . '".'
        );


        /*==================================================
        =                  RESPUESTA                      =
        ==================================================*/

        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'mensaje' =>
                    'Sistema registrado correctamente.',

                'sistema' =>
                    $sistema,

                'total_sistemas' =>
                    $totalSistemasProyecto,
            ]);
    }


    /*==================================================
    =              DESACTIVAR SISTEMA                 =
    ==================================================*/

    public function desactivar(
        int $idSistema
    ) {
        $sistemas =
            $this->storage
            ->obtenerTodos();

        foreach (
            $sistemas as
            $indice => $sistema
        ) {
            if (
                (int) (
                    $sistema['id_sistema']
                    ?? 0
                ) !== $idSistema
            ) {
                continue;
            }

            $sistemas[$indice]['activo'] =
                false;

            $this->storage
                ->guardarTodos(
                    $sistemas
                );


            /*==================================================
            =              REGISTRAR ACTIVIDAD                =
            ==================================================*/

            $this->registrarActividad(
                'Desactivó',
                $idSistema,
                'Desactivó el sistema "'
                    . (
                        $sistemas[$indice]['nombre']
                        ?? 'Sistema'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Sistema desactivado correctamente.',

                    'sistema' =>
                        $sistemas[$indice],
                ]);
        }

        return $this->response
            ->setStatusCode(404)
            ->setJSON([
                'ok' =>
                    false,

                'mensaje' =>
                    'No se encontró el sistema solicitado.',
            ]);
    }


    /*==================================================
    =                ACTIVAR SISTEMA                  =
    ==================================================*/

    public function activar(
        int $idSistema
    ) {
        $sistemas =
            $this->storage
            ->obtenerTodos();

        foreach (
            $sistemas as
            $indice => $sistema
        ) {
            if (
                (int) (
                    $sistema['id_sistema']
                    ?? 0
                ) !== $idSistema
            ) {
                continue;
            }

            $sistemas[$indice]['activo'] =
                true;

            $this->storage
                ->guardarTodos(
                    $sistemas
                );


            /*==================================================
            =              REGISTRAR ACTIVIDAD                =
            ==================================================*/

            $this->registrarActividad(
                'Activó',
                $idSistema,
                'Activó el sistema "'
                    . (
                        $sistemas[$indice]['nombre']
                        ?? 'Sistema'
                    )
                    . '".'
            );


            return $this->response
                ->setJSON([
                    'ok' =>
                        true,

                    'mensaje' =>
                        'Sistema activado correctamente.',

                    'sistema' =>
                        $sistemas[$indice],
                ]);
        }

        return $this->response
            ->setStatusCode(404)
            ->setJSON([
                'ok' =>
                    false,

                'mensaje' =>
                    'No se encontró el sistema solicitado.',
            ]);
    }


    /*==================================================
    =                ELIMINAR SISTEMA                 =
    ==================================================*/

    public function eliminar(
        int $idSistema
    ) {
        $sistemas =
            $this->storage
            ->obtenerTodos();

        $sistemaEncontrado =
            null;

        foreach ($sistemas as $sistema) {

            if (
                (int) (
                    $sistema['id_sistema']
                    ?? 0
                ) === $idSistema
            ) {
                $sistemaEncontrado =
                    $sistema;

                break;
            }
        }

        if ($sistemaEncontrado === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el sistema solicitado.',
                ]);
        }


        /*==================================================
        =              PROYECTO ASOCIADO                  =
        ==================================================*/

        $idProyecto =
            (int) (
                $sistemaEncontrado['id_proyecto']
                ?? 0
            );


        /*==================================================
        =                  ELIMINAR                       =
        ==================================================*/

        $sistemas =
            array_values(
                array_filter(
                    $sistemas,
                    static fn(array $sistema): bool =>
                        (int) (
                            $sistema['id_sistema']
                            ?? 0
                        ) !== $idSistema
                )
            );

        $this->storage
            ->guardarTodos(
                $sistemas
            );


        /*==================================================
        =              TOTAL DEL PROYECTO                 =
        ==================================================*/

        $totalSistemasProyecto =
            $idProyecto > 0
                ? count(
                    $this->storage
                    ->obtenerPorProyecto(
                        $idProyecto
                    )
                )
                : 0;


        /*==================================================
        =              REGISTRAR ACTIVIDAD                =
        ==================================================*/

        $this->registrarActividad(
            'Eliminó',
            $idSistema,
            'Eliminó el sistema "'
                . (
                    $sistemaEncontrado['nombre']
                    ?? 'Sistema'
                )
                . '".'
        );


        /*==================================================
        =                  RESPUESTA                      =
        ==================================================*/

        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'mensaje' =>
                    'Sistema eliminado correctamente.',

                'id_sistema' =>
                    $idSistema,

                'id_proyecto' =>
                    $idProyecto,

                'total_sistemas' =>
                    $totalSistemasProyecto,
            ]);
    }


    /*==================================================
    =                OBTENER SISTEMA                  =
    ==================================================*/

    public function obtener(
        int $idSistema
    ) {
        $sistema =
            $this->storage
            ->obtenerPorId(
                $idSistema
            );

        if ($sistema === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el sistema solicitado.',
                ]);
        }


        /*==================================================
        =              NOMBRE DEL PROYECTO                 =
        ==================================================*/

        $nombreProyecto =
            'Sin proyecto';

        $idProyecto =
            (int) (
                $sistema['id_proyecto']
                ?? 0
            );

        if ($idProyecto > 0) {

            foreach (
                $this->proyectoStorage
                ->obtenerTodos() as $proyecto
            ) {
                if (
                    (int) (
                        $proyecto['id_proyecto']
                        ?? 0
                    ) === $idProyecto
                ) {
                    $nombreProyecto =
                        $proyecto['nombre']
                        ?? 'Sin proyecto';

                    break;
                }
            }
        }

        $sistema['proyecto_nombre'] =
            $nombreProyecto;


        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'sistema' =>
                    $sistema,
            ]);
    }


    /*==================================================
    =               ACTUALIZAR SISTEMA                =
    ==================================================*/

    public function actualizar(
        int $idSistema
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
        =              BUSCAR SISTEMA                     =
        ==================================================*/

        $sistemas =
            $this->storage
            ->obtenerTodos();

        $indiceSistema =
            null;

        foreach (
            $sistemas as
            $indice => $sistema
        ) {
            if (
                (int) (
                    $sistema['id_sistema']
                    ?? 0
                ) === $idSistema
            ) {
                $indiceSistema =
                    $indice;

                break;
            }
        }

        if ($indiceSistema === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No se encontró el sistema solicitado.',
                ]);
        }


        /*==================================================
        =              DATOS EXISTENTES                   =
        ==================================================*/

        $sistemaActual =
            $sistemas[$indiceSistema];

        $estado =
            trim(
                (string) (
                    $datos['estado']
                    ?? ''
                )
            );


        /*==================================================
        =              ACTUALIZAR SISTEMA                 =
        ==================================================*/

        $sistemaActualizado = [
            ...$sistemaActual,

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

            'tipo' =>
                trim(
                    (string) (
                        $datos['tipo']
                        ?? 'Sistema'
                    )
                ),

            'modo_visualizacion' =>
                trim(
                    (string) (
                        $datos['modo_visualizacion']
                        ?? 'registro'
                    )
                ),

            'descripcion' =>
                trim(
                    (string) (
                        $datos['descripcion']
                        ?? ''
                    )
                ),

            'url' =>
                trim(
                    (string) (
                        $datos['url']
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
        ];


        /*==================================================
        =                  GUARDAR                        =
        ==================================================*/

        $sistemas[$indiceSistema] =
            $sistemaActualizado;

        $this->storage
            ->guardarTodos(
                $sistemas
            );


        /*==================================================
        =              REGISTRAR ACTIVIDAD                =
        ==================================================*/

        $this->registrarActividad(
            'Editó',
            $idSistema,
            'Editó el sistema "'
                . (
                    $sistemaActualizado['nombre']
                    ?? 'Sistema'
                )
                . '".'
        );


        /*==================================================
        =                  RESPUESTA                      =
        ==================================================*/

        return $this->response
            ->setJSON([
                'ok' =>
                    true,

                'mensaje' =>
                    'Sistema actualizado correctamente.',

                'sistema' =>
                    $sistemaActualizado,
            ]);
    }


    /*==================================================
    =              REGISTRAR ACTIVIDAD                =
    ==================================================*/

    private function registrarActividad(
        string $accion,
        int $idSistema,
        string $detalle
    ): void {
        try {

            $this->actividadStorage
                ->registrar([
                    'bloque' =>
                        'Sistemas',

                    'accion' =>
                        $accion,

                    'entidad_tipo' =>
                        'Sistema',

                    'entidad_id' =>
                        $idSistema,

                    'detalle' =>
                        $detalle,
                ]);

        } catch (\Throwable $error) {

            log_message(
                'error',
                'No fue posible registrar actividad del sistema {id}: {mensaje}',
                [
                    'id' =>
                        $idSistema,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );
        }
    }


    /*==================================================
    =              TIPO DE ESTADO                    =
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