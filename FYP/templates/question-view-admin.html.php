<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Question Details</h1>
        <p class="subtitle">
            Review the question, manage answers, and reply as admin.
        </p>

        <div class="actions">
            <a class="btn" href="/FYP/admin/questions-admin.php">Back to Questions</a>

            <form method="post" action="/FYP/admin/question-delete-admin.php" onsubmit="return confirm('Delete this question and all answers?');">
                <input type="hidden" name="QuestionID" value="<?= (int)$question['QuestionID'] ?>">
                <button type="submit" class="btn btn-danger">Delete Question</button>
            </form>
        </div>
    </header>

    <section class="section">
        <div class="card">
            <h2 class="title title-md"><?= htmlspecialchars($question['Query']) ?></h2>

            <div class="question-meta">
                <span><?= htmlspecialchars($question['UserName'] ?? 'Unknown user') ?></span>
                <span>•</span>
                <span><?= htmlspecialchars($question['ModuleName'] ?? 'No module') ?></span>
                <span>•</span>
                <span><?= htmlspecialchars($question['CreatedAt'] ?? '') ?></span>
            </div>
        </div>
    </section>

    <section class="section">
        <h2 class="title title-md">Answers (<?= count($answers) ?>)</h2>

        <?php if (empty($answers)) : ?>
            <div class="card">
                <p class="muted">No answers yet.</p>
            </div>
        <?php else : ?>
            <div class="answers-list">
                <?php foreach ($answers as $a) : ?>
                    <div class="answer-card">
                        <div class="answer-meta">
                            <strong><?= htmlspecialchars($a['UserName'] ?? 'Unknown user') ?></strong>
                            <span>•</span>
                            <span><?= htmlspecialchars($a['CreatedAt'] ?? '') ?></span>
                        </div>

                        <div class="answer-body">
                            <?= nl2br(htmlspecialchars($a['Body'])) ?>
                        </div>

                        <div class="admin-actions">
                            <form method="post" action="/FYP/admin/answer-delete-admin.php" onsubmit="return confirm('Delete this answer?');">
                                <input type="hidden" name="AnswerID" value="<?= (int)$a['AnswerID'] ?>">
                                <input type="hidden" name="QuestionID" value="<?= (int)$question['QuestionID'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete Answer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="section">
        <div class="card">
            <h2 class="title title-md">Reply to Question</h2>
            <p class="muted">Replying as <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>

            <form class="form" method="post" action="/FYP/admin/answer-add-admin.php">
                <input type="hidden" name="QuestionID" value="<?= (int)$question['QuestionID'] ?>">

                <div class="field">
                    <label class="label" for="Body">Answer</label>
                    <textarea class="textarea" name="Body" id="Body" rows="6" required></textarea>
                </div>

                <button class="btn btn-accent" type="submit">Submit Answer</button>
            </form>
        </div>
    </section>
</main>