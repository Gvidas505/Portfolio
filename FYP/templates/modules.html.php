<main class="page">
    <header class="hero">
        <h1 class="title title-lg">All Modules</h1>

        <div class="actions">
            <a href="vle.php" class="btn">Back to VLE</a>
        </div>
    </header>

    <section class="section">
        <div class="vle-module-grid">
            <?php foreach ($modules as $m) : ?>
                <article class="vle-panel">
                    <h2><?= htmlspecialchars($m['ModuleName']) ?></h2>

                    <p class="muted"><?= htmlspecialchars($m['Code']) ?></p>

                    <a href="module.php?id=<?= (int)$m['ModuleID'] ?>" class="btn">
                        View Resources
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>