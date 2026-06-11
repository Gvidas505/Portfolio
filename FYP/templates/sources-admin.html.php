<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Manage Sources</h1>
        <p class="subtitle">
            Add new research sources and manage the existing source list.
        </p>

        <div class="actions">
            <a href="/FYP/admin/home.php" class="btn">Home</a>
        </div>
    </header>

    <section class="section card">
        <h2 class="title title-md">Add Source</h2>

        <?php if (!empty($formError)) : ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($formError) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($formSuccess)) : ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($formSuccess) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/FYP/admin/add-source.php" class="form">
            <div class="field">
                <label class="label" for="Title">Title</label>
                <input
                    class="input"
                    type="text"
                    name="Title"
                    id="Title"
                    value="<?= htmlspecialchars($old['Title'] ?? '') ?>"
                >
            </div>

            <div class="field">
                <label class="label" for="Authors">Authors</label>
                <textarea
                    class="textarea"
                    name="Authors"
                    id="Authors"
                    placeholder="Enter authors separated by commas, e.g. Beckie Edwards, Jennifer Norris, Steve Cole"
                ><?= htmlspecialchars($old['Authors'] ?? '') ?></textarea>
            </div>

            <div class="field">
                <label class="label" for="Abstract">Abstract</label>
                <textarea
                    class="textarea"
                    name="Abstract"
                    id="Abstract"
                ><?= htmlspecialchars($old['Abstract'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-2">
                <div class="field">
                    <label class="label" for="Year">Year</label>
                    <input
                        class="input"
                        type="number"
                        name="Year"
                        id="Year"
                        value="<?= htmlspecialchars($old['Year'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label class="label" for="Venue">Venue</label>
                    <input
                        class="input"
                        type="text"
                        name="Venue"
                        id="Venue"
                        value="<?= htmlspecialchars($old['Venue'] ?? '') ?>"
                    >
                </div>
            </div>

            <div class="grid grid-2">
                <div class="field">
                    <label class="label" for="Publisher">Publisher</label>
                    <input
                        class="input"
                        type="text"
                        name="Publisher"
                        id="Publisher"
                        value="<?= htmlspecialchars($old['Publisher'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label class="label" for="DOI">DOI</label>
                    <input
                        class="input"
                        type="text"
                        name="DOI"
                        id="DOI"
                        value="<?= htmlspecialchars($old['DOI'] ?? '') ?>"
                    >
                </div>
            </div>

            <div class="grid grid-2">
                <div class="field">
                    <label class="label" for="URL">Website URL</label>
                    <input
                        class="input"
                        type="url"
                        name="URL"
                        id="URL"
                        value="<?= htmlspecialchars($old['URL'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label class="label" for="PDFUrl">PDF URL</label>
                    <input
                        class="input"
                        type="url"
                        name="PDFUrl"
                        id="PDFUrl"
                        value="<?= htmlspecialchars($old['PDFUrl'] ?? '') ?>"
                    >
                </div>
            </div>

            <button type="submit" class="btn btn-accent">Add Source</button>
        </form>
    </section>

    <section class="section sources-list">
        <?php if (empty($sources)) : ?>
            <div class="card">
                <p class="muted">No sources found.</p>
            </div>
        <?php else : ?>
            <?php foreach ($sources as $s) : ?>
                <article class="source-card">
                    <div class="source-row">
                        <div class="source-side">
                            <?php if (!empty($s['PDFUrl'])) : ?>
                                <a
                                    class="btn btn-outline btn-sm"
                                    href="<?= htmlspecialchars($s['PDFUrl']) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    PDF
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="source-main">
                            <a class="source-title-link" href="/FYP/admin/source-admin.php?id=<?= (int)$s['SourceID'] ?>">
                                <?= htmlspecialchars($s['Title']) ?>
                            </a>

                            <div class="source-meta">
                                <?php if (!empty($s['Venue']) || !empty($s['Year'])) : ?>
                                    <?= htmlspecialchars($s['Venue'] ?? '') ?>
                                    <?= !empty($s['Year']) ? ' (' . (int)$s['Year'] . ')' : '' ?>
                                <?php endif; ?>
                            </div>

                            <div class="source-actions">
                                <a class="btn btn-sm" href="/FYP/admin/source-admin.php?id=<?= (int)$s['SourceID'] ?>">
                                    View Source
                                </a>

                                <a
                                    class="btn btn-danger btn-sm"
                                    href="/FYP/admin/delete-source.php?id=<?= (int)$s['SourceID'] ?>"
                                    onclick="return confirm('Are you sure you want to delete this source?');"
                                >
                                    Delete Source
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>