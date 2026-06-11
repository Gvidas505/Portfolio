<main class="page">
    <section class="section auth-wrap">
        <div class="auth-card">
            <h1 class="title title-lg">Login</h1>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="form">
                <div class="field">
                    <label class="label" for="Email">Email</label>
                    <input class="input" type="email" name="Email" id="Email" required>
                </div>

                <div class="field">
                    <label class="label" for="Password">Password</label>
                    <input class="input" type="password" name="Password" id="Password" required>
                </div>

                <button type="submit" class="btn btn-accent">Login</button>
            </form>

            <p class="auth-footer">
                Don’t have an account?
                <a href="register.php">Register here</a>
            </p>
        </div>
    </section>
</main>