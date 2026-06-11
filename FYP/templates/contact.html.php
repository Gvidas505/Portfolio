<main class="page">
    <header class="hero">
        <h1 class="title title-lg">Contact Us</h1>
        <p class="subtitle">
            If you have any questions, feedback, or issues using the system, send us a message below.
        </p>

        <div class="actions">
            <a class="btn" href="home.php">Home</a>
        </div>
    </header>

    <section class="section contact-wrap">
        <div class="card">
            <?php if (!empty($error)) : ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)) : ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="form">
                <div class="field">
                    <label class="label" for="Name">Name</label>
                    <input class="input" type="text" name="Name" id="Name" value="<?= htmlspecialchars($name ?? '') ?>" required>
                </div>

                <div class="field">
                    <label class="label" for="Email">Email</label>
                    <input class="input" type="email" name="Email" id="Email" value="<?= htmlspecialchars($email ?? '') ?>" required>
                </div>

                <div class="field">
                    <label class="label" for="Message">Message</label>
                    <textarea class="textarea" name="Message" id="Message" rows="6" required><?= htmlspecialchars($message ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-accent">Send Message</button>
            </form>
        </div>
    </section>
</main>