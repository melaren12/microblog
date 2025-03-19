
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>
    <h2>Profile</h2>
    <img src="uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="Аватар"
         style="width: 100px; height: 100px; border-radius: 50%;">
    <p>Username: <?= htmlspecialchars($user['username']) ?></p>

    <h3>Change avatar</h3>

    <form method="post" enctype="multipart/form-data">
        <div class="avatar-container">
            <div class="custom-file-upload">
                <input type="file" name="avatar" id="avatar" accept="image/*">
                <label for="avatar">Choose File</label>
                <span class="file-name">No file chosen</span>
            </div>
            <br>
            <button type="submit">Refresh</button>
        </div>
    </form>

    <a href="profile.php"><button class="btn">Profile</button> </a>

    <script>
        const fileInput = document.getElementById('avatar');
        const fileName = document.querySelector('.file-name');

        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                fileName.textContent = this.files[0].name;
            } else {
                fileName.textContent = 'No file chosen';
            }
        });
    </script>
</body>
</html>

