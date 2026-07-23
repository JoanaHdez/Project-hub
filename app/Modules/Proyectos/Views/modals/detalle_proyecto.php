<?php

$proyecto = [
    'nombre' => 'Extorsión',
    'estado' => 'Producción',
    'origen' => 'Trabajo',
    'descripcion' => 'Sistema para el registro y seguimiento de pláticas de prevención de extorsión.',
    'repositorio_url' => 'https://github.com/JoanaHdez/Extorsi-n.git',
    'ruta_local' => 'C:\laragon\www\ExtorsionF',
    'url_servidor' => 'https://cepyc.seguridadneza.gob.mx/ExtorsionF/public/',
    'id_especificacion' => '1',
    'responsable' => 'Joana Herrera',
    'observaciones' => 'Proyecto en producción.',
];

$contenido = view(
    'App\Modules\Proyectos\Views\forms\proyecto',
    [
        'modo' => 'detalle',
        'proyecto' => $proyecto,
    ],
    [
        'saveData' => false,
    ]
);

$acciones = '
    <button
        type="button"
        class="boton boton--secundario"
        data-modal-cerrar
    >
        Cerrar
    </button>
';

?>

<?= view(
    'components/ui/modal',
    [
        'id'        => 'modal-detalle-proyecto',
        'titulo'    => 'Detalle del proyecto',
        'tamano'    => 'grande',
        'contenido' => $contenido,
        'acciones'  => $acciones,
    ],
    [
        'saveData' => false,
    ]
) ?>