<div
    class="modal"
    id="modal-ficha-api"
    aria-hidden="true"
>
    <div
        class="modal__fondo"
        data-modal-cerrar
    ></div>

    <div
        class="modal__contenedor modal__contenedor--mediano"
        role="dialog"
        aria-modal="true"
        aria-labelledby="titulo-modal-ficha-api"
    >
        <div class="modal__encabezado">

            <h2 id="titulo-modal-ficha-api">
                Ficha de ubicación
            </h2>

            <button
                type="button"
                class="modal__cerrar"
                data-modal-cerrar
                aria-label="Cerrar modal"
            >
                ×
            </button>

        </div>

        <div class="modal__contenido">

            <?= view('components/ui/ficha', [
                'titulo' => 'Información general',

                'campos' => [
                    [
                        'etiqueta' => 'Proyecto',
                        'id' => 'ficha-api-proyecto',
                    ],
                    [
                        'etiqueta' => 'Estado',
                        'id' => 'ficha-api-estado',
                    ],
                ],
            ]) ?>

            <?= view('components/ui/ficha', [
                'titulo' => 'Ubicación',

                'campos' => [
                    [
                        'etiqueta' => 'Repositorio Git',
                        'id' => 'ficha-api-repositorio',
                    ],
                    [
                        'etiqueta' => 'Ruta local',
                        'id' => 'ficha-api-ruta',
                    ],
                    [
                        'etiqueta' => 'Servidor',
                        'id' => 'ficha-api-servidor',
                    ],
                ],
            ]) ?>

        </div>
    </div>
</div>