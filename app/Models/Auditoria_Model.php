<?php

namespace App\Models;

use CodeIgniter\Model;

class Auditoria_Model extends Model
{
    protected $table =
        'auditoria';

    protected $primaryKey =
        'id_auditoria';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_usuario',
        'bloque',
        'accion',
        'entidad_tipo',
        'entidad_id',
        'detalle',
    ];

    protected $useTimestamps =
        false;


    /*==================================================
    =              OBTENER TODAS                      =
    ==================================================*/

    public function obtenerTodas(): array
    {
        return $this
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
            ->orderBy(
                'auditoria.created_at',
                'DESC'
            )
            ->orderBy(
                'auditoria.id_auditoria',
                'DESC'
            )
            ->findAll();
    }


    /*==================================================
    =              OBTENER RECIENTES                  =
    ==================================================*/

    public function obtenerRecientes(
        int $limite = 10
    ): array {

        $limite =
            max(
                1,
                $limite
            );


        return $this
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
            ->orderBy(
                'auditoria.created_at',
                'DESC'
            )
            ->orderBy(
                'auditoria.id_auditoria',
                'DESC'
            )
            ->findAll(
                $limite
            );
    }
}