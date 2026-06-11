<main class="page">
    <header class="hero">
        <h1 class="title title-lg"><?= htmlspecialchars($question['Query']) ?></h1>

        <div class="question-meta">
            <span><?= htmlspecialchars($question['UserName'] ?? 'Unknown user') ?></span>
            <span>•</span>
            <span><?= htmlspecialchars($question['ModuleName'] ?? 'No module') ?></span>
            <span>•</span>
            <span><?= htmlspecialchars($question['CreatedAt'] ?? '') ?></span>
        </div>

        <div class="actions">
            <a class="btn" href="questions.php">← Back to questions</a>
        </div>
    </header>

    <section class="section">
        <h2 class="title title-md">Answers (<?= count($answers) ?>)</h2>

        <?php if (empty($answers)) : ?>
            <p class="muted">No answers yet. Be the first to reply.</p>
        <?php else : ?>
            <div class="answers-list">
                <?php foreach ($answers as $a) : ?>
                    <article class="answer-card">
                        <div class="answer-meta">
                            <strong><?= htmlspecialchars($a['UserName'] ?? 'Unknown user') ?></strong>
                            <span>•</span>
                            <span><?= htmlspecialchars($a['CreatedAt'] ?? '') ?></span>
                        </div>

                        <div class="answer-body">
                            <?= nl2br(htmlspecialchars($a['Body'])) ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="section card">
        <h2 class="title title-md">Post an answer</h2>

        <p class="muted answer-note">
            Answering as <?= htmlspecialchars($_SESSION['user_name'] ?? 'Logged-in user') ?>
        </p>

        <form class="form" method="post" action="answer-add.php">
            <input type="hidden" name="QuestionID" value="<?= (int)$question['QuestionID'] ?>">

            <div class="field">
                <label class="label" for="answer-body">Answer</label>
                <textarea class="textarea" id="answer-body" name="Body" rows="6" required></textarea>
            </div>

            <button class="btn" type="submit">Submit answer</button>
        </form>
    </section>
</main>