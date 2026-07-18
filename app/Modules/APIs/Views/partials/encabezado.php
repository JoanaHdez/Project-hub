<?= view('components/ui/encabezado_modulo', [

    'titulo' => 'APIs',

    'descripcion' => 'Documentación técnica de las APIs registradas en Project Hub.',

    'accion' => view('components/ui/boton', [
        'texto' => 'Nueva API',
        'url'   => '#',
        'tipo'  => 'primario',
        'icono' => '+',
        'atributos' => [
            'id' => 'btn-nueva-api',
        ],
    ]),

]) ?>