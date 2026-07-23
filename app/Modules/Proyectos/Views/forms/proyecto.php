<?php

$modo = $modo ?? 'crear';

$proyecto = $proyecto ?? [
    'nombre' => '',
    'estado' => '',
    'origen' => '',
    'descripcion' => '',
    'repositorio_url' => '',
    'ruta_local' => '',
    'url_servidor' => '',
    'id_especificacion' => '',
    'responsable' => '',
    'observaciones' => '',
];

$esDetalle = $modo === 'detalle';

$idFormulario = match ($modo) {
    'editar' => 'form-editar-proyecto',
    'detalle' => 'form-detalle-proyecto',
    default => 'form-proyecto',
};

?>

<form
    id="<?= esc($idFormulario) ?>"
    method="post"
    action="#"
    autocomplete="off"
    data-modo="<?= esc($modo) ?>"
>
    <div class="formulario-proyecto">

        <?= $this->include(
            'App\Modules\Proyectos\Views\forms\proyecto\informacion_general'
            
        ) ?>

        <?= $this->include(
            'App\Modules\Proyectos\Views\forms\proyecto\ubicacion'
        ) ?>

        <?= $this->include(
            'App\Modules\Proyectos\Views\forms\proyecto\configuracion_tecnica'
        ) ?>

        <?= $this->include(
            'App\Modules\Proyectos\Views\forms\proyecto\informacion_adicional'
        ) ?>

    </div>
</form>