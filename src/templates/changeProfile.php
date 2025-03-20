<main class="profile-container">
    <div class="left-side">
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
                </div>
                <br>
                <button type="submit">Refresh</button>
            </div>
        </form>

        <a href="profile.php"><button class="btn logout">Profile</button> </a>
    </div>
    <div class="right-side">
        <form method="post" enctype="multipart/form-data">
            <div class="photo-container" >
                <div>
                    <input type="file" name="photo_path" id="photo_path" accept="image/*">
                </div>
                <button type="submit">Upload</button>
            </div>
        </form>
        <div class="photos" >
            <?php if (!empty($photos)): ?>
                <?php foreach ($photos as $photo): ?>
                <div class="photo">
                    <img src="<?= htmlspecialchars($photo['photo_path']) ?>" alt="Photo" >
                    <a href="../../delete.php?id=<?php echo htmlspecialchars($photo['id']); ?>" ><button class="delete-photo" >Delete</button></a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No photos uploaded.</p>
            <?php endif; ?>
        </div>
    </div>
</main>
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


