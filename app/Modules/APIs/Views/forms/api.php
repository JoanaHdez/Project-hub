<?php

$modo = $modo ?? 'crear';

$api = $api ?? [
    'id_proyecto'    => '',
    'id_sistema'     => '',
    'nombre'         => '',
    'descripcion'    => '',
    'estado'         => '',
    'metodo'         => '',
    'endpoint'       => '',
    'url'            => '',
    'autenticacion'  => '',
    'repositorio_url'=> '',
    'ruta_local'     => '',
    'url_servidor'   => '',
    'responsable'    => '',
    'observaciones'  => '',
];

$esDetalle = $modo === 'detalle';

$idFormulario = match ($modo) {
    'editar'  => 'form-editar-api',
    'detalle' => 'form-detalle-api',
    default   => 'form-nueva-api',
};

?>

<form
    id="<?= esc($idFormulario, 'attr') ?>"
    autocomplete="off"
    data-modo="<?= esc($modo, 'attr') ?>"
>
    <?= $this->include(
        'App\Modules\APIs\Views\forms\api\informacion_general'
    ) ?>

    <?= $this->include(
        'App\Modules\APIs\Views\forms\api\ubicacion'
    ) ?>

    <?= $this->include(
        'App\Modules\APIs\Views\forms\api\documentacion'
    ) ?>

    <?= $this->include(
        'App\Modules\APIs\Views\forms\api\informacion_adicional'
    ) ?>
</form>