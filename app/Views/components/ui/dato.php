<?php

$etiqueta = $etiqueta ?? '';
$valor = $valor ?? '—';
$id = $id ?? '';
?>

<div class="dato">

    <span class="dato__etiqueta">
        <?= esc($etiqueta) ?>
    </span>

    <strong
        class="dato__valor"
        <?= $id !== '' ? 'id="'.esc($id, 'attr').'"' : '' ?>
    >
        <?= esc($valor) ?>
    </strong>

</div>