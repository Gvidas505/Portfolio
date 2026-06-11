<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Sources</h1>
        <p class="subtitle">
            Browse research sources, open PDFs, and explore the linked material in a cleaner view.
        </p>

        <div class="actions">
            <a href="home.php" class="btn">Home</a>
        </div>
    </header>

    <section class="section sources-list">
        <?php if (empty($sources)) : ?>
            <div class="card">
                <p class="muted">No sources found.</p>
            </div>
        <?php else : ?>
            <?php foreach ($sources as $s) : ?>
                <article class="source-card">
                    <div class="source-row">
                        <div class="source-side">
                            <?php if (!empty($s['PDFUrl'])) : ?>
                                <a
                                    class="btn btn-outline btn-sm"
                                    href="<?= htmlspecialchars($s['PDFUrl']) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    PDF
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="source-main">
                            <a class="source-title-link" href="source.php?id=<?= (int)$s['SourceID'] ?>">
                                <?= htmlspecialchars($s['Title']) ?>
                            </a>

                            <div class="source-meta">
                                <?= htmlspecialchars($s['Authors'] ?? '') ?>
                                <?php if (!empty($s['Venue']) || !empty($s['Year'])) : ?>
                                    — <?= htmlspecialchars($s['Venue'] ?? '') ?>
                                    <?= !empty($s['Year']) ? '(' . (int)$s['Year'] . ')' : '' ?>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($s['Abstract'])) : ?>
                                <div class="source-abstract">
                                    <?= htmlspecialchars(mb_strimwidth($s['Abstract'], 0, 200, '...')) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($s['Keywords'])) : ?>
                                <div class="source-keywords">
                                    <strong>Keywords:</strong> <?= htmlspecialchars($s['Keywords']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="source-actions">
                                <a class="btn btn-sm" href="source.php?id=<?= (int)$s['SourceID'] ?>">
                                    View Source
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>