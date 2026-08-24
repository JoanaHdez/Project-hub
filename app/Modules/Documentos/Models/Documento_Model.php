<?php

namespace App\Modules\Documentos\Models;

use CodeIgniter\Model;

class Documento_Model extends Model
{
    protected $table =
        'documentos';

    protected $primaryKey =
        'id_documento';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_sistema',
        'id_usuario_creador',
        'nombre_original',
        'nombre_archivo',
        'tipo_mime',
        'extension',
        'tamano',
        'ruta_archivo',
        'descripcion',
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
    =            OBTENER TODOS COMPLETOS             =
    ==================================================*/

    public function obtenerTodosCompletos(): array
    {
        return $this
            ->select(
                '
                documentos.*,
                sistemas.nombre AS sistema_nombre,
                sistemas.id_proyecto,
                proyectos.nombre AS proyecto_nombre
                '
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = documentos.id_sistema',
                'left'
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->orderBy(
                'documentos.id_documento',
                'ASC'
            )
            ->findAll();
    }


    /*==================================================
    =             OBTENER COMPLETO POR ID            =
    ==================================================*/

    public function obtenerCompletoPorId(
        int $idDocumento
    ): ?array {

        $documento =
            $this
            ->select(
                '
                documentos.*,
                sistemas.nombre AS sistema_nombre,
                sistemas.id_proyecto,
                proyectos.nombre AS proyecto_nombre
                '
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = documentos.id_sistema',
                'left'
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->where(
                'documentos.id_documento',
                $idDocumento
            )
            ->first();


        return is_array($documento)
            ? $documento
            : null;
    }


    /*==================================================
    =            OBTENER POR SISTEMA                 =
    ==================================================*/

    public function obtenerCompletosPorSistema(
        int $idSistema
    ): array {

        return $this
            ->select(
                '
                documentos.*,
                sistemas.nombre AS sistema_nombre,
                sistemas.id_proyecto,
                proyectos.nombre AS proyecto_nombre
                '
            )
            ->join(
                'sistemas',
                'sistemas.id_sistema = documentos.id_sistema',
                'left'
            )
            ->join(
                'proyectos',
                'proyectos.id_proyecto = sistemas.id_proyecto',
                'left'
            )
            ->where(
                'documentos.id_sistema',
                $idSistema
            )
            ->orderBy(
                'documentos.id_documento',
                'ASC'
            )
            ->findAll();
    }
}