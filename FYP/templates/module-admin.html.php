<main class="page">
    <header class="hero">
        <h1 class="title title-lg"><?= htmlspecialchars($module['ModuleName']) ?></h1>
        <p class="subtitle"><?= htmlspecialchars($module['Code']) ?></p>

        <div class="actions">
            <a href="modules-admin.php" class="btn">Back to Modules</a>
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
                            href="/FYP/<?= htmlspecialchars($mat['FilePath']) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <?= htmlspecialchars($mat['Title']) ?>
                        </a>

                        <div class="vle-item-meta">
                            <?= htmlspecialchars($mat['Type']) ?> •
                            <?= htmlspecialchars($mat['CreatedAt']) ?>
                        </div>

                        <div class="admin-actions">
                            <a
                                href="delete-material.php?id=<?= (int)$mat['MaterialID'] ?>&module_id=<?= (int)$module['ModuleID'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this material?');"
                            >
                                Delete Material
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>