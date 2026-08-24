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

        $documentos =
            $this->documentoStorage
            ->obtenerTodos();


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
                    $totalesDocumentos[
                        $idSistema
                    ]
                )
            ) {

                $totalesDocumentos[
                    $idSistema
                ] = 0;
            }


            $totalesDocumentos[
                $idSistema
            ]++;
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
                            $sistema[
                                'id_sistema'
                            ]
                            ?? 0
                        );


                    return (
                        $totalesDocumentos[
                            $idSistema
                        ]
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
                    $totalesDocumentos
                ): array {

                    $idSistema =
                        (int) (
                            $sistema[
                                'id_sistema'
                            ]
                            ?? 0
                        );


                    /*
                     * Sistema_Model ya obtiene
                     * proyecto_nombre mediante JOIN.
                     */
                    return array_merge(
                        $sistema,
                        [
                            'proyecto_nombre' =>
                                $sistema[
                                    'proyecto_nombre'
                                ]
                                ?? 'Sin proyecto',

                            'total_documentos' =>
                                $totalesDocumentos[
                                    $idSistema
                                ]
                                ?? 0,
                        ]
                    );
                },
                array_values(
                    $sistemasConDocumentos
                )
            );


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


        $sistemasVista =
            array_map(
                static function (
                    array $sistema
                ): array {

                    return array_merge(
                        $sistema,
                        [
                            'proyecto_nombre' =>
                                $sistema[
                                    'proyecto_nombre'
                                ]
                                ?? 'Sin proyecto',
                        ]
                    );
                },
                $sistemas
            );


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
                $this->request
                ->getPost(
                    'id_sistema'
                )
                ?? 0
            );


        $descripcion =
            trim(
                (string) (
                    $this->request
                    ->getPost(
                        'descripcion'
                    )
                    ?? ''
                )
            );


        $archivo =
            $this->request
            ->getFile(
                'archivo'
            );


        /*==================================================
        =                VALIDAR SISTEMA                   =
        ==================================================*/

        if ($idSistema <= 0) {

            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'Selecciona un sistema válido.',
                ]);
        }


        $sistema =
            $this->sistemaStorage
            ->obtenerPorId(
                $idSistema
            );


        if ($sistema === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El sistema seleccionado no existe.',
                ]);
        }


        $respuestaPermisoSistema =
            $this->validarPermisoSistema(
                $sistema,
                'agregar documentos a'
            );

        if ($respuestaPermisoSistema !== null) {
            return $respuestaPermisoSistema;
        }


        /*==================================================
        =                VALIDAR ARCHIVO                   =
        ==================================================*/

        if (
            !$archivo
            ||
            !$archivo->isValid()
            ||
            $archivo->hasMoved()
        ) {

            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'ok' =>
                        false,

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


        $tipoMime =
            $archivo->getMimeType();


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
                    'ok' =>
                        false,

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
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El archivo no puede superar los 25 MB.',
                ]);
        }


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
                )
                &&
                !is_dir(
                    $rutaDirectorio
                )
            ) {

                return $this->response
                    ->setStatusCode(500)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'No fue posible preparar el directorio del documento.',
                    ]);
            }
        }


        /*==================================================
        =          NOMBRE TEMPORAL DEL ARCHIVO             =
        ==================================================*/

        try {

            $identificadorTemporal =
                bin2hex(
                    random_bytes(8)
                );

        } catch (\Throwable $error) {

            $identificadorTemporal =
                uniqid(
                    '',
                    true
                );
        }


        $nombreTemporal =
            'documento-temp-'
            . $identificadorTemporal
            . '.'
            . $extension;


        /*==================================================
        =              MOVER ARCHIVO TEMPORAL              =
        ==================================================*/

        try {

            $archivo->move(
                $rutaDirectorio,
                $nombreTemporal
            );

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al guardar documento temporal: {mensaje}',
                [
                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible guardar el archivo.',
                ]);
        }


        $rutaTemporal =
            $rutaRelativa
            . '/'
            . $nombreTemporal;


        $rutaFisicaTemporal =
            $rutaDirectorio
            . DIRECTORY_SEPARATOR
            . $nombreTemporal;


        /*==================================================
        =        CREAR REGISTRO EN MYSQL                   =
        ==================================================*/

        try {

            $documento =
                $this->documentoStorage
                ->crear([
                    'id_sistema' =>
                        $idSistema,

                    'nombre_original' =>
                        $nombreOriginal,

                    /*
                     * Temporalmente se registra este
                     * nombre hasta que MySQL genere
                     * id_documento.
                     */
                    'nombre_archivo' =>
                        $nombreTemporal,

                    'tipo_mime' =>
                        $tipoMime,

                    'extension' =>
                        $extension,

                    'tamano' =>
                        $tamano,

                    'ruta_archivo' =>
                        $rutaTemporal,

                    'descripcion' =>
                        $descripcion,
                ]);


            if ($documento === null) {

                throw new \RuntimeException(
                    'No fue posible registrar el documento.'
                );
            }

        } catch (\Throwable $error) {

            /*
             * Si MySQL falla, eliminamos el archivo
             * temporal para evitar archivos huérfanos.
             */
            if (
                is_file(
                    $rutaFisicaTemporal
                )
            ) {

                @unlink(
                    $rutaFisicaTemporal
                );
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
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible registrar el documento.',
                ]);
        }


        /*==================================================
        =              ID GENERADO POR MYSQL                =
        ==================================================*/

        $idDocumento =
            (int) (
                $documento[
                    'id_documento'
                ]
                ?? 0
            );


        if ($idDocumento <= 0) {

            /*
             * Muy improbable, pero evitamos dejar
             * un registro inconsistente.
             */
            if (
                is_file(
                    $rutaFisicaTemporal
                )
            ) {

                @unlink(
                    $rutaFisicaTemporal
                );
            }


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible obtener el identificador del documento.',
                ]);
        }


        /*==================================================
        =            NOMBRE DEFINITIVO                     =
        ==================================================*/

        $nombreArchivo =
            'documento-'
            . $idDocumento
            . '-'
            . time()
            . '.'
            . $extension;


        $rutaFisicaFinal =
            $rutaDirectorio
            . DIRECTORY_SEPARATOR
            . $nombreArchivo;


        $rutaFinal =
            $rutaRelativa
            . '/'
            . $nombreArchivo;


        /*==================================================
        =             RENOMBRAR ARCHIVO                     =
        ==================================================*/

        try {

            $renombrado =
                rename(
                    $rutaFisicaTemporal,
                    $rutaFisicaFinal
                );


            if (!$renombrado) {

                throw new \RuntimeException(
                    'No fue posible asignar el nombre definitivo al archivo.'
                );
            }

        } catch (\Throwable $error) {

            /*
             * Si el archivo no puede finalizarse,
             * eliminamos también el registro de BD.
             */
            $this->documentoStorage
                ->eliminar(
                    $idDocumento
                );


            if (
                is_file(
                    $rutaFisicaTemporal
                )
            ) {

                @unlink(
                    $rutaFisicaTemporal
                );
            }


            log_message(
                'error',
                'Error al renombrar documento {id}: {mensaje}',
                [
                    'id' =>
                        $idDocumento,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible finalizar el archivo.',
                ]);
        }


        /*==================================================
        =            ACTUALIZAR RUTA EN MYSQL               =
        ==================================================*/

        try {

            $documentoActualizado =
                $this->documentoStorage
                ->actualizarArchivo(
                    $idDocumento,
                    [
                        'nombre_archivo' =>
                            $nombreArchivo,

                        'ruta_archivo' =>
                            $rutaFinal,

                        'tipo_mime' =>
                            $tipoMime,

                        'extension' =>
                            $extension,

                        'tamano' =>
                            $tamano,
                    ]
                );


            if ($documentoActualizado === null) {

                throw new \RuntimeException(
                    'No fue posible actualizar los datos finales del documento.'
                );
            }


            $documento =
                $documentoActualizado;

        } catch (\Throwable $error) {

            /*
             * No dejamos ni registro ni archivo
             * si la actualización final falla.
             */
            if (
                is_file(
                    $rutaFisicaFinal
                )
            ) {

                @unlink(
                    $rutaFisicaFinal
                );
            }


            $this->documentoStorage
                ->eliminar(
                    $idDocumento
                );


            log_message(
                'error',
                'Error al actualizar datos del documento {id}: {mensaje}',
                [
                    'id' =>
                        $idDocumento,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'No fue posible completar el registro del documento.',
                ]);
        }


        /*==================================================
        =              REGISTRAR ACTIVIDAD                 =
        ==================================================*/

        $this->registrarActividad(
            'Agregó',
            $idDocumento,
            'Agregó el archivo "'
                . $nombreOriginal
                . '" al sistema "'
                . (
                    $sistema[
                        'nombre'
                    ]
                    ?? 'Sistema'
                )
                . '".'
        );


        /*==================================================
        =                  RESPUESTA                       =
        ==================================================*/

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'ok' =>
                    true,

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

            throw \CodeIgniter\Exceptions\PageNotFoundException
                ::forPageNotFound(
                    'El sistema solicitado no existe.'
                );
        }


        $documentos =
            $this->documentoStorage
            ->obtenerPorSistema(
                $idSistema
            );


        /*
         * Sistema_Model ya proporciona
         * proyecto_nombre mediante JOIN.
         */
        $sistema['proyecto_nombre'] =
            $sistema[
                'proyecto_nombre'
            ]
            ?? 'Sin proyecto';


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

        $documento =
            $this->documentoStorage
            ->obtenerPorId(
                $idDocumento
            );


        if ($documento === null) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'ok' =>
                        false,

                    'mensaje' =>
                        'El documento seleccionado no existe.',
                ]);
        }


        $respuestaPermisoDocumento =
            $this->validarPermisoDocumento(
                $documento,
                'eliminar'
            );

        if ($respuestaPermisoDocumento !== null) {
            return $respuestaPermisoDocumento;
        }


        /*==================================================
        =              DATOS IMPORTANTES                   =
        ==================================================*/

        $idSistema =
            (int) (
                $documento[
                    'id_sistema'
                ]
                ?? 0
            );


        $rutaRelativa =
            (string) (
                $documento[
                    'ruta'
                ]
                ?? ''
            );


        /*==================================================
        =              ELIMINAR DE MYSQL                   =
        ==================================================*/

        try {

            $eliminado =
                $this->documentoStorage
                ->eliminar(
                    $idDocumento
                );


            if (!$eliminado) {

                return $this->response
                    ->setStatusCode(404)
                    ->setJSON([
                        'ok' =>
                            false,

                        'mensaje' =>
                            'El documento seleccionado no existe.',
                    ]);
            }

        } catch (\Throwable $error) {

            log_message(
                'error',
                'Error al eliminar documento {id}: {mensaje}',
                [
                    'id' =>
                        $idDocumento,

                    'mensaje' =>
                        $error->getMessage(),
                ]
            );


            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'ok' =>
                        false,

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
                     * Dejamos el error únicamente
                     * registrado en logs.
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
            $idSistema > 0
                ? count(
                    $this->documentoStorage
                    ->obtenerPorSistema(
                        $idSistema
                    )
                )
                : 0;


        /*==================================================
        =              REGISTRAR ACTIVIDAD                 =
        ==================================================*/

        $sistema =
            $idSistema > 0
                ? $this->sistemaStorage
                    ->obtenerPorId(
                        $idSistema
                    )
                : null;


        $this->registrarActividad(
            'Eliminó',
            $idDocumento,
            'Eliminó el archivo "'
                . (
                    $documento[
                        'nombre_original'
                    ]
                    ?? 'Documento'
                )
                . '" del sistema "'
                . (
                    $sistema[
                        'nombre'
                    ]
                    ?? 'Sistema'
                )
                . '".'
        );


        /*==================================================
        =                  RESPUESTA                       =
        ==================================================*/

        return $this->response
            ->setJSON([
                'ok' =>
                    true,

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


        $respuestaPermisoSistema =
            $this->validarPermisoSistema(
                $sistemaSeleccionado,
                'agregar documentos a'
            );

        if ($respuestaPermisoSistema !== null) {
            return $respuestaPermisoSistema;
        }


        /*==================================================
        =              OBTENER SISTEMAS                    =
        ==================================================*/

        $sistemas =
            $this->sistemaStorage
            ->obtenerTodos();


        /*==================================================
        =              PREPARAR SISTEMAS                   =
        ==================================================*/

        $sistemasVista =
            array_map(
                static function (
                    array $sistema
                ): array {

                    return array_merge(
                        $sistema,
                        [
                            'proyecto_nombre' =>
                                $sistema[
                                    'proyecto_nombre'
                                ]
                                ?? 'Sin proyecto',
                        ]
                    );
                },
                $sistemas
            );


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


    /*==================================================
    =             VALIDAR PERMISO DOCUMENTO            =
    ==================================================*/

    private function validarPermisoDocumento(
        array $documento,
        string $accion = 'modificar'
    ): ?\CodeIgniter\HTTP\ResponseInterface {

        $rolUsuario =
            (string) session()
                ->get('usuario_rol');

        $idUsuario =
            (int) session()
                ->get('id_usuario');

        $idUsuarioCreador =
            (int) (
                $documento['id_usuario_creador']
                ?? 0
            );

        if (
            $rolUsuario === 'superadministrador'
            ||
            (
                $rolUsuario === 'desarrollador'
                &&
                $idUsuario > 0
                &&
                $idUsuarioCreador === $idUsuario
            )
        ) {
            return null;
        }

        return $this->response
            ->setStatusCode(403)
            ->setJSON([
                'ok' => false,
                'mensaje' =>
                    'No tienes permisos para '
                    . $accion
                    . ' este documento.',
            ]);
    }


    /*==================================================
    =              VALIDAR PERMISO SISTEMA             =
    ==================================================*/

    private function validarPermisoSistema(
        array $sistema,
        string $accion = 'modificar'
    ): ?\CodeIgniter\HTTP\ResponseInterface {

        $rolUsuario =
            (string) session()
                ->get('usuario_rol');

        $idUsuario =
            (int) session()
                ->get('id_usuario');

        $idUsuarioCreador =
            (int) (
                $sistema['id_usuario_creador']
                ?? 0
            );

        if (
            $rolUsuario === 'superadministrador'
            ||
            (
                $rolUsuario === 'desarrollador'
                &&
                $idUsuario > 0
                &&
                $idUsuarioCreador === $idUsuario
            )
        ) {
            return null;
        }

        return $this->response
            ->setStatusCode(403)
            ->setJSON([
                'ok' => false,
                'mensaje' =>
                    'No tienes permisos para '
                    . $accion
                    . ' este sistema.',
            ]);
    }


    /*==================================================
    =              REGISTRAR ACTIVIDAD                 =
    ==================================================*/

    private function registrarActividad(
        string $accion,
        int $idDocumento,
        string $detalle
    ): void {

        try {

            $this->actividadStorage
                ->registrar([
                    'bloque' =>
                        'Documentos',

                    'accion' =>
                        $accion,

                    'entidad_tipo' =>
                        'Documento',

                    'entidad_id' =>
                        $idDocumento,

                    'detalle' =>
                        $detalle,
                ]);

        } catch (\Throwable $error) {

            /*
             * Una falla de actividad/auditoría
             * no debe revertir una operación
             * que ya fue completada.
             */
            log_message(
                'error',
                'No fue posible registrar actividad del documento {id}: {mensaje}',
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