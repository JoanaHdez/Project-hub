<?php

namespace App\Modules\Proyectos\Models;

use CodeIgniter\Model;

class Proyecto_Model extends Model
{
    protected $table =
        'proyectos';

    protected $primaryKey =
        'id_proyecto';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_usuario_creador',
        'id_especificacion',
        'nombre',
        'id_estado',
        'id_origen',
        'descripcion',
        'repositorio_url',
        'ruta_local',
        'url_servidor',
        'responsable',
        'observaciones',
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
    =          OBTENER PROYECTOS COMPLETOS            =
    ==================================================*/

    public function obtenerTodosCompletos(): array
    {
        return $this
            ->select(
                '
                proyectos.*,
                cat_estados.nombre AS estado,
                cat_origenes_proyecto.nombre AS origen,
                especificaciones_tecnicas.codigo AS codigo_especificacion
                '
            )
            ->join(
                'cat_estados',
                'cat_estados.id_estado = proyectos.id_estado',
                'left'
            )
            ->join(
                'cat_origenes_proyecto',
                'cat_origenes_proyecto.id_origen = proyectos.id_origen',
                'left'
            )
            ->join(
                'especificaciones_tecnicas',
                'especificaciones_tecnicas.id_especificacion = proyectos.id_especificacion',
                'left'
            )
            ->orderBy(
                'proyectos.id_proyecto',
                'ASC'
            )
            ->findAll();
    }


    /*==================================================
    =          OBTENER PROYECTO COMPLETO              =
    ==================================================*/

    public function obtenerCompletoPorId(
        int $idProyecto
    ): ?array {

        $proyecto =
            $this
            ->select(
                '
                proyectos.*,
                cat_estados.nombre AS estado,
                cat_origenes_proyecto.nombre AS origen,
                especificaciones_tecnicas.codigo AS codigo_especificacion
                '
            )
            ->join(
                'cat_estados',
                'cat_estados.id_estado = proyectos.id_estado',
                'left'
            )
            ->join(
                'cat_origenes_proyecto',
                'cat_origenes_proyecto.id_origen = proyectos.id_origen',
                'left'
            )
            ->join(
                'especificaciones_tecnicas',
                'especificaciones_tecnicas.id_especificacion = proyectos.id_especificacion',
                'left'
            )
            ->where(
                'proyectos.id_proyecto',
                $idProyecto
            )
            ->first();

        return is_array($proyecto)
            ? $proyecto
            : null;
    }
}