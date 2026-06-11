<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Manage Questions</h1>
        <p class="subtitle">
            Review student questions, open discussions, reply to them, and remove them when needed.
        </p>

        <div class="actions">
            <a class="btn" href="/FYP/admin/home.php">Back to Admin Dashboard</a>
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
                                <a class="question-link" href="/FYP/admin/question-admin.php?id=<?= (int)$q['QuestionID'] ?>">
                                    <?= htmlspecialchars($q['Query']) ?>
                                </a>

                                <div class="question-meta">
                                    <span><?= htmlspecialchars($q['UserName'] ?? 'Unknown user') ?></span>
                                    <span>•</span>
                                    <span><?= htmlspecialchars($q['ModuleName'] ?? 'No module') ?></span>
                                    <span>•</span>
                                    <span><?= htmlspecialchars($q['CreatedAt'] ?? '') ?></span>
                                </div>

                                <div class="admin-actions">
                                    <a class="btn btn-sm" href="/FYP/admin/question-admin.php?id=<?= (int)$q['QuestionID'] ?>">
                                        Open
                                    </a>

                                    <form method="post" action="/FYP/admin/question-delete-admin.php" onsubmit="return confirm('Delete this question and all answers?');">
                                        <input type="hidden" name="QuestionID" value="<?= (int)$q['QuestionID'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
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