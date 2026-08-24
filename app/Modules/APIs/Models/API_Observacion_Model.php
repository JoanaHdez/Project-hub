<?php

namespace App\Modules\APIs\Models;

use CodeIgniter\Model;

class API_Observacion_Model extends Model
{
    protected $table =
        'api_observaciones';

    protected $primaryKey =
        'id_observacion';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_api',
        'id_tipo_observacion',
        'mensaje',
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
                api_observaciones.*,
                cat_tipos_observacion_api.nombre AS tipo
                '
            )
            ->join(
                'cat_tipos_observacion_api',
                'cat_tipos_observacion_api.id_tipo_observacion =
                 api_observaciones.id_tipo_observacion',
                'left'
            )
            ->where(
                'api_observaciones.id_api',
                $idApi
            )
            ->orderBy(
                'api_observaciones.id_observacion',
                'ASC'
            )
            ->findAll();
    }
}