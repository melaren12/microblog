<main class="main-container">
    <section class="container">
        <h2>Login</h2>
        <form method="post">
            <label>
                <input type="text" name="username" placeholder="User Name" required>
            </label>
            <label>
                <input type="password" name="password" placeholder="Password" required>
            </label>
            <button type="submit" class="btn">Login</button>
        </form>
        <?php if (isset($output)): ?>
            <p class="error"><?= htmlspecialchars($output) ?></p>
        <?php endif; ?>
        <p>Not registered yet?</p>
        <a href="../controllers/register-page" class="btn">Register</a>
    </section>
</main>