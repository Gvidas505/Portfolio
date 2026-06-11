<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Contact Messages</h1>
        <p class="subtitle">
            Review messages sent by users and save an admin reply.
        </p>

        <div class="actions">
            <a href="/FYP/admin/home.php" class="btn">Back to Admin Dashboard</a>
        </div>
    </header>

    <section class="section">
        <?php if (empty($messages)) : ?>
            <div class="card">
                <p class="muted">No contact messages yet.</p>
            </div>
        <?php else : ?>
            <div class="messages-list">
                <?php foreach ($messages as $message) : ?>
                    <article class="message-card" id="message-<?= (int)$message['ContactID'] ?>">
                        <div class="message-meta">
                            <div><strong>Name:</strong> <?= htmlspecialchars($message['Name']) ?></div>
                            <div><strong>Email:</strong> <?= htmlspecialchars($message['Email']) ?></div>
                            <div><strong>Sent:</strong> <?= htmlspecialchars($message['CreatedAt']) ?></div>
                        </div>

                        <div class="message-body">
                            <strong>Message:</strong>
                            <p><?= nl2br(htmlspecialchars($message['Message'])) ?></p>
                        </div>

                        <?php if (!empty($message['ReplyMessage'])) : ?>
                            <div class="reply-box">
                                <strong>Reply:</strong>
                                <p><?= nl2br(htmlspecialchars($message['ReplyMessage'])) ?></p>
                                <div class="reply-meta">
                                    Reply at: <?= htmlspecialchars($message['ReplyAt']) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="contact-reply-add.php" class="form section-tight">
                            <input type="hidden" name="ContactID" value="<?= (int)$message['ContactID'] ?>">

                            <div class="field">
                                <label class="label" for="reply-<?= (int)$message['ContactID'] ?>">Reply</label>
                                <textarea
                                    class="textarea"
                                    id="reply-<?= (int)$message['ContactID'] ?>"
                                    name="ReplyMessage"
                                    rows="5"
                                    required
                                ></textarea>
                            </div>

                            <button type="submit" class="btn btn-accent">Save Reply</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>