<main class="page">
    <section class="fc-wrap">
        <div class="actions">
            <a class="btn" href="vle.php">← Back to hub</a>
        </div>

        <header class="hero section">
            <h1 class="title title-lg">Create Flashcards</h1>
            <p class="subtitle fc-note">
                Creating as <?= htmlspecialchars($_SESSION['user_name'] ?? 'Student') ?>
            </p>
        </header>

        <section class="section card fc-card">
            <form class="form" method="post" action="flashcard-add.php">
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
                    <label class="label" for="Front">Question</label>
                    <textarea class="textarea" name="Front" id="Front" rows="4" required></textarea>
                </div>

                <div class="field">
                    <label class="label" for="Back">Answer</label>
                    <textarea class="textarea" name="Back" id="Back" rows="6" required></textarea>
                </div>

                <button class="btn btn-accent" type="submit">Save Flashcard</button>
            </form>
        </section>
    </section>
</main>