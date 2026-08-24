<?php

namespace App\Modules\APIs\Models;

use CodeIgniter\Model;

class API_Model extends Model
{
    protected $table = 'apis';

    protected $primaryKey = 'id_api';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $protectFields = true;

    protected $allowedFields = [
        'id_proyecto',
        'id_sistema',
        'id_usuario_creador',
        'nombre',
        'id_estado',
        'id_metodo',
        'descripcion',
        'endpoint',
        'url_completa',
        'autenticacion',
        'ruta_local',
        'repositorio_url',
        'url_servidor',
        'responsable',
        'observaciones',
        'body_ejemplo',
        'arquitectura_modulo',
        'activo',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $dateFormat = 'datetime';


    /*==================================================
    =              OBTENER TODAS COMPLETAS            =
    ==================================================*/

    public function obtenerTodasCompletas(): array
    {
        return $this
            ->select(
                '
                apis.*,
                proyectos.nombre AS proyecto_nombre,
                sistemas.nombre AS sistema_nombre,
                cat_estados.nombre AS estado,
                cat_metodos_http.nombre AS metodo
                '
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = apis.id_proyecto',
                'left'
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = apis.id_sistema',
                'left'
            )
            ->join(
                'cat_estados',
                'cat_estados.id_estado = apis.id_estado',
                'left'
            )
            ->join(
                'cat_metodos_http',
                'cat_metodos_http.id_metodo = apis.id_metodo',
                'left'
            )
            ->orderBy(
                'apis.id_api',
                'ASC'
            )
            ->findAll();
    }


    /*==================================================
    =               OBTENER COMPLETA POR ID           =
    ==================================================*/

    public function obtenerCompletaPorId(
        int $idApi
    ): ?array {

        $api = $this
            ->select(
                '
                apis.*,
                proyectos.nombre AS proyecto_nombre,
                sistemas.nombre AS sistema_nombre,
                cat_estados.nombre AS estado,
                cat_metodos_http.nombre AS metodo
                '
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = apis.id_proyecto',
                'left'
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = apis.id_sistema',
                'left'
            )
            ->join(
                'cat_estados',
                'cat_estados.id_estado = apis.id_estado',
                'left'
            )
            ->join(
                'cat_metodos_http',
                'cat_metodos_http.id_metodo = apis.id_metodo',
                'left'
            )
            ->where(
                'apis.id_api',
                $idApi
            )
            ->first();

        return is_array($api)
            ? $api
            : null;
    }


    /*==================================================
    =             OBTENER POR PROYECTO                =
    ==================================================*/

    public function obtenerCompletasPorProyecto(
        int $idProyecto
    ): array {

        return $this
            ->select(
                '
                apis.*,
                proyectos.nombre AS proyecto_nombre,
                sistemas.nombre AS sistema_nombre,
                cat_estados.nombre AS estado,
                cat_metodos_http.nombre AS metodo
                '
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = apis.id_proyecto',
                'left'
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = apis.id_sistema',
                'left'
            )
            ->join(
                'cat_estados',
                'cat_estados.id_estado = apis.id_estado',
                'left'
            )
            ->join(
                'cat_metodos_http',
                'cat_metodos_http.id_metodo = apis.id_metodo',
                'left'
            )
            ->where(
                'apis.id_proyecto',
                $idProyecto
            )
            ->orderBy(
                'apis.id_api',
                'ASC'
            )
            ->findAll();
    }
}