<?php

namespace App\Modules\Proyectos\Services;

use App\Modules\Proyectos\Models\Especificacion_Model;

class Especificacion_StorageService
{
    private Especificacion_Model $model;

    private const ID_USUARIO_TEMPORAL = 1;


    public function __construct()
    {
        $this->model =
            new Especificacion_Model();
    }


    /*==================================================
    =                  OBTENER TODAS                   =
    ==================================================*/

    public function obtenerTodos(): array
    {
        return $this->model
            ->orderBy(
                'id_especificacion',
                'ASC'
            )
            ->findAll();
    }


    /*==================================================
    =                   OBTENER POR ID                 =
    ==================================================*/

    public function obtenerPorId(
        int $idEspecificacion
    ): ?array {

        $especificacion =
            $this->model
            ->find(
                $idEspecificacion
            );

        return is_array($especificacion)
            ? $especificacion
            : null;
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


        $idEspecificacion =
            $this->model
            ->insert(
                $datosBd,
                true
            );


        if (!$idEspecificacion) {
            return null;
        }


        return $this->obtenerPorId(
            (int) $idEspecificacion
        );
    }


    /*==================================================
    =                   ACTUALIZAR                     =
    ==================================================*/

    public function actualizar(
        int $idEspecificacion,
        array $datos
    ): ?array {

        $existente =
            $this->model
            ->find(
                $idEspecificacion
            );

        if (!is_array($existente)) {
            return null;
        }


        $actualizado =
            $this->model
            ->update(
                $idEspecificacion,
                $this->prepararDatosBd(
                    $datos
                )
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idEspecificacion
        );
    }


    /*==================================================
    =                    ELIMINAR                      =
    ==================================================*/

    public function eliminar(
        int $idEspecificacion
    ): bool {

        $existente =
            $this->model
            ->find(
                $idEspecificacion
            );

        if (!is_array($existente)) {
            return false;
        }


        return $this->model
            ->delete(
                $idEspecificacion
            );
    }


    /*==================================================
    =                 CÓDIGO EXISTE                    =
    ==================================================*/

    public function existeCodigo(
        string $codigo,
        ?int $ignorarId = null
    ): bool {

        $builder =
            $this->model
            ->where(
                'LOWER(codigo)',
                mb_strtolower(
                    trim($codigo),
                    'UTF-8'
                )
            );


        if ($ignorarId !== null) {

            $builder->where(
                'id_especificacion !=',
                $ignorarId
            );
        }


        return $builder
            ->first() !== null;
    }


    /*==================================================
    =              PREPARAR DATOS PARA BD              =
    ==================================================*/

    private function prepararDatosBd(
        array $datos
    ): array {

        return [

            'codigo' =>
                trim(
                    (string) (
                        $datos['codigo']
                        ?? ''
                    )
                ),

            'framework' =>
                $this->normalizarNullable(
                    $datos['framework']
                    ?? null
                ),

            'version_framework' =>
                $this->normalizarNullable(
                    $datos['version_framework']
                    ?? null
                ),

            'php' =>
                $this->normalizarNullable(
                    $datos['php']
                    ?? null
                ),

            'base_datos' =>
                $this->normalizarNullable(
                    $datos['base_datos']
                    ?? null
                ),

            'repositorio' =>
                $this->normalizarNullable(
                    $datos['repositorio']
                    ?? null
                ),

            'entorno_local' =>
                $this->normalizarNullable(
                    $datos['entorno_local']
                    ?? null
                ),
        ];
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
}