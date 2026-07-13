<article class="tarjeta-resumen">
    <div class="tarjeta-resumen__icono">
        <?= esc($icono ?? '') ?>
    </div>

    <div class="tarjeta-resumen__contenido">
        <strong class="tarjeta-resumen__cantidad">
            <?= esc($cantidad ?? 0) ?>
        </strong>

        <span class="tarjeta-resumen__titulo">
            <?= esc($titulo ?? '') ?>
        </span>
    </div>
</article>