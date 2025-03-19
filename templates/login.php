
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
<div class="main-container">
    <div class="container ">
        <h2>Login</h2>
        <form method="post">
            <div class="form-group">
                <input type="text" name="username"  placeholder="User Name" required>
            </div>
            <div class="form-group">
                <input type="password" name="password"  placeholder="Password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p style="color: red;"><?= htmlspecialchars($output) ?></p>
        <p>Not registered yet?</p>
        <a href="register.php"><button class="link-btn btn">Register</button></a>
    </div>
</div>

</body>
</html>
