<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Manage Announcements</h1>
        <p class="subtitle">
            Create announcements for modules and manage the existing announcement list.
        </p>

        <div class="actions">
            <a href="/FYP/admin/home.php" class="btn">Back to Admin Dashboard</a>
        </div>
    </header>

    <section class="section card">
        <h2 class="title title-md">Create Announcement</h2>

        <form method="post" action="/FYP/admin/add-announcement.php" class="form">
            <div class="field">
                <label class="label" for="ModuleID">Module</label>
                <select class="select" name="ModuleID" id="ModuleID" required>
                    <option value="">Select a module</option>
                    <?php foreach ($modules as $m) : ?>
                        <option value="<?= (int)$m['ModuleID'] ?>">
                            <?= htmlspecialchars($m['ModuleName']) ?> (<?= htmlspecialchars($m['Code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label class="label" for="Title">Announcement Title</label>
                <input class="input" type="text" name="Title" id="Title" required>
            </div>

            <div class="field">
                <label class="label" for="Message">Message</label>
                <textarea class="textarea" name="Message" id="Message" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-accent">Create Announcement</button>
        </form>
    </section>

    <section class="section admin-card-grid">
        <?php if (empty($announcements)) : ?>
            <div class="card">
                <p class="muted">No announcements found.</p>
            </div>
        <?php else : ?>
            <?php foreach ($announcements as $a) : ?>
                <article class="card">
                    <h2 class="title title-sm"><?= htmlspecialchars($a['Title']) ?></h2>

                    <p class="meta">
                        <?= htmlspecialchars($a['ModuleName'] ?? 'General') ?> •
                        <?= htmlspecialchars($a['CreatedAt']) ?>
                    </p>

                    <p class="admin-text">
                        <?= nl2br(htmlspecialchars($a['Message'])) ?>
                    </p>

                    <div class="admin-actions">
                        <a
                            href="/FYP/admin/delete-announcement.php?id=<?= (int)$a['AnnouncementID'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to delete this announcement?');"
                        >
                            Delete Announcement
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</main>