<?php

namespace App\Modules\APIs\Models;

use CodeIgniter\Model;

class API_Respuesta_Model extends Model
{
    protected $table = 'api_respuestas';

    protected $primaryKey = 'id_respuesta';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $protectFields = true;

    protected $allowedFields = [
        'id_api',
        'codigo_http',
        'descripcion',
        'body',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $dateFormat = 'datetime';


    public function obtenerPorApi(
        int $idApi
    ): array {

        return $this
            ->where(
                'id_api',
                $idApi
            )
            ->orderBy(
                'id_respuesta',
                'ASC'
            )
            ->findAll();
    }
}