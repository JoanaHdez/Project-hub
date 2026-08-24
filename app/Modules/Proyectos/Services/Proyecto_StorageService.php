<?php

namespace App\Modules\Proyectos\Services;

use App\Modules\Proyectos\Models\Proyecto_Model;
use CodeIgniter\Database\BaseConnection;

class Proyecto_StorageService
{
    private Proyecto_Model $model;

    private BaseConnection $db;

    private const ID_USUARIO_TEMPORAL = 1;


    public function __construct()
    {
        $this->model =
            new Proyecto_Model();

        $this->db =
            db_connect();
    }


    /*==================================================
    =                  OBTENER TODOS                   =
    ==================================================*/

    public function obtenerTodos(): array
    {
        $proyectos =
            $this->model
            ->obtenerTodosCompletos();

        foreach (
            $proyectos
            as &$proyecto
        ) {
            $proyecto =
                $this->prepararProyecto(
                    $proyecto
                );
        }

        unset($proyecto);

        return $proyectos;
    }


    /*==================================================
    =                  OBTENER POR ID                  =
    ==================================================*/

    public function obtenerPorId(
        int $idProyecto
    ): ?array {

        $proyecto =
            $this->model
            ->obtenerCompletoPorId(
                $idProyecto
            );

        if ($proyecto === null) {
            return null;
        }

        return $this->prepararProyecto(
            $proyecto
        );
    }


    /*==================================================
    =                     CREAR                        =
    ==================================================*/

    public function crear(
        array $datos
    ): ?array {

        $datosBd =
            $this->prepararDatosBd(
                $datos
            );

        $datosBd['id_usuario_creador'] =
            self::ID_USUARIO_TEMPORAL;

        $datosBd['activo'] =
            1;


        $idProyecto =
            $this->model
            ->insert(
                $datosBd,
                true
            );


        if (!$idProyecto) {
            return null;
        }


        return $this->obtenerPorId(
            (int) $idProyecto
        );
    }


    /*==================================================
    =                   ACTUALIZAR                     =
    ==================================================*/

    public function actualizar(
        int $idProyecto,
        array $datos
    ): ?array {

        $existente =
            $this->model
            ->find(
                $idProyecto
            );

        if (!is_array($existente)) {
            return null;
        }


        $datosBd =
            $this->prepararDatosBd(
                $datos
            );


        $actualizado =
            $this->model
            ->update(
                $idProyecto,
                $datosBd
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idProyecto
        );
    }


    /*==================================================
    =                  DESACTIVAR                      =
    ==================================================*/

    public function desactivar(
        int $idProyecto
    ): ?array {

        $proyecto =
            $this->model
            ->find(
                $idProyecto
            );

        if (!is_array($proyecto)) {
            return null;
        }


        $actualizado =
            $this->model
            ->update(
                $idProyecto,
                [
                    'activo' =>
                        0,
                ]
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idProyecto
        );
    }


    /*==================================================
    =                    ACTIVAR                       =
    ==================================================*/

    public function activar(
        int $idProyecto
    ): ?array {

        $proyecto =
            $this->model
            ->find(
                $idProyecto
            );

        if (!is_array($proyecto)) {
            return null;
        }


        $actualizado =
            $this->model
            ->update(
                $idProyecto,
                [
                    'activo' =>
                        1,
                ]
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idProyecto
        );
    }


    /*==================================================
    =                    ELIMINAR                      =
    ==================================================*/

    public function eliminar(
        int $idProyecto
    ): bool {

        $proyecto =
            $this->model
            ->find(
                $idProyecto
            );

        if (!is_array($proyecto)) {
            return false;
        }


        return $this->model
            ->delete(
                $idProyecto
            );
    }


    /*==================================================
    =              PREPARAR DATOS PARA BD             =
    ==================================================*/

    private function prepararDatosBd(
        array $datos
    ): array {

        return [

            'nombre' =>
                trim(
                    (string) (
                        $datos['nombre']
                        ?? ''
                    )
                ),

            'id_estado' =>
                $this->obtenerIdEstado(
                    (string) (
                        $datos['estado']
                        ?? ''
                    )
                ),

            'id_origen' =>
                $this->obtenerIdOrigen(
                    (string) (
                        $datos['origen']
                        ?? ''
                    )
                ),

            'descripcion' =>
                $this->normalizarNullable(
                    $datos['descripcion']
                    ?? null
                ),

            'repositorio_url' =>
                $this->normalizarNullable(
                    $datos['repositorio_url']
                    ?? null
                ),

            'ruta_local' =>
                $this->normalizarNullable(
                    $datos['ruta_local']
                    ?? null
                ),

            'url_servidor' =>
                $this->normalizarNullable(
                    $datos['url_servidor']
                    ?? null
                ),

            'id_especificacion' =>
                $this->normalizarIdNullable(
                    $datos['id_especificacion']
                    ?? null
                ),

            'responsable' =>
                trim(
                    (string) (
                        $datos['responsable']
                        ?? ''
                    )
                ),

            'observaciones' =>
                $this->normalizarNullable(
                    $datos['observaciones']
                    ?? null
                ),
        ];
    }


    /*==================================================
    =               PREPARAR PROYECTO                  =
    ==================================================*/

    private function prepararProyecto(
        array $proyecto
    ): array {

        $estado =
            trim(
                (string) (
                    $proyecto['estado']
                    ?? ''
                )
            );


        $proyecto['activo'] =
            (bool) (
                $proyecto['activo']
                ?? false
            );


        $proyecto['estado_tipo'] =
            $this->obtenerTipoEstado(
                $estado
            );


        $proyecto['id_especificacion'] =
            isset(
                $proyecto['id_especificacion']
            )
            && $proyecto['id_especificacion'] !== null
                ? (string) $proyecto['id_especificacion']
                : '';


        if (
            !isset(
                $proyecto['fecha_creacion']
            )
        ) {

            $createdAt =
                $proyecto['created_at']
                ?? null;


            $proyecto['fecha_creacion'] =
                $createdAt
                    ? date(
                        'd/m/Y',
                        strtotime(
                            (string) $createdAt
                        )
                    )
                    : '';
        }


        return $proyecto;
    }


    /*==================================================
    =                  ID DE ESTADO                    =
    ==================================================*/

    private function obtenerIdEstado(
        string $estado
    ): int {

        $estado =
            trim(
                $estado
            );


        if ($estado === '') {

            throw new \RuntimeException(
                'El estado del proyecto es obligatorio.'
            );
        }


        $registro =
            $this->db
            ->table(
                'cat_estados'
            )
            ->select(
                'id_estado'
            )
            ->where(
                'LOWER(nombre)',
                mb_strtolower(
                    $estado,
                    'UTF-8'
                )
            )
            ->get()
            ->getRowArray();


        if (
            !is_array(
                $registro
            )
            ||
            !isset(
                $registro['id_estado']
            )
        ) {

            throw new \RuntimeException(
                'El estado seleccionado no existe en el catálogo.'
            );
        }


        return (int)
            $registro['id_estado'];
    }


    /*==================================================
    =                  ID DE ORIGEN                    =
    ==================================================*/

    private function obtenerIdOrigen(
        string $origen
    ): int {

        $origen =
            trim(
                $origen
            );


        if ($origen === '') {

            throw new \RuntimeException(
                'El origen del proyecto es obligatorio.'
            );
        }


        $registro =
            $this->db
            ->table(
                'cat_origenes_proyecto'
            )
            ->select(
                'id_origen'
            )
            ->where(
                'LOWER(nombre)',
                mb_strtolower(
                    $origen,
                    'UTF-8'
                )
            )
            ->get()
            ->getRowArray();


        if (
            !is_array(
                $registro
            )
            ||
            !isset(
                $registro['id_origen']
            )
        ) {

            throw new \RuntimeException(
                'El origen seleccionado no existe en el catálogo.'
            );
        }


        return (int)
            $registro['id_origen'];
    }


    /*==================================================
    =                  TIPO DE ESTADO                  =
    ==================================================*/

    private function obtenerTipoEstado(
        string $estado
    ): string {

        return match (
            mb_strtolower(
                trim(
                    $estado
                ),
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
                'inactivo',
        };
    }


    /*==================================================
    =                NORMALIZAR TEXTO                  =
    ==================================================*/

    private function normalizarNullable(
        mixed $valor
    ): ?string {

        $valor =
            trim(
                (string) (
                    $valor
                    ?? ''
                )
            );


        return $valor === ''
            ? null
            : $valor;
    }


    /*==================================================
    =                   NORMALIZAR ID                  =
    ==================================================*/

    private function normalizarIdNullable(
        mixed $valor
    ): ?int {

        if (
            $valor === null
            ||
            $valor === ''
        ) {
            return null;
        }


        $id =
            (int) $valor;


        return $id > 0
            ? $id
            : null;
    }
}