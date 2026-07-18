<aside class="catalogo">

    <div class="catalogo__buscador">

        <input type="search" id="buscar-api" class="input" placeholder="Buscar API...">

    </div>

    <div class="catalogo__lista">

        <?php foreach ($apis as $api): ?>

            <?= view('App\Modules\APIs\Views\components\api_selector', [

                'titulo'   => $api['nombre'],
                'proyecto' => $api['proyecto'],
                'estado'   => $api['estado'],
                'metodo'   => $api['metodo'],

                'badgeClase' => 'badge badge--success',

                'atributos' => [
                    'class' => 'api-selector',

                    'data-api-id' => $api['id'],
                    'data-api-nombre' => $api['nombre'],
                    'data-api-proyecto' => $api['proyecto'],
                    'data-api-descripcion' => $api['descripcion'],
                    'data-api-estado' => $api['estado'],
                    'data-api-metodo' => $api['metodo'],
                    'data-api-endpoint' => $api['endpoint'],
                    'data-api-url' => $api['url'],
                    'data-api-autenticacion' => $api['autenticacion'],

                    'data-api-repositorio' => $api['repositorio'],
                    'data-api-ruta' => $api['ruta_local'],
                    'data-api-servidor' => $api['servidor'],

                    'data-api-headers' => json_encode(
                        $api['headers'] ?? [],
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-parametros' => json_encode(
                        $api['parametros'] ?? [],
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-ejemplo' => json_encode(
                        $api['ejemplo'] ?? [],
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                    ),

                    'data-api-respuestas' => json_encode(
                        $api['respuestas'] ?? [],
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                    ),
                ],

            ]) ?>

        <?php endforeach; ?>

    </div>

</aside>