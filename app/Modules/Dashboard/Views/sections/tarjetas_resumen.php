<section class="dashboard-tarjetas">

    <?= view('components/ui/tarjeta_resumen', [
        'icono'   => '📁',
        'cantidad' => 0,
        'titulo'   => 'Proyectos',
    ]) ?>

    <?= view('components/ui/tarjeta_resumen', [
        'icono'   => '🖥️',
        'cantidad' => 0,
        'titulo'   => 'Sistemas',
    ]) ?>

    <?= view('components/ui/tarjeta_resumen', [
        'icono'   => '🧩',
        'cantidad' => 0,
        'titulo'   => 'Módulos',
    ]) ?>

    <?= view('components/ui/tarjeta_resumen', [
        'icono'   => '🔌',
        'cantidad' => 0,
        'titulo'   => 'APIs',
    ]) ?>

</section>