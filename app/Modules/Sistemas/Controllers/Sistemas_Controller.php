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


        /*==================================================
        =              PREPARAR SISTEMAS                   =
        ==================================================*/

        $sistemas =
            array_map(
                static function (
                    array $sistema
                ): array {

                    return [

                        'id' =>
                        $sistema['id_sistema']
                            ?? null,

                        'id_proyecto' =>
                        isset(
                            $sistema['id_proyecto']
                        )
                            ? (int)
                            $sistema['id_proyecto']
                            : null,

                        'nombre' =>
                        $sistema['nombre']
                            ?? 'Sistema sin nombre',

                        /*
                         * El Model ya devuelve
                         * proyecto_nombre mediante JOIN.
                         */
                        'proyecto' =>
                        $sistema['proyecto_nombre']
                            ?? 'Sin proyecto',

                        'estado' =>
                        $sistema['estado']
                            ?? 'Sin estado',

                        'estado_tipo' =>
                        $sistema['estado_tipo']
                            ?? 'inactivo',

                        'tipo' =>
                        $sistema['tipo']
                            ?? 'Sistema',

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

                        'responsable' =>
                        $sistema['responsable']
                            ?? '',

                        'observaciones' =>
                        $sistema['observaciones']
                            ?? '',

                        'activo' =>
                        (bool) (
                            $sistema['activo']
                            ?? false
                        ),
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


        /*
         * Ahora que Proyectos ya trabaja con MySQL,
         * comprobamos que el proyecto exista realmente.
         */
        $proyecto =
            $this->proyectoStorage
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
                    'El proyecto asociado no existe.',
                ]);
        }


        try {

            /*==================================================
            =                    CREAR                         =
            ==================================================*/

            $sistema =
                $this->storage
                ->crear(
                    $datos
                );


            if ($sistema === null) {

                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'ok' =>
                        false,

                        'mensaje' =>
                        'No fue posible registrar el sistema.',
                    ]);
            }


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
                (int) (
                    $sistema['id_sistema']
                    ?? 0
                ),
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
        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al registrar sistema: {mensaje}',
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
                        ?: 'Ocurrió un error al registrar el sistema.',
                ]);
        }
    }


    /*==================================================
    =              DESACTIVAR SISTEMA                 =
    ==================================================*/

    public function desactivar(
        int $idSistema
    ) {

        try {

            $sistema =
                $this->storage
                ->desactivar(
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
            =              REGISTRAR ACTIVIDAD                =
            ==================================================*/

            $this->registrarActividad(
                'Desactivó',
                $idSistema,
                'Desactivó el sistema "'
                    . (
                        $sistema['nombre']
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
                    $sistema,
                ]);
        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al desactivar sistema {id}: {mensaje}',
                [
                    'id' =>
                    $idSistema,

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
                    'No fue posible desactivar el sistema.',
                ]);
        }
    }


    /*==================================================
    =                ACTIVAR SISTEMA                  =
    ==================================================*/

    public function activar(
        int $idSistema
    ) {

        try {

            $sistema =
                $this->storage
                ->activar(
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
            =              REGISTRAR ACTIVIDAD                =
            ==================================================*/

            $this->registrarActividad(
                'Activó',
                $idSistema,
                'Activó el sistema "'
                    . (
                        $sistema['nombre']
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
                    $sistema,
                ]);
        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al activar sistema {id}: {mensaje}',
                [
                    'id' =>
                    $idSistema,

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
                    'No fue posible activar el sistema.',
                ]);
        }
    }


    /*==================================================
    =                ELIMINAR SISTEMA                 =
    ==================================================*/

    public function eliminar(
        int $idSistema
    ) {

        /*==================================================
        =              OBTENER SISTEMA                    =
        ==================================================*/

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


        $idProyecto =
            (int) (
                $sistema['id_proyecto']
                ?? 0
            );


        try {

            /*==================================================
            =                   ELIMINAR                       =
            ==================================================*/

            $eliminado =
                $this->storage
                ->eliminar(
                    $idSistema
                );


            if (!$eliminado) {

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
                    'Sistema eliminado correctamente.',

                    'id_sistema' =>
                    $idSistema,

                    'id_proyecto' =>
                    $idProyecto,

                    'total_sistemas' =>
                    $totalSistemasProyecto,
                ]);
        } catch (\Throwable $error) {

            /*
             * MySQL protegerá el sistema cuando existan
             * registros dependientes mediante sus
             * llaves foráneas.
             */
            log_message(
                'error',
                'No fue posible eliminar el sistema {id}: {mensaje}',
                [
                    'id' =>
                    $idSistema,

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
                    'No se puede eliminar el sistema porque existen registros asociados.',
                ]);
        }
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


        /*
         * Sistema_Model ya obtiene proyecto_nombre
         * directamente mediante JOIN con proyectos.
         */
        if (
            !isset(
                $sistema['proyecto_nombre']
            )
            ||
            trim(
                (string)
                $sistema['proyecto_nombre']
            ) === ''
        ) {

            $sistema['proyecto_nombre'] =
                'Sin proyecto';
        }


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
        =              VALIDAR EXISTENCIA                  =
        ==================================================*/

        $sistemaExistente =
            $this->storage
            ->obtenerPorId(
                $idSistema
            );


        if ($sistemaExistente === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                    false,

                    'mensaje' =>
                    'No se encontró el sistema solicitado.',
                ]);
        }


        /*
         * El formulario actual no necesariamente
         * vuelve a enviar id_proyecto al editar.
         *
         * Si no llega, conservamos el proyecto
         * al que ya pertenece el sistema.
         */
        if (
            !isset(
                $datos['id_proyecto']
            )
            ||
            (int) $datos['id_proyecto'] <= 0
        ) {

            $datos['id_proyecto'] =
                (int) (
                    $sistemaExistente['id_proyecto']
                    ?? 0
                );
        }


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
                    'El sistema debe estar asociado a un proyecto.',
                ]);
        }


        /*==================================================
        =            VALIDAR PROYECTO REAL                 =
        ==================================================*/

        $proyecto =
            $this->proyectoStorage
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
                    'El proyecto asociado no existe.',
                ]);
        }


        try {

            /*==================================================
            =                  ACTUALIZAR                      =
            ==================================================*/

            $sistemaActualizado =
                $this->storage
                ->actualizar(
                    $idSistema,
                    $datos
                );


            if ($sistemaActualizado === null) {

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
        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al actualizar sistema {id}: {mensaje}',
                [
                    'id' =>
                    $idSistema,

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
                        ?: 'Ocurrió un error al actualizar el sistema.',
                ]);
        }
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

            /*
             * Actividad todavía mantiene su
             * StorageService actual.
             *
             * Lo migraremos en su etapa.
             */
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
}
