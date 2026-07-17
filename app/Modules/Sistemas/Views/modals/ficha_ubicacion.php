<?php

$contenido = '

' .

view('components/ui/ficha', [
    'titulo' => 'Información general',
    'campos' => [
        [
            'etiqueta' => 'Proyecto',
            'valor' => '—',
            'id' => 'ficha-proyecto',
        ],
        [
            'etiqueta' => 'Estado',
            'valor' => '—',
            'id' => 'ficha-estado',
        ],
    ],
], ['saveData' => false])

.

view('components/ui/ficha', [
    'titulo' => 'Ubicación',
    'campos' => [
        [
            'etiqueta' => 'Repositorio Git',
            'valor' => '—',
            'id' => 'ficha-repositorio',
        ],
        [
            'etiqueta' => 'Ruta local',
            'valor' => '—',
            'id' => 'ficha-ruta',
        ],
        [
            'etiqueta' => 'Servidor',
            'valor' => '—',
            'id' => 'ficha-servidor',
        ],
    ],
], ['saveData' => false]);

$acciones = '

<button
    type="button"
    class="boton boton--primario"
    data-modal-cerrar
>
    Cerrar
</button>

';

echo view('components/ui/modal', [
    'id'        => 'modal-ficha-ubicacion',
    'titulo'    => 'Ficha de ubicación',
    'tamano'    => 'mediano',
    'contenido' => $contenido,
    'acciones'  => $acciones,
], [
    'saveData' => false,
]);