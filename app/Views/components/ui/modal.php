<?php

$id         = $id ?? 'modal';
$titulo     = $titulo ?? 'Ventana';
$contenido  = $contenido ?? '';
$acciones   = $acciones ?? '';
$tamano     = $tamano ?? 'mediano';
$visible    = $visible ?? false;

?>

<div
    id="<?= esc($id) ?>"
    class="modal <?= $visible ? 'modal--visible' : '' ?>"
    aria-hidden="<?= $visible ? 'false' : 'true' ?>"
>
    <div class="modal__fondo" data-modal-cerrar></div>

    <div
        class="modal__contenedor modal__contenedor--<?= esc($tamano) ?>"
        role="dialog"
        aria-modal="true"
        aria-labelledby="<?= esc($id) ?>-titulo"
    >
        <header class="modal__encabezado">
            <h2 id="<?= esc($id) ?>-titulo">
                <?= esc($titulo) ?>
            </h2>

            <button
                type="button"
                class="modal__cerrar"
                aria-label="Cerrar ventana"
                data-modal-cerrar
            >
                ×
            </button>
        </header>

        <div class="modal__contenido">
            <?= $contenido ?>
        </div>

        <?php if ($acciones): ?>
            <footer class="modal__acciones">
                <?= $acciones ?>
            </footer>
        <?php endif; ?>
    </div>
</div>