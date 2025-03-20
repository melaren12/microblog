<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>
    <h2>Profile</h2>
    <?php if (isset($user)): ?>
        <img src="uploads/<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar"
            style="width: 100px; height: 100px; border-radius: 50%;">
    <?php endif; ?>
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
        <div class="photo-container" style="margin: 30px; width: 300px; height: 300px; background-color: #4CAF50">
            <div>
                <input type="file" name="photo_path" id="photo_path" accept="image/*">
                <label for="avatar">Choose File</label>
                <span class="file-name">No file chosen</span>
            </div>
            <button type="submit">Upload</button>
        </div>
    </form>
    <div class="photos" style="display: flex">
        <?php if (!empty($photos)): ?>
            <?php foreach ($photos as $photo): ?>
                <img src="<?= htmlspecialchars($photo['photo_path']) ?>" alt="Photo"
                     style="width: 100px; height: 100px; border-radius: 50%; margin-right: 10px;">
            <?php endforeach; ?>
        <?php else: ?>
            <p>No photos uploaded.</p>
        <?php endif; ?>
    </div>


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

