<?php

namespace App\Modules\APIs\Models;

use CodeIgniter\Model;

class API_Header_Model extends Model
{
    protected $table = 'api_headers';

    protected $primaryKey = 'id_header';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $protectFields = true;

    protected $allowedFields = [
        'id_api',
        'nombre',
        'valor',
        'obligatorio',
        'descripcion',
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
                'id_header',
                'ASC'
            )
            ->findAll();
    }
}