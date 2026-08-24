<?php

namespace App\Modules\APIs\Models;

use CodeIgniter\Model;

class API_Dependencia_Model extends Model
{
    protected $table =
        'api_dependencias';

    protected $primaryKey =
        'id_dependencia';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_api',
        'id_tipo_dependencia',
        'nombre',
        'descripcion',
        'id_estado_dependencia',
    ];

    protected $useTimestamps =
        true;

    protected $createdField =
        'created_at';

    protected $updatedField =
        'updated_at';

    protected $dateFormat =
        'datetime';


    public function obtenerPorApi(
        int $idApi
    ): array {

        return $this
            ->select(
                '
                api_dependencias.*,
                cat_tipos_dependencia.nombre AS tipo,
                cat_estados_dependencia.nombre AS estado
                '
            )
            ->join(
                'cat_tipos_dependencia',
                'cat_tipos_dependencia.id_tipo_dependencia =
                 api_dependencias.id_tipo_dependencia',
                'left'
            )
            ->join(
                'cat_estados_dependencia',
                'cat_estados_dependencia.id_estado_dependencia =
                 api_dependencias.id_estado_dependencia',
                'left'
            )
            ->where(
                'api_dependencias.id_api',
                $idApi
            )
            ->orderBy(
                'api_dependencias.id_dependencia',
                'ASC'
            )
            ->findAll();
    }
}