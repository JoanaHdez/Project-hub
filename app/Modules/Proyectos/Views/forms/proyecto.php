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

?>

<form
    id="<?= $modo === 'editar' ? 'form-editar-proyecto' : 'form-proyecto' ?>"
    method="post"
    action="#"
    autocomplete="off"
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