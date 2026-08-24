<?php

namespace App\Modules\APIs\Models;

use CodeIgniter\Model;

class API_ArquitecturaComponente_Model extends Model
{
    protected $table =
        'api_arquitectura_componentes';

    protected $primaryKey =
        'id_componente';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_api',
        'id_tipo_arquitectura',
        'archivo_componente',
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
                api_arquitectura_componentes.*,
                cat_tipos_arquitectura.nombre AS tipo
                '
            )
            ->join(
                'cat_tipos_arquitectura',
                'cat_tipos_arquitectura.id_tipo_arquitectura =
                 api_arquitectura_componentes.id_tipo_arquitectura',
                'left'
            )
            ->where(
                'api_arquitectura_componentes.id_api',
                $idApi
            )
            ->orderBy(
                'api_arquitectura_componentes.id_componente',
                'ASC'
            )
            ->findAll();
    }
}