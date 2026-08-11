<?php

$modo = $modo ?? 'crear';

$sistema = $sistema ?? [
    'id_proyecto'        => '',
    'proyecto_nombre'    => '',
    'nombre'             => '',
    'estado'             => '',
    'tipo'               => '',
    'modo_visualizacion' => '',
    'descripcion'        => '',
    'url'                => '',
    'repositorio_url'    => '',
    'ruta_local'         => '',
    'url_servidor'       => '',
    'responsable'        => '',
    'observaciones'      => '',
];

$esDetalle = $modo === 'detalle';

$idFormulario = match ($modo) {
    'editar'  => 'form-editar-sistema',
    'detalle' => 'form-detalle-sistema',
    default   => 'form-nuevo-sistema',
};

?>

<form
    id="<?= esc($idFormulario, 'attr') ?>"
    autocomplete="off"
    data-modo="<?= esc($modo, 'attr') ?>"
>
    <input
        type="hidden"
        name="id_proyecto"
        value="<?= esc($sistema['id_proyecto'] ?? '', 'attr') ?>"
    >

    <div class="formulario-proyecto">

        <?= $this->include(
            'App\Modules\Sistemas\Views\forms\sistema\informacion_general'
        ) ?>

        <?= $this->include(
            'App\Modules\Sistemas\Views\forms\sistema\ubicacion'
        ) ?>

        <?= $this->include(
            'App\Modules\Sistemas\Views\forms\sistema\informacion_adicional'
        ) ?>

    </div>
</form>