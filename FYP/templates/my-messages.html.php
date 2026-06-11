<main class="page">
    <header class="hero">
        <h1 class="title title-lg">My Messages</h1>
        <p class="subtitle">
            Review the messages you have sent and check whether an admin has replied.
        </p>

        <div class="actions">
            <a class="btn" href="home.php">← Back to hub</a>
        </div>
    </header>

    <section class="section">
        <?php if (empty($messages)) : ?>
            <div class="card">
                <p class="muted">No messages found.</p>
            </div>
        <?php else : ?>
            <div class="messages-list">
                <?php foreach ($messages as $msg) : ?>
                    <article class="message-card">
                        <div class="message-meta">
                            <strong>Sent:</strong> <?= htmlspecialchars($msg['CreatedAt'] ?? '') ?>
                        </div>

                        <div class="message-body">
                            <strong>Your Message:</strong>
                            <p><?= nl2br(htmlspecialchars($msg['Message'] ?? '')) ?></p>
                        </div>

                        <?php if (!empty($msg['ReplyMessage'])) : ?>
                            <div class="reply-box">
                                <strong>Admin Reply:</strong>
                                <p><?= nl2br(htmlspecialchars($msg['ReplyMessage'])) ?></p>
                                <div class="reply-meta">
                                    Reply at: <?= htmlspecialchars($msg['ReplyAt'] ?? '') ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="waiting-note">
                                Awaiting reply...
                            </div>
                        <?php endif; ?>

                        <div class="admin-actions">
                            <form method="post" action="my-message-delete.php" onsubmit="return confirm('Delete this message and its reply?');">
                                <input type="hidden" name="ContactID" value="<?= (int)$msg['ContactID'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete Message</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>