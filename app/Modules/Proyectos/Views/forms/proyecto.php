<form
    id="form-proyecto"
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