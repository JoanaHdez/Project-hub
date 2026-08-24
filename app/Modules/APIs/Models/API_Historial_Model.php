<?php

namespace App\Modules\APIs\Models;

use CodeIgniter\Model;

class API_Historial_Model extends Model
{
    protected $table =
        'api_historial';

    protected $primaryKey =
        'id_historial';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_api',
        'version',
        'descripcion_cambio',
        'fecha',
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
            ->where(
                'id_api',
                $idApi
            )
            ->orderBy(
                'id_historial',
                'ASC'
            )
            ->findAll();
    }
}