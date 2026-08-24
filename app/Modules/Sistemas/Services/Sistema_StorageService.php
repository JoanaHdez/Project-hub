<?php

namespace App\Modules\Sistemas\Services;

use App\Modules\Sistemas\Models\Sistema_Model;
use CodeIgniter\Database\BaseConnection;

class Sistema_StorageService
{
    private Sistema_Model $model;

    private BaseConnection $db;

    private const ID_USUARIO_TEMPORAL = 1;


    public function __construct()
    {
        $this->model =
            new Sistema_Model();

        $this->db =
            db_connect();
    }


    /*==================================================
    =                  OBTENER TODOS                   =
    ==================================================*/

    public function obtenerTodos(): array
    {
        $sistemas =
            $this->model
            ->obtenerTodosCompletos();


        foreach (
            $sistemas
            as &$sistema
        ) {

            $sistema =
                $this->prepararSistema(
                    $sistema
                );
        }

        unset($sistema);


        return $sistemas;
    }


    /*==================================================
    =              OBTENER POR PROYECTO               =
    ==================================================*/

    public function obtenerPorProyecto(
        int $idProyecto
    ): array {

        $sistemas =
            $this->model
            ->obtenerCompletosPorProyecto(
                $idProyecto
            );


        foreach (
            $sistemas
            as &$sistema
        ) {

            $sistema =
                $this->prepararSistema(
                    $sistema
                );
        }

        unset($sistema);


        return $sistemas;
    }


    /*==================================================
    =                  OBTENER POR ID                  =
    ==================================================*/

    public function obtenerPorId(
        int $idSistema
    ): ?array {

        $sistema =
            $this->model
            ->obtenerCompletoPorId(
                $idSistema
            );


        if ($sistema === null) {
            return null;
        }


        return $this->prepararSistema(
            $sistema
        );
    }


    /*==================================================
    =                     CREAR                        =
    ==================================================*/

    public function crear(
        array $datos
    ): ?array {

        $datosBd =
            $this->prepararDatosBd(
                $datos
            );


        $datosBd['id_usuario_creador'] =
            self::ID_USUARIO_TEMPORAL;

        $datosBd['activo'] =
            1;


        $idSistema =
            $this->model
            ->insert(
                $datosBd,
                true
            );


        if (!$idSistema) {
            return null;
        }


        return $this->obtenerPorId(
            (int) $idSistema
        );
    }


    /*==================================================
    =                   ACTUALIZAR                     =
    ==================================================*/

    public function actualizar(
        int $idSistema,
        array $datos
    ): ?array {

        $existente =
            $this->model
            ->find(
                $idSistema
            );


        if (!is_array($existente)) {
            return null;
        }


        $datosBd =
            $this->prepararDatosBd(
                $datos
            );


        $actualizado =
            $this->model
            ->update(
                $idSistema,
                $datosBd
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idSistema
        );
    }


    /*==================================================
    =                  DESACTIVAR                      =
    ==================================================*/

    public function desactivar(
        int $idSistema
    ): ?array {

        $sistema =
            $this->model
            ->find(
                $idSistema
            );


        if (!is_array($sistema)) {
            return null;
        }


        $actualizado =
            $this->model
            ->update(
                $idSistema,
                [
                    'activo' =>
                    0,
                ]
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idSistema
        );
    }


    /*==================================================
    =                    ACTIVAR                       =
    ==================================================*/

    public function activar(
        int $idSistema
    ): ?array {

        $sistema =
            $this->model
            ->find(
                $idSistema
            );


        if (!is_array($sistema)) {
            return null;
        }


        $actualizado =
            $this->model
            ->update(
                $idSistema,
                [
                    'activo' =>
                    1,
                ]
            );


        if ($actualizado === false) {
            return null;
        }


        return $this->obtenerPorId(
            $idSistema
        );
    }


    /*==================================================
    =                    ELIMINAR                      =
    ==================================================*/

    public function eliminar(
        int $idSistema
    ): bool {

        $sistema =
            $this->model
            ->find(
                $idSistema
            );


        if (!is_array($sistema)) {
            return false;
        }


        return $this->model
            ->delete(
                $idSistema
            );
    }


    /*==================================================
    =              PREPARAR DATOS BD                  =
    ==================================================*/

    private function prepararDatosBd(
        array $datos
    ): array {

        $idProyecto =
            (int) (
                $datos['id_proyecto']
                ?? 0
            );


        if ($idProyecto <= 0) {

            throw new \RuntimeException(
                'El proyecto asociado es obligatorio.'
            );
        }


        $nombre =
            trim(
                (string) (
                    $datos['nombre']
                    ?? ''
                )
            );


        if ($nombre === '') {

            throw new \RuntimeException(
                'El nombre del sistema es obligatorio.'
            );
        }


        return [

            'id_proyecto' =>
            $idProyecto,

            'nombre' =>
            $nombre,

            'id_estado' =>
            $this->obtenerIdEstado(
                (string) (
                    $datos['estado']
                    ?? ''
                )
            ),

            'id_tipo_sistema' =>
            $this->obtenerIdTipoSistema(
                (string) (
                    $datos['tipo']
                    ?? 'Sistema'
                )
            ),

            'id_modo_visualizacion' =>
            $this->obtenerIdModoVisualizacion(
                (string) (
                    $datos['modo_visualizacion']
                    ?? 'registro'
                )
            ),

            'descripcion' =>
            $this->normalizarNullable(
                $datos['descripcion']
                    ?? null
            ),

            'url' =>
            $this->normalizarNullable(
                $datos['url']
                    ?? null
            ),

            'repositorio_url' =>
            $this->normalizarNullable(
                $datos['repositorio_url']
                    ?? null
            ),

            'ruta_local' =>
            $this->normalizarNullable(
                $datos['ruta_local']
                    ?? null
            ),

            'url_servidor' =>
            $this->normalizarNullable(
                $datos['url_servidor']
                    ?? null
            ),

            'responsable' =>
            $this->normalizarNullable(
                $datos['responsable']
                    ?? null
            ),

            'observaciones' =>
            $this->normalizarNullable(
                $datos['observaciones']
                    ?? null
            ),
        ];
    }


    /*==================================================
=                PREPARAR SISTEMA                 =
==================================================*/

    private function prepararSistema(
        array $sistema
    ): array {

        $estado =
            trim(
                (string) (
                    $sistema['estado']
                    ?? ''
                )
            );


        $sistema['activo'] =
            (bool) (
                $sistema['activo']
                ?? false
            );


        $sistema['estado_tipo'] =
            $this->obtenerTipoEstado(
                $estado
            );


        /*==================================================
    =           MODO DE VISUALIZACIÓN                 =
    ==================================================*/

        $modoCatalogo =
            trim(
                (string) (
                    $sistema['modo_visualizacion']
                    ?? ''
                )
            );


        /*
     * Conservamos el nombre descriptivo
     * proveniente del catálogo.
     *
     * Ej:
     * Integrado
     * Externo
     * Solo registro
     */
        $sistema['modo_visualizacion_nombre'] =
            $modoCatalogo;


        /*
     * Valor interno que utiliza actualmente
     * el formulario y JavaScript.
     */
        $sistema['modo_visualizacion'] =
            $this->obtenerModoVisualizacionFrontend(
                $modoCatalogo
            );


        $sistema['url'] =
            $sistema['url']
            ?? '';


        return $sistema;
    }

    /*==================================================
=        MODO DE VISUALIZACIÓN FRONTEND            =
==================================================*/

private function obtenerModoVisualizacionFrontend(
    string $modo
): string {

    $modo =
        mb_strtolower(
            trim(
                $modo
            ),
            'UTF-8'
        );


    return match ($modo) {

        'integrado',
        'iframe' =>
            'integrado',

        'externo',
        'enlace',
        'link' =>
            'externo',

        'solo registro',
        'solo-registro',
        'solo_registro',
        'registro' =>
            'registro',

        default =>
            'registro',
    };
}

    /*==================================================
    =                  ID DE ESTADO                    =
    ==================================================*/

    private function obtenerIdEstado(
        string $estado
    ): int {

        $estado =
            trim(
                $estado
            );


        if ($estado === '') {

            throw new \RuntimeException(
                'El estado del sistema es obligatorio.'
            );
        }


        $registro =
            $this->buscarCatalogo(
                'cat_estados',
                'id_estado',
                $estado
            );


        if ($registro === null) {

            throw new \RuntimeException(
                'El estado seleccionado no existe en el catálogo.'
            );
        }


        return $registro;
    }


    /*==================================================
    =               ID TIPO DE SISTEMA                =
    ==================================================*/

    private function obtenerIdTipoSistema(
        string $tipo
    ): int {

        $tipo =
            trim(
                $tipo
            );


        if ($tipo === '') {
            $tipo = 'Sistema';
        }


        $registro =
            $this->buscarCatalogo(
                'cat_tipos_sistema',
                'id_tipo_sistema',
                $tipo
            );


        if ($registro === null) {

            throw new \RuntimeException(
                'El tipo de sistema seleccionado no existe en el catálogo.'
            );
        }


        return $registro;
    }


    /*==================================================
    =            ID MODO DE VISUALIZACIÓN             =
    ==================================================*/

    private function obtenerIdModoVisualizacion(
        string $modo
    ): int {

        $modoNormalizado =
            mb_strtolower(
                trim(
                    $modo
                ),
                'UTF-8'
            );


        /*
         * Compatibilidad con los valores que
         * actualmente utiliza el frontend.
         */
        $modoCatalogo =
            match ($modoNormalizado) {

                'registro',
                'solo registro',
                'solo-registro',
                'solo_registro' =>
                'Solo registro',

                'integrado',
                'iframe' =>
                'Integrado',

                'externo',
                'enlace',
                'link' =>
                'Externo',

                default =>
                trim(
                    $modo
                ),
            };


        if ($modoCatalogo === '') {

            throw new \RuntimeException(
                'El modo de visualización es obligatorio.'
            );
        }


        $registro =
            $this->buscarCatalogo(
                'cat_modos_visualizacion',
                'id_modo_visualizacion',
                $modoCatalogo
            );


        if ($registro === null) {

            throw new \RuntimeException(
                'El modo de visualización seleccionado no existe en el catálogo.'
            );
        }


        return $registro;
    }


    /*==================================================
    =                 BUSCAR CATÁLOGO                  =
    ==================================================*/

    private function buscarCatalogo(
        string $tabla,
        string $campoId,
        string $nombre
    ): ?int {

        $registro =
            $this->db
            ->table(
                $tabla
            )
            ->select(
                $campoId
            )
            ->where(
                'LOWER(nombre)',
                mb_strtolower(
                    trim(
                        $nombre
                    ),
                    'UTF-8'
                )
            )
            ->get()
            ->getRowArray();


        if (
            !is_array(
                $registro
            )
            ||
            !isset(
                $registro[$campoId]
            )
        ) {
            return null;
        }


        return (int)
        $registro[$campoId];
    }


    /*==================================================
    =                 TIPO DE ESTADO                   =
    ==================================================*/

    private function obtenerTipoEstado(
        string $estado
    ): string {

        return match (mb_strtolower(
            trim(
                $estado
            ),
            'UTF-8'
        )) {

            'producción',
            'produccion' =>
            'produccion',

            'desarrollo' =>
            'desarrollo',

            'detenido' =>
            'detenido',

            'mantenimiento' =>
            'mantenimiento',

            default =>
            'inactivo',
        };
    }


    /*==================================================
    =                NORMALIZAR TEXTO                  =
    ==================================================*/

    private function normalizarNullable(
        mixed $valor
    ): ?string {

        $valor =
            trim(
                (string) (
                    $valor
                    ?? ''
                )
            );


        return $valor === ''
            ? null
            : $valor;
    }
}
