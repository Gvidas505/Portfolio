<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Saved</h1>
        <p class="subtitle">
            View the sources and flashcards you have saved for quick access later.
        </p>

        <div class="actions">
            <a class="btn" href="home.php">Home</a>
        </div>
    </header>

    <section class="section saved-grid">
        <section class="card">
            <h2 class="title title-md">Saved Sources (<?= count($savedSources) ?>)</h2>

            <?php if (empty($savedSources)) : ?>
                <p class="muted">No saved sources yet.</p>
            <?php else : ?>
                <?php foreach ($savedSources as $s) : ?>
                    <div class="saved-item">
                        <a class="saved-title-link" href="source.php?id=<?= (int)$s['SourceID'] ?>">
                            <?= htmlspecialchars($s['Title']) ?>
                        </a>

                        <div class="saved-meta">
                            <?= htmlspecialchars($s['Authors'] ?? '') ?>
                            <?php if (!empty($s['Venue']) || !empty($s['Year'])) : ?>
                                — <?= htmlspecialchars($s['Venue'] ?? '') ?>
                                <?= !empty($s['Year']) ? '(' . (int)$s['Year'] . ')' : '' ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="card">
            <h2 class="title title-md">Saved Flashcards (<?= count($savedFlashcards) ?>)</h2>

            <?php if (empty($savedFlashcards)) : ?>
                <p class="muted">No saved flashcards yet.</p>
            <?php else : ?>
                <?php foreach ($savedFlashcards as $f) : ?>
                    <div class="saved-item">
                        <div class="saved-title-text">
                            <?= htmlspecialchars(mb_strimwidth($f['Front'], 0, 90, '...')) ?>
                        </div>

                        <div class="saved-meta">
                            <?= htmlspecialchars(mb_strimwidth($f['Back'], 0, 120, '...')) ?>
                        </div>

                        <?php if (!empty($f['SourceID'])) : ?>
                            <div class="saved-meta">
                                From:
                                <a href="source.php?id=<?= (int)$f['SourceID'] ?>">
                                    <?= htmlspecialchars($f['SourceTitle'] ?? 'Source') ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </section>
</main>