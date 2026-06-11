<main class="page">
    <section class="fc-wrap">
        <header class="hero">
            <h1 class="title title-lg">My Flashcards</h1>

            <div class="actions">
                <a class="btn" href="vle.php">← Back to hub</a>
            </div>
        </header>

        <section class="section">
            <?php if (empty($flashcards)) : ?>
                <div class="card">
                    <p class="muted">No flashcards yet.</p>
                </div>
            <?php else : ?>
                <div class="flashcard-list">
                    <?php foreach ($flashcards as $card) : ?>
                        <article class="flashcard-card">
                            <div class="flashcard-module">
                                <?= htmlspecialchars($card['ModuleName']) ?>
                            </div>

                            <div class="flashcard-front">
                                <strong>Q:</strong> <?= htmlspecialchars($card['Front']) ?>
                            </div>

                            <div class="flashcard-back">
                                <strong>A:</strong> <?= htmlspecialchars($card['Back']) ?>
                            </div>

                            <div class="flashcard-date">
                                <?= htmlspecialchars($card['CreatedAt']) ?>
                            </div>

                            <form class="flashcard-actions" method="post" action="student-flashcard-delete.php" onsubmit="return confirm('Delete this flashcard?');">
                                <input type="hidden" name="StudentFlashcardID" value="<?= (int)$card['StudentFlashcardID'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </section>
</main>