<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Ask a Question</h1>
        <p class="subtitle">
            Ask a coursework question and attach it to the relevant module.
        </p>

        <div class="actions">
            <a class="btn" href="questions.php">← Back to questions</a>
        </div>
    </header>

    <section class="section question-form-wrap">
        <div class="card">
            <form class="form" method="post" action="question-add.php">
                <div class="field">
                    <label class="label" for="ModuleID">Module</label>
                    <select class="select" name="ModuleID" id="ModuleID" required>
                        <option value="">Select a module...</option>
                        <?php foreach ($modules as $module) : ?>
                            <option value="<?= (int)$module['ModuleID'] ?>">
                                <?= htmlspecialchars($module['ModuleName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label class="label" for="Query">Question</label>
                    <textarea class="textarea" name="Query" id="Query" rows="6" required></textarea>
                </div>

                <button class="btn btn-accent" type="submit">Post Question</button>
            </form>
        </div>
    </section>
</main>