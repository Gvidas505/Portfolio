<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Search</h1>
        <p class="subtitle">
            Search across sources, flashcards, and questions in one place.
        </p>

        <form action="search.php" method="get" class="search-row" style="margin-top: 16px;">
            <input
                class="input"
                type="text"
                name="q"
                value="<?= htmlspecialchars($q) ?>"
                placeholder="Search sources, flashcards, or questions..."
                required
            >
            <button type="submit" class="btn btn-accent">Search</button>
            <a href="home.php" class="btn">Home</a>
        </form>
    </header>

    <section class="section">
        <?php if ($q === '') : ?>
            <div class="card">
                <p class="muted">Type something to search.</p>
            </div>
        <?php else : ?>
            <p class="muted">Results for: <strong><?= htmlspecialchars($q) ?></strong></p>

            <div class="results-grid">
                <section class="card">
                    <h2 class="title title-md">Sources (<?= count($results['sources']) ?>)</h2>

                    <?php if (empty($results['sources'])) : ?>
                        <p class="muted">No sources found.</p>
                    <?php else : ?>
                        <div class="result-list">
                            <?php foreach ($results['sources'] as $s) : ?>
                                <div class="result-item">
                                    <a class="result-title" href="source.php?id=<?= (int)$s['SourceID'] ?>">
                                        <?= htmlspecialchars($s['Title']) ?>
                                    </a>
                                    <div class="result-meta">
                                        <?= htmlspecialchars($s['Venue'] ?? '') ?>
                                        <?= !empty($s['Year']) ? ' (' . (int)$s['Year'] . ')' : '' ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="card">
                    <h2 class="title title-md">Flashcards (<?= count($results['flashcards']) ?>)</h2>

                    <?php if (empty($results['flashcards'])) : ?>
                        <p class="muted">No flashcards found.</p>
                        <p class="muted small">If you do have flashcards but this shows nothing, tell me your Flashcard column names.</p>
                    <?php else : ?>
                        <div class="result-list">
                            <?php foreach ($results['flashcards'] as $f) : ?>
                                <div class="result-item">
                                    <div class="result-title">
                                        <?= htmlspecialchars(mb_strimwidth($f['Front'] ?? '', 0, 90, '...')) ?>
                                    </div>
                                    <div class="result-meta">
                                        <?= htmlspecialchars(mb_strimwidth($f['Back'] ?? '', 0, 120, '...')) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="card">
                    <h2 class="title title-md">Questions (<?= count($results['questions']) ?>)</h2>

                    <?php if (empty($results['questions'])) : ?>
                        <p class="muted">No questions found.</p>
                    <?php else : ?>
                        <div class="result-list">
                            <?php foreach ($results['questions'] as $qq) : ?>
                                <div class="result-item">
                                    <a class="result-title" href="question.php?id=<?= (int)$qq['QuestionID'] ?>">
                                        <?= htmlspecialchars($qq['Query']) ?>
                                    </a>
                                    <div class="result-meta">
                                        <?= htmlspecialchars($qq['CreatedAt'] ?? '') ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        <?php endif; ?>
    </section>
</main>