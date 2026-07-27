<?php

$proyecto = $proyecto ?? [];

$estado = view('components/ui/estado', [
    'texto' => $proyecto['estado'] ?? 'Sin estado',
    'tipo'  => $proyecto['estado_tipo'] ?? 'inactivo',
]);

$acciones = '<div class="acciones-proyecto">';

$acciones .= view('components/ui/boton_accion', [
    'icono'   => '📄',
    'mensaje' => 'Ver detalle del proyecto',
    'url'     => '#',
    'tipo'    => 'detalle',
    'modalId' => 'modal-detalle-proyecto',
], [
    'saveData' => false,
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'      => '✏️',
    'mensaje'    => 'Editar proyecto',
    'url'        => '#',
    'tipo'       => 'editar',
    'accion'     => 'editar',
    'modalId'    => 'modal-editar-proyecto',
    'proyectoId' => $proyecto['id_proyecto'],
], [
    'saveData' => false,
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'   => '🗑️',
    'mensaje' => 'Eliminar o desactivar proyecto',
    'url'     => '#',
    'tipo'    => 'eliminar',
    'modalId' => 'modal-eliminar-proyecto',
], [
    'saveData' => false,
]);

$acciones .= view('components/ui/boton_accion', [
    'icono'   => '🌐',
    'mensaje' => 'Ver sistemas asociados',
    'url'     => '#',
    'tipo'    => 'sistema',
    'modalId' => 'modal-sistemas-asociados',
], [
    'saveData' => false,
]);

if (!empty($proyecto['repositorio_url'])) {
    $acciones .= view('components/ui/boton_accion', [
        'icono'        => '🐙',
        'mensaje'      => 'Abrir repositorio',
        'url'          => $proyecto['repositorio_url'],
        'tipo'         => 'repositorio',
        'nuevaPestana' => true,
    ], [
        'saveData' => false,
    ]);
}

$acciones .= '</div>';
?>

<tr data-id-proyecto="<?= esc($proyecto['id_proyecto'] ?? '', 'attr') ?>">
    <td><?= esc($proyecto['nombre'] ?? 'Proyecto sin nombre') ?></td>
    <td><?= $estado ?></td>
    <td><?= esc($proyecto['origen'] ?? 'Sin especificar') ?></td>
    <td><?= esc((string) ($proyecto['total_sistemas'] ?? 0)) ?></td>
    <td><?= esc($proyecto['fecha_creacion'] ?? 'Sin fecha') ?></td>
    <td><?= $acciones ?></td>
</tr>