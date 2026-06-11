<main class="page">
    <section class="section auth-wrap">
        <div class="auth-card">
            <h1 class="title title-lg">Register</h1>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="form">
                <div class="field">
                    <label class="label" for="Name">Name</label>
                    <input
                        class="input"
                        type="text"
                        name="Name"
                        id="Name"
                        required
                        value="<?= htmlspecialchars($_POST['Name'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label class="label" for="Email">Email</label>
                    <input
                        class="input"
                        type="email"
                        name="Email"
                        id="Email"
                        required
                        value="<?= htmlspecialchars($_POST['Email'] ?? '') ?>"
                    >
                </div>

                <div class="field">
                    <label class="label" for="Password">Password</label>
                    <input
                        class="input"
                        type="password"
                        name="Password"
                        id="Password"
                        required
                    >
                </div>

                <div class="field">
                    <label class="label" for="ConfirmPassword">Confirm Password</label>
                    <input
                        class="input"
                        type="password"
                        name="ConfirmPassword"
                        id="ConfirmPassword"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-accent">Create Account</button>
            </form>

            <p class="auth-footer">
                Already have an account?
                <a href="login.php">Login here</a>
            </p>
        </div>
    </section>
</main>