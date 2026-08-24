<?php

namespace App\Modules\Modulos\Models;

use CodeIgniter\Model;

class Modulo_Model extends Model
{
    protected $table =
        'modulos';

    protected $primaryKey =
        'id_modulo';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_sistema',
        'id_usuario_creador',
        'tipo',
        'nombre',
        'descripcion',
        'url',
        'imagen',
        'activo',
    ];

    protected $useTimestamps =
        true;

    protected $createdField =
        'created_at';

    protected $updatedField =
        'updated_at';

    protected $dateFormat =
        'datetime';


    /*==================================================
    =            OBTENER TODOS COMPLETOS             =
    ==================================================*/

    public function obtenerTodosCompletos(): array
    {
        return $this
            ->select(
                '
                modulos.*,
                sistemas.nombre AS sistema_nombre,
                sistemas.id_proyecto,
                proyectos.nombre AS proyecto_nombre
                '
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = modulos.id_sistema',
                'left'
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->orderBy(
                'modulos.id_modulo',
                'ASC'
            )
            ->findAll();
    }


    /*==================================================
    =             OBTENER COMPLETO POR ID            =
    ==================================================*/

    public function obtenerCompletoPorId(
        int $idModulo
    ): ?array {

        $modulo =
            $this
            ->select(
                '
                modulos.*,
                sistemas.nombre AS sistema_nombre,
                sistemas.id_proyecto,
                proyectos.nombre AS proyecto_nombre
                '
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = modulos.id_sistema',
                'left'
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->where(
                'modulos.id_modulo',
                $idModulo
            )
            ->first();


        return is_array($modulo)
            ? $modulo
            : null;
    }


    /*==================================================
    =            OBTENER POR SISTEMA                 =
    ==================================================*/

    public function obtenerCompletosPorSistema(
        int $idSistema
    ): array {

        return $this
            ->select(
                '
                modulos.*,
                sistemas.nombre AS sistema_nombre,
                sistemas.id_proyecto,
                proyectos.nombre AS proyecto_nombre
                '
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = modulos.id_sistema',
                'left'
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->where(
                'modulos.id_sistema',
                $idSistema
            )
            ->orderBy(
                'modulos.id_modulo',
                'ASC'
            )
            ->findAll();
    }
}