<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Study &amp; Research Hub</h1>
        <p class="subtitle">
            Store sources, create flashcards, and ask coursework questions — all backed by a database.
        </p>

        <div class="actions">
            <a class="btn" href="sources.php">Browse Sources</a>
            <a class="btn" href="saved.php">Saved Flashcards/Sources</a>
            <a class="btn" href="questions.php">Browse Questions</a>
            <a class="btn" href="vle.php">Virtual Learning Environment</a>
            <a class="btn" href="my-messages.php">Messages</a>
            <a class="btn" href="contact.php">Contact Us</a>

            <form action="search.php" method="get" class="search-row">
                <input
                    class="input"
                    type="text"
                    name="q"
                    placeholder="Search sources, flashcards, or questions..."
                    required
                >
                <button class="btn btn-accent" type="submit">Search</button>
            </form>
        </div>
    </header>

    <section class="section stats">
        <div class="stat">
            <div class="stat-label">Total Sources</div>
            <div class="stat-value"><?= (int)$stats['sources'] ?></div>
        </div>

        <div class="stat">
            <div class="stat-label">Total Flashcards</div>
            <div class="stat-value"><?= (int)$stats['flashcards'] ?></div>
        </div>

        <div class="stat">
            <div class="stat-label">Total Questions</div>
            <div class="stat-value"><?= (int)$stats['questions'] ?></div>
        </div>

        <div class="stat">
            <div class="stat-label">Total Modules</div>
            <div class="stat-value"><?= (int)$stats['modules'] ?></div>
        </div>
    </section>

    <section class="section grid grid-2">
        <div class="card">
            <h2 class="title title-md">Recently Added Sources</h2>

            <?php if (empty($recentSources)) : ?>
                <p class="muted">No sources found.</p>
            <?php else : ?>
                <ul class="list">
                    <?php foreach ($recentSources as $s) : ?>
                        <li>
                            <a href="source.php?id=<?= (int)$s['SourceID'] ?>">
                                <?= htmlspecialchars($s['Title'] ?? ('Source #' . $s['SourceID'])) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2 class="title title-md">Latest Questions</h2>

            <?php if (empty($recentQuestions)) : ?>
                <p class="muted">No questions found.</p>
            <?php else : ?>
                <ul class="list">
                    <?php foreach ($recentQuestions as $q) : ?>
                        <li>
                            <a href="question.php?id=<?= (int)$q['QuestionID'] ?>">
                                <?= htmlspecialchars($q['Query'] ?? ('Question #' . $q['QuestionID'])) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>

    <section class="section home-note-wrap">
        <div class="home-note">
            If you have any queries, want specific sources added, or have any feedback, please contact us!
        </div>
    </section>
</main>