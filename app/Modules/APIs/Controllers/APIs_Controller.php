<?php

namespace App\Modules\APIs\Controllers;

use App\Controllers\BaseController;
use App\Modules\APIs\Services\API_StorageService;
use App\Modules\Proyectos\Services\Proyecto_StorageService;

class APIs_Controller extends BaseController
{
    private API_StorageService $storage;
    private Proyecto_StorageService $proyectoStorage;

    public function __construct()
    {
        $this->storage = new API_StorageService();
        $this->proyectoStorage = new Proyecto_StorageService();
    }
    public function index()
    {
        $apisAlmacenadas = $this->storage->obtenerTodos();

        $proyectos = $this->proyectoStorage->obtenerTodos();

        $nombresProyectos = [];

        foreach ($proyectos as $proyecto) {
            $idProyecto = (int) (
                $proyecto['id_proyecto'] ?? 0
            );

            if ($idProyecto <= 0) {
                continue;
            }

            $nombresProyectos[$idProyecto] =
                $proyecto['nombre'] ?? 'Sin proyecto';
        }

        $apis = array_map(
            static function (array $api) use ($nombresProyectos): array {
                $idProyecto = isset($api['id_proyecto'])
                    ? (int) $api['id_proyecto']
                    : null;

                return array_merge(
                    $api,
                    [

                        'id' =>
                        $api['id_api'] ?? null,

                        'proyecto' =>
                        $idProyecto !== null
                            ? (
                                $nombresProyectos[$idProyecto]
                                ?? 'Sin proyecto'
                            )
                            : 'Sin proyecto',

                        'repositorio' =>
                        $api['repositorio_url'] ?? '',

                        'servidor' =>
                        $api['url_servidor'] ?? '',
                    ]
                );
            },
            $apisAlmacenadas
        );

        $proyectosDisponibles = array_map(
            static function (array $proyecto): array {
                return [
                    'id_proyecto' =>
                    $proyecto['id_proyecto'] ?? null,

                    'nombre' =>
                    $proyecto['nombre'] ?? 'Proyecto sin nombre',
                ];
            },
            $proyectos
        );

        return view(
            'App\Modules\APIs\Views\index',
            [
                'title'     => 'APIs | Project Hub',
                'apis'      => $apis,
                'proyectos' => $proyectosDisponibles,
            ]
        );
    }

    public function guardar()
    {
        $datos = $this->request->getJSON(true);

        if (!is_array($datos)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'Los datos enviados no son válidos.',
                ]);
        }

        $idProyecto = (int) (
            $datos['id_proyecto'] ?? 0
        );

        if ($idProyecto <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'Debes seleccionar un proyecto.',
                ]);
        }

        $nombre = trim(
            (string) ($datos['nombre'] ?? '')
        );

        if ($nombre === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'El nombre de la API es obligatorio.',
                ]);
        }

        $estado = trim(
            (string) ($datos['estado'] ?? '')
        );

        $metodo = trim(
            (string) ($datos['metodo'] ?? '')
        );

        $endpoint = trim(
            (string) ($datos['endpoint'] ?? '')
        );

        if ($estado === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'El estado es obligatorio.',
                ]);
        }

        if ($metodo === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'El método HTTP es obligatorio.',
                ]);
        }

        if ($endpoint === '') {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'ok'      => false,
                    'mensaje' => 'El endpoint es obligatorio.',
                ]);
        }

        $apis = $this->storage->obtenerTodos();

        $idSistema = $datos['id_sistema'] ?? null;

        if (
            $idSistema === ''
            || $idSistema === null
        ) {
            $idSistema = null;
        } else {
            $idSistema = (int) $idSistema;
        }

        $api = [
            'id_api' =>
            $this->storage->generarNuevoId($apis),

            'id_proyecto' =>
            $idProyecto,

            'id_sistema' =>
            $idSistema,

            'nombre' =>
            $nombre,

            'descripcion' => trim(
                (string) ($datos['descripcion'] ?? '')
            ),

            'estado' =>
            $estado,

            'metodo' =>
            strtoupper($metodo),

            'endpoint' =>
            $endpoint,

            'url' => trim(
                (string) ($datos['url'] ?? '')
            ),

            'autenticacion' => trim(
                (string) ($datos['autenticacion'] ?? '')
            ),

            'repositorio_url' => trim(
                (string) ($datos['repositorio_url'] ?? '')
            ),

            'ruta_local' => trim(
                (string) ($datos['ruta_local'] ?? '')
            ),

            'url_servidor' => trim(
                (string) ($datos['url_servidor'] ?? '')
            ),

            'headers' =>
            is_array($datos['headers'] ?? null)
                ? $datos['headers']
                : [],

            'parametros' =>
            is_array($datos['parametros'] ?? null)
                ? $datos['parametros']
                : [],

            'ejemplo' =>
            is_array($datos['ejemplo'] ?? null)
                ? $datos['ejemplo']
                : [],

            'respuestas' =>
            is_array($datos['respuestas'] ?? null)
                ? $datos['respuestas']
                : [],

            'responsable' => trim(
                (string) ($datos['responsable'] ?? '')
            ),

            'observaciones' => trim(
                (string) ($datos['observaciones'] ?? '')
            ),

            'activo' => true,
        ];

        $apis[] = $api;

$this->storage->guardarTodos($apis);


/*
 * Resolver el nombre del proyecto para mostrarlo
 * inmediatamente en el catálogo.
 */
$nombreProyecto = 'Sin proyecto';

$proyectos = $this->proyectoStorage->obtenerTodos();

foreach ($proyectos as $proyecto) {
    if (
        (int) ($proyecto['id_proyecto'] ?? 0)
        === $idProyecto
    ) {
        $nombreProyecto =
            $proyecto['nombre'] ?? 'Sin proyecto';

        break;
    }
}


/*
 * Adaptar la API a los nombres utilizados
 * actualmente por la vista.
 */
$apiVista = array_merge(
    $api,
    [
        'id' =>
            $api['id_api'],

        'proyecto' =>
            $nombreProyecto,

        'repositorio' =>
            $api['repositorio_url'] ?? '',

        'servidor' =>
            $api['url_servidor'] ?? '',
    ]
);


/*
 * Generar el mismo componente que utiliza
 * el catálogo al cargar la página.
 */
$selectorHtml = view(
    'App\Modules\APIs\Views\components\api_selector',
    [
        'titulo' =>
            $apiVista['nombre'],

        'proyecto' =>
            $apiVista['proyecto'],

        'estado' =>
            $apiVista['estado'],

        'metodo' =>
            $apiVista['metodo'],

        'atributos' => [
            'data-api-id' =>
                $apiVista['id'],

            'data-api-nombre' =>
                $apiVista['nombre'],

            'data-api-proyecto' =>
                $apiVista['proyecto'],

            'data-api-descripcion' =>
                $apiVista['descripcion'],

            'data-api-estado' =>
                $apiVista['estado'],

            'data-api-metodo' =>
                $apiVista['metodo'],

            'data-api-endpoint' =>
                $apiVista['endpoint'],

            'data-api-url' =>
                $apiVista['url'],

            'data-api-autenticacion' =>
                $apiVista['autenticacion'],

            'data-api-repositorio' =>
                $apiVista['repositorio'],

            'data-api-ruta' =>
                $apiVista['ruta_local'],

            'data-api-servidor' =>
                $apiVista['servidor'],

            'data-api-headers' => json_encode(
                $apiVista['headers'] ?? [],
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),

            'data-api-parametros' => json_encode(
                $apiVista['parametros'] ?? [],
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),

            'data-api-ejemplo' => json_encode(
                $apiVista['ejemplo'] ?? [],
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),

            'data-api-respuestas' => json_encode(
                $apiVista['respuestas'] ?? [],
                JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
            ),
        ],
    ],
    [
        'saveData' => false,
    ]
);


return $this->response->setJSON([
    'ok'            => true,
    'mensaje'       => 'API registrada correctamente.',
    'api'           => $apiVista,
    'selector_html' => $selectorHtml,
]);
    }
}
