<?php

namespace App\Modules\Proyectos\Models;

use CodeIgniter\Model;

class Especificacion_Model extends Model
{
    protected $table =
        'especificaciones_tecnicas';

    protected $primaryKey =
        'id_especificacion';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_usuario_creador',
        'codigo',
        'framework',
        'version_framework',
        'php',
        'base_datos',
        'repositorio',
        'entorno_local',
    ];

    protected $useTimestamps =
        true;

    protected $createdField =
        'created_at';

    protected $updatedField =
        'updated_at';

    protected $dateFormat =
        'datetime';
}