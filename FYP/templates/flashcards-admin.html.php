<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Flashcards</h1>
        <p class="subtitle">
            Review flashcards linked to this source and generate a fresh set when needed.
        </p>

        <div class="actions">
            <a class="btn" href="source-admin.php?id=<?= (int)$source['SourceID'] ?>">← Back to Source</a>

            <form method="post" action="flashcards-admin.php?source_id=<?= (int)$source['SourceID'] ?>">
                <input type="hidden" name="action" value="generate">
                <button type="submit" class="btn btn-accent">Generate Flashcards</button>
            </form>
        </div>
    </header>

    <section class="section">
        <div class="card">
            <p class="meta">
                Source: <strong><?= htmlspecialchars($source['Title']) ?></strong>
            </p>
        </div>
    </section>

    <section class="section">
        <?php if (empty($flashcards)) : ?>
            <div class="card">
                <p class="muted">No flashcards for this source yet. Click “Generate Flashcards”.</p>
            </div>
        <?php else : ?>
            <div class="flashcard-list">
                <?php foreach ($flashcards as $c) : ?>
                    <?php $isSaved = !empty($savedFlashcardIds) && isset($savedFlashcardIds[$c['FlashcardID']]); ?>

                    <article class="flashcard-card">
                        <div class="flashcard-front">
                            <strong>Q:</strong> <?= htmlspecialchars($c['Front']) ?>
                        </div>

                        <div class="flashcard-back">
                            <strong>A:</strong> <?= nl2br(htmlspecialchars($c['Back'])) ?>
                        </div>

                        <div class="flashcard-actions">
                            <?php if ($isSaved) : ?>
                                <a
                                    class="btn btn-outline btn-sm saved-state"
                                    href="unsave-flashcard.php?id=<?= (int)$c['FlashcardID'] ?>&source_id=<?= (int)$source['SourceID'] ?>"
                                >
                                    Saved ✅
                                </a>
                            <?php else : ?>
                                <a
                                    class="btn btn-outline btn-sm"
                                    href="save-flashcard.php?id=<?= (int)$c['FlashcardID'] ?>&source_id=<?= (int)$source['SourceID'] ?>"
                                >
                                    Save Flashcard
                                </a>
                            <?php endif; ?>

                            <div class="meta">
                                Generated: <?= htmlspecialchars($c['GeneratedBy']) ?> • <?= htmlspecialchars($c['CreatedAt']) ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>