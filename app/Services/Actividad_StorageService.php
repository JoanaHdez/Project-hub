<?php

namespace App\Services;

use App\Models\Auditoria_Model;

class Actividad_StorageService
{
    private Auditoria_Model $model;

    private const ID_USUARIO_TEMPORAL = 1;


    public function __construct()
    {
        $this->model =
            new Auditoria_Model();
    }


    /*==================================================
    =              OBTENER TODAS                     =
    ==================================================*/

    public function obtenerTodos(): array
    {
        $actividades =
            $this->model
            ->obtenerTodas();


        return array_map(
            fn(array $actividad): array =>
                $this->prepararActividad(
                    $actividad
                ),
            $actividades
        );
    }


    /*==================================================
    =              OBTENER RECIENTES                 =
    ==================================================*/

    public function obtenerRecientes(
        int $limite = 10
    ): array {

        $actividades =
            $this->model
            ->obtenerRecientes(
                $limite
            );


        return array_map(
            fn(array $actividad): array =>
                $this->prepararActividad(
                    $actividad
                ),
            $actividades
        );
    }


    /*==================================================
    =              REGISTRAR ACTIVIDAD               =
    ==================================================*/

    public function registrar(
        array $datos
    ): array {

        $bloque =
            trim(
                (string) (
                    $datos['bloque']
                    ?? ''
                )
            );


        $accion =
            trim(
                (string) (
                    $datos['accion']
                    ?? ''
                )
            );


        $detalle =
            trim(
                (string) (
                    $datos['detalle']
                    ?? ''
                )
            );


        if ($bloque === '') {

            throw new \RuntimeException(
                'El bloque de la actividad es obligatorio.'
            );
        }


        if ($accion === '') {

            throw new \RuntimeException(
                'La acción de la actividad es obligatoria.'
            );
        }


        if ($detalle === '') {

            throw new \RuntimeException(
                'El detalle de la actividad es obligatorio.'
            );
        }


        $idUsuario =
            (int) (
                $datos['id_usuario']
                ?? self::ID_USUARIO_TEMPORAL
            );


        if ($idUsuario <= 0) {

            $idUsuario =
                self::ID_USUARIO_TEMPORAL;
        }


        $entidadTipo =
            trim(
                (string) (
                    $datos['entidad_tipo']
                    ?? ''
                )
            );


        $entidadId =
            $datos['entidad_id']
            ?? null;


        if (
            $entidadId === ''
            ||
            $entidadId === null
        ) {

            $entidadId =
                null;

        } else {

            $entidadId =
                (int) $entidadId;


            if ($entidadId <= 0) {
                $entidadId = null;
            }
        }


        $idAuditoria =
            $this->model
            ->insert(
                [
                    'id_usuario' =>
                        $idUsuario,

                    'bloque' =>
                        $bloque,

                    'accion' =>
                        $accion,

                    'entidad_tipo' =>
                        $entidadTipo !== ''
                            ? $entidadTipo
                            : null,

                    'entidad_id' =>
                        $entidadId,

                    'detalle' =>
                        $detalle,
                ],
                true
            );


        if (!$idAuditoria) {

            throw new \RuntimeException(
                'No fue posible registrar la actividad.'
            );
        }


        $actividad =
            $this->model
            ->where(
                'auditoria.id_auditoria',
                (int) $idAuditoria
            )
            ->select(
                '
                auditoria.*,
                usuarios.nombre,
                usuarios.apellido_paterno,
                usuarios.apellido_materno,
                usuarios.correo
                '
            )
            ->join(
                'usuarios',
                'usuarios.id_usuario = auditoria.id_usuario',
                'left'
            )
            ->first();


        if (!is_array($actividad)) {

            throw new \RuntimeException(
                'La actividad fue registrada, pero no fue posible recuperarla.'
            );
        }


        return $this->prepararActividad(
            $actividad
        );
    }


    /*==================================================
    =              PREPARAR ACTIVIDAD                 =
    ==================================================*/

    private function prepararActividad(
        array $actividad
    ): array {

        $nombre =
            trim(
                (string) (
                    $actividad['nombre']
                    ?? ''
                )
            );


        $apellidoPaterno =
            trim(
                (string) (
                    $actividad['apellido_paterno']
                    ?? ''
                )
            );


        $apellidoMaterno =
            trim(
                (string) (
                    $actividad['apellido_materno']
                    ?? ''
                )
            );


        $usuarioNombre =
            trim(
                $nombre
                . ' '
                . $apellidoPaterno
                . ' '
                . $apellidoMaterno
            );


        if ($usuarioNombre === '') {

            $usuarioNombre =
                'Usuario actual';
        }


        return [

            /*
             * Alias compatible con el formato
             * que antes utilizaba actividad.json.
             */
            'id_actividad' =>
                (int) (
                    $actividad['id_auditoria']
                    ?? 0
                ),

            'id_usuario' =>
                (int) (
                    $actividad['id_usuario']
                    ?? 0
                ),

            'usuario_nombre' =>
                $usuarioNombre,

            'bloque' =>
                $actividad['bloque']
                ?? '',

            'accion' =>
                $actividad['accion']
                ?? '',

            'entidad_tipo' =>
                $actividad['entidad_tipo']
                ?? '',

            'entidad_id' =>
                isset(
                    $actividad['entidad_id']
                )
                    ? (
                        $actividad['entidad_id'] !== null
                            ? (int) $actividad['entidad_id']
                            : null
                    )
                    : null,

            'detalle' =>
                $actividad['detalle']
                ?? '',

            /*
             * Alias de compatibilidad:
             * antes se llamaba fecha_hora.
             */
            'fecha_hora' =>
                $actividad['created_at']
                ?? '',

            /*
             * Dejamos también los nombres reales
             * de la BD por si después los necesitas.
             */
            'id_auditoria' =>
                (int) (
                    $actividad['id_auditoria']
                    ?? 0
                ),

            'created_at' =>
                $actividad['created_at']
                ?? '',
        ];
    }
}