<?= $this->extend('layouts/head') ?>

<?= $this->section('title') ?>
Mis sistemas | Project Hub
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<section style="padding: 20px;">

    <h1>
        Mis sistemas
    </h1>

    <p>
        Sistemas disponibles para tu usuario.
    </p>

    <?php foreach (($sistemas ?? []) as $sistema): ?>

        <p>
            <?= esc(
                $sistema['nombre']
                ?? 'Sistema'
            ) ?>
        </p>

    <?php endforeach; ?>

</section>

<?= $this->endSection() ?>