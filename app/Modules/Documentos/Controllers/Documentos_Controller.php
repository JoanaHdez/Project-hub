<?php

namespace App\Modules\Documentos\Controllers;

use App\Controllers\BaseController;

use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Documentos\Services\Documento_StorageService;

use App\Services\Actividad_StorageService;


class Documentos_Controller extends BaseController
{
    private Sistema_StorageService $sistemaStorage;
    private Proyecto_StorageService $proyectoStorage;
    private Documento_StorageService $documentoStorage;
    private Actividad_StorageService $actividadStorage;


    public function __construct()
    {
        $this->sistemaStorage =
            new Sistema_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();

        $this->documentoStorage =
            new Documento_StorageService();

        $this->actividadStorage =
            new Actividad_StorageService();
    }


    /*==================================================
    =                     INDEX                        =
    ==================================================*/

    public function index()
    {
        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();

        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();

        $documentos =
            $this->documentoStorage
            ->obtenerTodos();


        /*==================================================
        =              NOMBRES DE PROYECTOS                =
        ==================================================*/

        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {

            $idProyecto =
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                );

            if ($idProyecto <= 0) {
                continue;
            }

            $nombresProyectos[$idProyecto] =
                $proyecto['nombre']
                ?? 'Sin proyecto';
        }


        /*==================================================
        =              TOTAL DE DOCUMENTOS                 =
        ==================================================*/

        $totalesDocumentos = [];

        foreach ($documentos as $documento) {

            $idSistema =
                (int) (
                    $documento['id_sistema']
                    ?? 0
                );

            if ($idSistema <= 0) {
                continue;
            }

            if (
                !isset(
                    $totalesDocumentos[$idSistema]
                )
            ) {
                $totalesDocumentos[$idSistema] = 0;
            }

            $totalesDocumentos[$idSistema]++;
        }


        /*==================================================
        =        SISTEMAS QUE TIENEN DOCUMENTOS            =
        ==================================================*/

        $sistemasConDocumentos =
            array_filter(
                $sistemas,
                static function (
                    array $sistema
                ) use (
                    $totalesDocumentos
                ): bool {

                    $idSistema =
                        (int) (
                            $sistema['id_sistema']
                            ?? 0
                        );

                    return (
                        $totalesDocumentos[$idSistema]
                        ?? 0
                    ) > 0;
                }
            );


        /*==================================================
        =              PREPARAR SISTEMAS                   =
        ==================================================*/

        $sistemasVista =
            array_map(
                static function (
                    array $sistema
                ) use (
                    $nombresProyectos,
                    $totalesDocumentos
                ): array {

                    $idProyecto =
                        (int) (
                            $sistema['id_proyecto']
                            ?? 0
                        );

                    $idSistema =
                        (int) (
                            $sistema['id_sistema']
                            ?? 0
                        );

                    return array_merge(
                        $sistema,
                        [
                            'proyecto_nombre' =>
                            $nombresProyectos[$idProyecto]
                                ?? 'Sin proyecto',

                            'total_documentos' =>
                            $totalesDocumentos[$idSistema]
                                ?? 0,
                        ]
                    );
                },
                array_values(
                    $sistemasConDocumentos
                )
            );


        /*==================================================
        =                    VISTA                          =
        ==================================================*/

        return view(
            'App\Modules\Documentos\Views\index',
            [
                'sistemas' =>
                $sistemasVista,

                'documentos' =>
                $documentos,
            ]
        );
    }

    /*==================================================
    =                 NUEVO DOCUMENTO                  =
    ==================================================*/

    public function nuevo()
    {
        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();

        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();


        /*==================================================
        =              NOMBRES DE PROYECTOS                =
        ==================================================*/

        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {

            $idProyecto =
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                );

            if ($idProyecto <= 0) {
                continue;
            }

            $nombresProyectos[$idProyecto] =
                $proyecto['nombre']
                ?? 'Sin proyecto';
        }


        /*==================================================
        =              PREPARAR SISTEMAS                   =
        ==================================================*/

        $sistemasVista =
            array_map(
                static function (
                    array $sistema
                ) use (
                    $nombresProyectos
                ): array {

                    $idProyecto =
                        (int) (
                            $sistema['id_proyecto']
                            ?? 0
                        );

                    return array_merge(
                        $sistema,
                        [
                            'proyecto_nombre' =>
                            $nombresProyectos[$idProyecto]
                                ?? 'Sin proyecto',
                        ]
                    );
                },
                $sistemas
            );


        /*==================================================
        =                    VISTA                          =
        ==================================================*/

        return view(
            'App\Modules\Documentos\Views\nuevo',
            [
                'sistemas' =>
                $sistemasVista,
            ]
        );
    }

    /*==================================================
    =                CREAR DOCUMENTO                   =
    ==================================================*/

    public function crear()
    {
        /*==================================================
        =                  DATOS RECIBIDOS                  =
        ==================================================*/

        $idSistema =
            (int) (
                $this->request->getPost(
                    'id_sistema'
                )
                ?? 0
            );

        $descripcion =
            trim(
                (string) (
                    $this->request->getPost(
                        'descripcion'
                    )
                    ?? ''
                )
            );

        $archivo =
            $this->request->getFile(
                'archivo'
            );


        /*==================================================
        =                VALIDAR SISTEMA                   =
        ==================================================*/

        if ($idSistema <= 0) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'Selecciona un sistema válido.',
                ]);
        }

        $sistema =
            $this->sistemaStorage
            ->obtenerPorId(
                $idSistema
            );

        if (!$sistema) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'El sistema seleccionado no existe.',
                ]);
        }


        /*==================================================
        =                VALIDAR ARCHIVO                   =
        ==================================================*/

        if (
            !$archivo ||
            !$archivo->isValid() ||
            $archivo->hasMoved()
        ) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'Selecciona un archivo válido.',
                ]);
        }


        /*==================================================
        =              DATOS DEL ARCHIVO                   =
        ==================================================*/

        $nombreOriginal =
            $archivo->getClientName();

        $extension =
            strtolower(
                $archivo->getClientExtension()
            );

        $tamano =
            $archivo->getSize();


        /*==================================================
        =              EXTENSIONES PERMITIDAS              =
        ==================================================*/

        $extensionesPermitidas = [
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'csv',
            'txt',
            'sql',
            'json',
            'xml',
            'zip',
            'rar',
            '7z',
        ];

        if (
            !in_array(
                $extension,
                $extensionesPermitidas,
                true
            )
        ) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'El tipo de archivo seleccionado no está permitido.',
                ]);
        }


        /*==================================================
        =                VALIDAR TAMAÑO                    =
        ==================================================*/

        $tamanoMaximo =
            25 * 1024 * 1024;

        if ($tamano > $tamanoMaximo) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'El archivo no puede superar los 25 MB.',
                ]);
        }


        /*==================================================
        =              OBTENER DOCUMENTOS                  =
        ==================================================*/

        $documentos =
            $this->documentoStorage
            ->obtenerTodos();

        $idDocumento =
            $this->documentoStorage
            ->generarNuevoId(
                $documentos
            );


        /*==================================================
        =              PREPARAR DIRECTORIO                 =
        ==================================================*/

        $rutaRelativa =
            'uploads/documentos/'
            . $idSistema;

        $rutaDirectorio =
            FCPATH
            . $rutaRelativa;

        if (!is_dir($rutaDirectorio)) {

            if (
                !mkdir(
                    $rutaDirectorio,
                    0775,
                    true
                ) &&
                !is_dir($rutaDirectorio)
            ) {
                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'ok' => false,
                        'mensaje' =>
                        'No fue posible preparar el directorio del documento.',
                    ]);
            }
        }


        /*==================================================
        =              NOMBRE INTERNO                      =
        ==================================================*/

        $nombreArchivo =
            'documento-'
            . $idDocumento
            . '-'
            . time()
            . '.'
            . $extension;


        /*==================================================
        =                  MOVER ARCHIVO                   =
        ==================================================*/

        try {

            $archivo->move(
                $rutaDirectorio,
                $nombreArchivo
            );
        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al guardar documento: {mensaje}',
                [
                    'mensaje' =>
                    $error->getMessage(),
                ]
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'No fue posible guardar el archivo.',
                ]);
        }


        /*==================================================
        =              CREAR REGISTRO                      =
        ==================================================*/

        $documento = [
            'id_documento' =>
            $idDocumento,

            'id_sistema' =>
            $idSistema,

            'nombre_original' =>
            $nombreOriginal,

            'nombre_archivo' =>
            $nombreArchivo,

            'ruta' =>
            $rutaRelativa
                . '/'
                . $nombreArchivo,

            'tipo' =>
            strtoupper(
                $extension
            ),

            'extension' =>
            $extension,

            'tamano' =>
            $tamano,

            'descripcion' =>
            $descripcion,

            'fecha_subida' =>
            date('Y-m-d H:i:s'),
        ];


        /*==================================================
        =              GUARDAR REGISTRO                    =
        ==================================================*/

        $documentos[] =
            $documento;

        try {

            $this->documentoStorage
                ->guardarTodos(
                    $documentos
                );
        } catch (\Throwable $error) {

            /*
         * Si falla el registro, eliminamos el archivo
         * para no dejar archivos huérfanos.
         */

            $rutaFisica =
                $rutaDirectorio
                . DIRECTORY_SEPARATOR
                . $nombreArchivo;

            if (is_file($rutaFisica)) {
                @unlink($rutaFisica);
            }

            log_message(
                'error',
                'Error al registrar documento: {mensaje}',
                [
                    'mensaje' =>
                    $error->getMessage(),
                ]
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' => false,
                    'mensaje' =>
                    'No fue posible registrar el documento.',
                ]);
        }

        /*==================================================
        =              REGISTRAR ACTIVIDAD                 =
        ==================================================*/

        try {

            $nombreSistema =
                $sistema['nombre']
                ?? 'Sistema';

            $this->actividadStorage
                ->registrar([
                    'bloque' =>
                    'Documentos',

                    'accion' =>
                    'Agregó',

                    'entidad_tipo' =>
                    'Documento',

                    'entidad_id' =>
                    $idDocumento,

                    'detalle' =>
                    'Agregó el archivo "'
                        . $nombreOriginal
                        . '" al sistema "'
                        . $nombreSistema
                        . '".',
                ]);
        } catch (\Throwable $error) {

                /*
        * La auditoría no debe impedir
        * que una operación ya realizada
        * sea considerada exitosa.
        */

            log_message(
                'error',
                'No fue posible registrar la actividad del documento {id}: {mensaje}',
                [
                    'id' =>
                    $idDocumento,

                    'mensaje' =>
                    $error->getMessage(),
                ]
            );
        }

        /*==================================================
        =                  RESPUESTA                       =
        ==================================================*/

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'El documento fue subido correctamente.',

                'documento' =>
                $documento,

                'id_sistema' =>
                $idSistema,
            ]);
    }

    /*==================================================
    =              DOCUMENTOS POR SISTEMA              =
    ==================================================*/

    public function sistema(
        int $idSistema
    ) {
        $sistema =
            $this->sistemaStorage
            ->obtenerPorId(
                $idSistema
            );

        if ($sistema === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'El sistema solicitado no existe.'
            );
        }

        $documentos =
            $this->documentoStorage
            ->obtenerPorSistema(
                $idSistema
            );

        $proyectoNombre =
            'Sin proyecto';

        $idProyecto =
            (int) (
                $sistema['id_proyecto']
                ?? 0
            );

        if ($idProyecto > 0) {

            foreach (
                $this->proyectoStorage
                    ->obtenerTodos() as $proyecto
            ) {
                if (
                    (int) (
                        $proyecto['id_proyecto']
                        ?? 0
                    ) === $idProyecto
                ) {
                    $proyectoNombre =
                        $proyecto['nombre']
                        ?? 'Sin proyecto';

                    break;
                }
            }
        }

        $sistema['proyecto_nombre'] =
            $proyectoNombre;

        return view(
            'App\Modules\Documentos\Views\sistema',
            [
                'sistema' =>
                $sistema,

                'documentos' =>
                $documentos,
            ]
        );
    }

    /*==================================================
    =                ELIMINAR DOCUMENTO                =
    ==================================================*/

    public function eliminar(
        int $idDocumento
    ) {
        /*==================================================
        =              BUSCAR DOCUMENTO                    =
        ==================================================*/

        $documentos =
            $this->documentoStorage
            ->obtenerTodos();

        $documentoEncontrado =
            null;

        foreach ($documentos as $documento) {

            if (
                (int) (
                    $documento['id_documento']
                    ?? 0
                ) === $idDocumento
            ) {
                $documentoEncontrado =
                    $documento;

                break;
            }
        }


        /*==================================================
        =              VALIDAR EXISTENCIA                  =
        ==================================================*/

        if ($documentoEncontrado === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'El documento seleccionado no existe.',
                ]);
        }


        /*==================================================
        =              DATOS IMPORTANTES                   =
        ==================================================*/

        $idSistema =
            (int) (
                $documentoEncontrado['id_sistema']
                ?? 0
            );

        $rutaRelativa =
            (string) (
                $documentoEncontrado['ruta']
                ?? ''
            );


        /*==================================================
        =              QUITAR DEL REGISTRO                 =
        ==================================================*/

        $documentosActualizados =
            array_values(
                array_filter(
                    $documentos,
                    static fn(array $documento): bool =>
                    (int) (
                        $documento['id_documento']
                        ?? 0
                    ) !== $idDocumento
                )
            );


        /*==================================================
        =              GUARDAR CAMBIO                      =
        ==================================================*/

        try {

            $this->documentoStorage
                ->guardarTodos(
                    $documentosActualizados
                );
        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al eliminar registro de documento: {mensaje}',
                [
                    'mensaje' =>
                    $error->getMessage(),
                ]
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' => false,

                    'mensaje' =>
                    'No fue posible eliminar el documento.',
                ]);
        }


        /*==================================================
        =              ELIMINAR ARCHIVO FÍSICO             =
        ==================================================*/

        if ($rutaRelativa !== '') {

            $rutaFisica =
                FCPATH
                . ltrim(
                    $rutaRelativa,
                    '/\\'
                );

            if (is_file($rutaFisica)) {

                try {

                    unlink(
                        $rutaFisica
                    );
                } catch (\Throwable $error) {

                    /*
                 * El registro ya fue eliminado.
                 * Dejamos constancia en logs si el
                 * archivo físico no pudo borrarse.
                 */

                    log_message(
                        'error',
                        'No fue posible eliminar el archivo físico del documento {id}: {mensaje}',
                        [
                            'id' =>
                            $idDocumento,

                            'mensaje' =>
                            $error->getMessage(),
                        ]
                    );
                }
            }
        }


        /*==================================================
        =          DOCUMENTOS RESTANTES DEL SISTEMA        =
        ==================================================*/

        $totalDocumentos =
            count(
                array_filter(
                    $documentosActualizados,
                    static fn(array $documento): bool =>
                    (int) (
                        $documento['id_sistema']
                        ?? 0
                    ) === $idSistema
                )
            );


        /*==================================================
        =                  RESPUESTA                       =
        ==================================================*/

        return $this->response
            ->setJSON([
                'ok' => true,

                'mensaje' =>
                'El documento fue eliminado correctamente.',

                'id_documento' =>
                $idDocumento,

                'id_sistema' =>
                $idSistema,

                'total_documentos' =>
                $totalDocumentos,
            ]);
    }

    /*==================================================
    =          NUEVO DOCUMENTO PARA SISTEMA            =
    ==================================================*/

    public function nuevoSistema(
        int $idSistema
    ) {
        /*==================================================
        =              BUSCAR SISTEMA                     =
        ==================================================*/

        $sistemaSeleccionado =
            $this->sistemaStorage
            ->obtenerPorId(
                $idSistema
            );

        if ($sistemaSeleccionado === null) {

            throw \CodeIgniter\Exceptions\PageNotFoundException
                ::forPageNotFound(
                    'El sistema solicitado no existe.'
                );
        }


        /*==================================================
        =              OBTENER SISTEMAS                    =
        ==================================================*/

        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();

        $proyectos =
            $this->proyectoStorage
            ->obtenerTodos();


        /*==================================================
        =              NOMBRES PROYECTOS                   =
        ==================================================*/

        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {

            $idProyecto =
                (int) (
                    $proyecto['id_proyecto']
                    ?? 0
                );

            if ($idProyecto <= 0) {
                continue;
            }

            $nombresProyectos[$idProyecto] =
                $proyecto['nombre']
                ?? 'Sin proyecto';
        }


        /*==================================================
        =              PREPARAR SISTEMAS                   =
        ==================================================*/

        $sistemasVista =
            array_map(
                static function (
                    array $sistema
                ) use (
                    $nombresProyectos
                ): array {

                    $idProyecto =
                        (int) (
                            $sistema['id_proyecto']
                            ?? 0
                        );

                    return array_merge(
                        $sistema,
                        [
                            'proyecto_nombre' =>
                            $nombresProyectos[$idProyecto]
                                ?? 'Sin proyecto',
                        ]
                    );
                },
                $sistemas
            );


        /*==================================================
        =                    VISTA                         =
        ==================================================*/

        return view(
            'App\Modules\Documentos\Views\nuevo',
            [
                'sistemas' =>
                $sistemasVista,

                'idSistemaSeleccionado' =>
                $idSistema,
            ]
        );
    }
}
