<main class="page">
    <header class="hero">
        <div class="vle-header">
            <div>
                <h1 class="title title-lg">Virtual Learning Environment</h1>
                <p class="subtitle">
                    Access your modules, learning materials, announcements, and flashcards from one place.
                </p>
            </div>

            <div class="vle-actions">
                <a href="flashcard-create.php" class="btn">Create Flashcards</a>
                <a href="student-flashcards.php" class="btn">My Flashcards</a>
                <a href="index.php" class="btn">Back to Main Hub</a>
            </div>
        </div>
    </header>

    <section class="section vle-grid">
        <section class="vle-panel">
            <h2>Modules</h2>

            <?php if (empty($modules)) : ?>
                <p class="muted">No modules available.</p>
            <?php else : ?>
                <div class="vle-module-list">
                    <?php foreach ($modules as $m) : ?>
                        <div class="vle-module-card">
                            <div class="vle-module-name"><?= htmlspecialchars($m['ModuleName']) ?></div>
                            <div class="vle-module-code"><?= htmlspecialchars($m['Code']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <a href="modules.php" class="btn">View Modules</a>
        </section>

        <section class="vle-panel">
            <h2>Announcements</h2>

            <?php if (empty($announcements)) : ?>
                <p class="muted">No announcements yet.</p>
            <?php else : ?>
                <?php foreach ($announcements as $a) : ?>
                    <div class="vle-item">
                        <div class="vle-item-title"><?= htmlspecialchars($a['Title']) ?></div>
                        <div class="vle-item-meta">
                            <?= htmlspecialchars($a['ModuleName'] ?? 'General') ?> •
                            <?= htmlspecialchars($a['CreatedAt']) ?>
                        </div>
                        <div class="vle-item-text"><?= htmlspecialchars($a['Message']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="vle-panel">
            <h2>Learning Materials</h2>

            <?php if (empty($materials)) : ?>
                <p class="muted">No learning materials yet.</p>
            <?php else : ?>
                <?php foreach ($materials as $mat) : ?>
                    <div class="vle-item">
                        <a class="vle-link" href="<?= htmlspecialchars($mat['FilePath']) ?>" target="_blank" rel="noopener noreferrer">
                            <?= htmlspecialchars($mat['Title']) ?>
                        </a>
                        <div class="vle-item-meta">
                            <?= htmlspecialchars($mat['ModuleName'] ?? 'Module') ?> •
                            <?= htmlspecialchars($mat['Type']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="vle-panel">
            <h2>Flashcards</h2>
            <p class="muted">
                Continue revising using saved or generated flashcards from your study sources.
            </p>
            <a href="saved-vle.php" class="btn">Open Saved Flashcards/Sources</a>
        </section>
    </section>
</main>