<?php

namespace App\Modules\Documentos\Controllers;

use App\Controllers\BaseController;

use App\Modules\Sistemas\Services\Sistema_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;
use App\Modules\Documentos\Services\Documento_StorageService;


class Documentos_Controller extends BaseController
{
    private Sistema_StorageService $sistemaStorage;
    private Proyecto_StorageService $proyectoStorage;
    private Documento_StorageService $documentoStorage;


    public function __construct()
    {
        $this->sistemaStorage =
            new Sistema_StorageService();

        $this->proyectoStorage =
            new Proyecto_StorageService();

        $this->documentoStorage =
            new Documento_StorageService();
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
}