<?php

$idBusqueda         = $idBusqueda ?? 'buscar-registro';
$nombreBusqueda     = $nombreBusqueda ?? 'buscar';
$placeholder        = $placeholder ?? 'Buscar...';
$textoBoton         = $textoBoton ?? 'Nuevo registro';
$iconoBoton         = $iconoBoton ?? '+';
$modalId            = $modalId ?? null;
$urlBoton            = $urlBoton ?? '#';
$mostrarBoton       = $mostrarBoton ?? true;

?>

<div class="barra-listado">

    <div class="barra-listado__busqueda">
        <label
            for="<?= esc($idBusqueda, 'attr') ?>"
            class="sr-only"
        >
            <?= esc($placeholder) ?>
        </label>

        <input
            type="search"
            id="<?= esc($idBusqueda, 'attr') ?>"
            name="<?= esc($nombreBusqueda, 'attr') ?>"
            placeholder="<?= esc($placeholder, 'attr') ?>"
            class="barra-listado__input"
        >
    </div>

</div>