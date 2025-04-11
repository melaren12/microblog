<main class="profile-container">
    <div class="left-cont">
        <h2>Profile</h2>
        <?php if (isset($user)): ?>
            <div class="avatar">
                <img src="/public/uploads/avatars/<?= htmlspecialchars($user->getAvatar()) ?>" alt="Avatar">
            </div>
        <?php endif; ?>
        <p>Username: <?= htmlspecialchars($user->getUsername()) ?></p>

        <h3>Change avatar</h3>

        <form method="post" enctype="multipart/form-data">
            <div class="avatar-container">
                <div class="custom-file-upload">
                    <label for="avatar" class="input-label btn">Choose Photo</label>
                    <input type="file" name="avatar" id="avatar" accept="image/*">
                </div>
                <br>
                <div class="avatar-preview" style="margin-bottom: 10px; display: none;">
                    <img class="avatar-preview-img" id="avatar-preview-img" src="#" alt="Avatar Preview"">
                    <button type="submit" class="btn avatar-btn">Upload</button>
                    <button type="button" class="close-preview""><img src="/public/icons/close.png" alt="#"></button>
                </div>
            </div>
        </form>

        <a href="/controllers/profile.php"><button class="btn logout">Profile</button> </a>
    </div>
    <div class="right-cont">
        <div class="left-side">
            <h2>Posts</h2>
            <?php if (empty($posts)): ?>
                <p>No posts yet.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <p class="intro"><?php echo nl2br(htmlspecialchars($post->getContent())); ?></p>
                        <footer class="time"><?php echo htmlspecialchars($post->getCreatedAt()); ?></footer>
                        <button class="delete-post post-delete-btn" data-id="<?= htmlspecialchars($post->getId()) ?>"><img src="/public/icons/delete.png"></button>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="right-side">
            <form method="post" enctype="multipart/form-data">
                <div class="upload" >
                    <div>
                        <label for="photo_path" class="input-label btn">Choose Photo</label>
                        <input type="file" name="photo_path" id="photo_path" accept="image/*">
                    </div>
                    <button type="submit" class="btn">Upload</button>
                </div>
            </form>
            <div class="photos" >
                <?php if (!empty($photos)): ?>
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo">
                            <img src="../<?= htmlspecialchars($photo['photo_path']) ?>" alt="Photo" >
                            <button class="delete-photo btn" data-id="<?= htmlspecialchars($photo['id']) ?>">Delete</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No photos uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>



