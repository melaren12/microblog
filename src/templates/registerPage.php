<main class="main-container">
    <section class="container">
        <h2>Register</h2>
        <form method="post" class="form" enctype="multipart/form-data">
            <label>
                <input type="text" name="firstname" placeholder="First Name" required>
            </label>
            <label>
                <input type="text" name="lastname" placeholder="Last Name" required>
            </label>
            <label>
                <input type="text" name="username" placeholder="Username" required>
            </label>
            <label>
                <input type="password" name="password" placeholder="Password" required>
            </label>

            <p class="error">
                <?php
                if (isset($output)) {
                    echo $output;
                }
                ?>
            </p>
            <button type="submit" class="btn">Register</button>
        </form>
        <p>Already have an account?</p>
        <a href='loginPage.php'>
            <button class="btn">Login</button>
        </a>
    </section>
</main>