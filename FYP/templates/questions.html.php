<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Questions</h1>
        <p class="subtitle">
            Browse coursework questions, open discussions, and add your own question to the forum.
        </p>

        <div class="actions">
            <a class="btn" href="ask.php">Ask a question</a>
            <a class="btn" href="home.php">Home</a>
        </div>
    </header>

    <section class="section">
        <?php if (empty($questions)) : ?>
            <div class="card">
                <p class="muted">No questions yet.</p>
            </div>
        <?php else : ?>
            <div class="question-list">
                <?php foreach ($questions as $q) : ?>
                    <article class="question-card">
                        <div class="question-row">
                            <div class="question-main">
                                <a class="question-link" href="question.php?id=<?= (int)$q['QuestionID'] ?>">
                                    <?= htmlspecialchars($q['Query']) ?>
                                </a>

                                <div class="question-meta">
                                    <span><?= htmlspecialchars($q['UserName'] ?? 'Unknown user') ?></span>
                                    <span>•</span>
                                    <span><?= htmlspecialchars($q['ModuleName'] ?? 'No module') ?></span>
                                    <span>•</span>
                                    <span><?= htmlspecialchars($q['CreatedAt'] ?? '') ?></span>
                                </div>
                            </div>

                            <div class="question-count">
                                <div class="question-count-num"><?= (int)$q['AnswerCount'] ?></div>
                                <div class="question-count-label">answers</div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>