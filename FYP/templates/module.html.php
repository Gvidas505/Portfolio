<main class="page">
    <header class="hero">
        <h1 class="title title-lg"><?= htmlspecialchars($module['ModuleName']) ?></h1>
        <p class="subtitle"><?= htmlspecialchars($module['Code']) ?></p>

        <div class="actions">
            <a href="modules.php" class="btn">Back to Modules</a>
        </div>
    </header>

    <section class="section">
        <div class="vle-panel">
            <h2>Learning Materials</h2>

            <?php if (empty($materials)) : ?>
                <p class="muted">No resources uploaded yet.</p>
            <?php else : ?>
                <?php foreach ($materials as $mat) : ?>
                    <div class="vle-item">
                        <a
                            class="vle-link"
                            href="<?= htmlspecialchars($mat['FilePath']) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <?= htmlspecialchars($mat['Title']) ?>
                        </a>

                        <div class="vle-item-meta">
                            <?= htmlspecialchars($mat['Type']) ?> •
                            <?= htmlspecialchars($mat['CreatedAt']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>