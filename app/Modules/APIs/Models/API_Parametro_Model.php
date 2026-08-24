<?php

namespace App\Modules\APIs\Models;

use CodeIgniter\Model;

class API_Parametro_Model extends Model
{
    protected $table = 'api_parametros';

    protected $primaryKey = 'id_parametro';

    protected $returnType = 'array';

    protected $useAutoIncrement = true;

    protected $protectFields = true;

    protected $allowedFields = [
        'id_api',
        'id_tipo_parametro',
        'nombre',
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
            ->select(
                '
                api_parametros.*,
                cat_tipos_parametro.nombre AS tipo
                '
            )
            ->join(
                'cat_tipos_parametro',
                'cat_tipos_parametro.id_tipo_parametro = api_parametros.id_tipo_parametro',
                'left'
            )
            ->where(
                'api_parametros.id_api',
                $idApi
            )
            ->orderBy(
                'api_parametros.id_parametro',
                'ASC'
            )
            ->findAll();
    }
}