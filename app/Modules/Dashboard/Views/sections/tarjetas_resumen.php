<section class="dashboard-tarjetas">

    <?= view('components/ui/tarjeta_resumen', [
        'icono' =>
            '📁',

        'cantidad' =>
            $totalProyectos
            ?? 0,

        'titulo' =>
            'Proyectos',
    ]) ?>


    <?= view('components/ui/tarjeta_resumen', [
        'icono' =>
            '🖥️',

        'cantidad' =>
            $totalSistemas
            ?? 0,

        'titulo' =>
            'Sistemas',
    ]) ?>


    <?= view('components/ui/tarjeta_resumen', [
        'icono' =>
            '🧩',

        'cantidad' =>
            $totalModulos
            ?? 0,

        'titulo' =>
            'Módulos',
    ]) ?>


    <?= view('components/ui/tarjeta_resumen', [
        'icono' =>
            '🔌',

        'cantidad' =>
            $totalApis
            ?? 0,

        'titulo' =>
            'APIs',
    ]) ?>

</section>