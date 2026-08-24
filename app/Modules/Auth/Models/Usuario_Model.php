<?php

namespace App\Modules\Auth\Models;

use CodeIgniter\Model;

class Usuario_Model extends Model
{
    protected $table =
        'usuarios';

    protected $primaryKey =
        'id_usuario';

    protected $returnType =
        'array';

    protected $useAutoIncrement =
        true;

    protected $protectFields =
        true;

    protected $allowedFields = [
        'id_rol',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'password_hash',
        'activo',
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
    =                 OBTENER TODOS                   =
    ==================================================*/

    public function obtenerTodosCompletos(): array
    {
        return $this
            ->select(
                '
                usuarios.*,
                roles.nombre AS rol
                '
            )
            ->join(
                'roles',
                'roles.id_rol = usuarios.id_rol',
                'left'
            )
            ->orderBy(
                'usuarios.id_usuario',
                'ASC'
            )
            ->findAll();
    }


    /*==================================================
    =               OBTENER POR ID                    =
    ==================================================*/

    public function obtenerCompletoPorId(
        int $idUsuario
    ): ?array {

        $usuario =
            $this
            ->select(
                '
                usuarios.*,
                roles.nombre AS rol
                '
            )
            ->join(
                'roles',
                'roles.id_rol = usuarios.id_rol',
                'left'
            )
            ->where(
                'usuarios.id_usuario',
                $idUsuario
            )
            ->first();


        return is_array($usuario)
            ? $usuario
            : null;
    }


    /*==================================================
    =              OBTENER POR CORREO                 =
    ==================================================*/

    public function obtenerPorCorreo(
        string $correo
    ): ?array {

        $correo =
            strtolower(
                trim(
                    $correo
                )
            );


        if ($correo === '') {
            return null;
        }


        $usuario =
            $this
            ->select(
                '
                usuarios.*,
                roles.nombre AS rol
                '
            )
            ->join(
                'roles',
                'roles.id_rol = usuarios.id_rol',
                'left'
            )
            ->where(
                'LOWER(usuarios.correo)',
                $correo
            )
            ->first();


        return is_array($usuario)
            ? $usuario
            : null;
    }
}