<?php

namespace App\Modules\Sistemas\Models;

use CodeIgniter\Model;

class Sistema_Model extends Model
{
    protected $table =
        'sistemas';

    protected $primaryKey =
        'id_sistema';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_proyecto',
        'id_usuario_creador',
        'nombre',
        'id_estado',
        'id_tipo_sistema',
        'id_modo_visualizacion',
        'descripcion',
        'url',
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
    =           OBTENER SISTEMAS COMPLETOS            =
    ==================================================*/

    public function obtenerTodosCompletos(): array
    {
        return $this
            ->select(
                '
                sistemas.*,
                proyectos.nombre AS proyecto_nombre,
                cat_estados.nombre AS estado,
                cat_tipos_sistema.nombre AS tipo,
                cat_modos_visualizacion.nombre AS modo_visualizacion
                '
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->join(
                'cat_estados',
                'cat_estados.id_estado = sistemas.id_estado',
                'left'
            )
            ->join(
                'cat_tipos_sistema',
                'cat_tipos_sistema.id_tipo_sistema = sistemas.id_tipo_sistema',
                'left'
            )
            ->join(
                'cat_modos_visualizacion',
                'cat_modos_visualizacion.id_modo_visualizacion = sistemas.id_modo_visualizacion',
                'left'
            )
            ->orderBy(
                'sistemas.id_sistema',
                'ASC'
            )
            ->findAll();
    }


    /*==================================================
    =             OBTENER SISTEMA POR ID              =
    ==================================================*/

    public function obtenerCompletoPorId(
        int $idSistema
    ): ?array {

        $sistema =
            $this
            ->select(
                '
                sistemas.*,
                proyectos.nombre AS proyecto_nombre,
                cat_estados.nombre AS estado,
                cat_tipos_sistema.nombre AS tipo,
                cat_modos_visualizacion.nombre AS modo_visualizacion
                '
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->join(
                'cat_estados',
                'cat_estados.id_estado = sistemas.id_estado',
                'left'
            )
            ->join(
                'cat_tipos_sistema',
                'cat_tipos_sistema.id_tipo_sistema = sistemas.id_tipo_sistema',
                'left'
            )
            ->join(
                'cat_modos_visualizacion',
                'cat_modos_visualizacion.id_modo_visualizacion = sistemas.id_modo_visualizacion',
                'left'
            )
            ->where(
                'sistemas.id_sistema',
                $idSistema
            )
            ->first();


        return is_array($sistema)
            ? $sistema
            : null;
    }


    /*==================================================
    =          OBTENER SISTEMAS POR PROYECTO          =
    ==================================================*/

    public function obtenerCompletosPorProyecto(
        int $idProyecto
    ): array {

        return $this
            ->select(
                '
                sistemas.*,
                proyectos.nombre AS proyecto_nombre,
                cat_estados.nombre AS estado,
                cat_tipos_sistema.nombre AS tipo,
                cat_modos_visualizacion.nombre AS modo_visualizacion
                '
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->join(
                'cat_estados',
                'cat_estados.id_estado = sistemas.id_estado',
                'left'
            )
            ->join(
                'cat_tipos_sistema',
                'cat_tipos_sistema.id_tipo_sistema = sistemas.id_tipo_sistema',
                'left'
            )
            ->join(
                'cat_modos_visualizacion',
                'cat_modos_visualizacion.id_modo_visualizacion = sistemas.id_modo_visualizacion',
                'left'
            )
            ->where(
                'sistemas.id_proyecto',
                $idProyecto
            )
            ->orderBy(
                'sistemas.id_sistema',
                'ASC'
            )
            ->findAll();
    }
}