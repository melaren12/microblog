<main class="profile-container">
    <div class="left-cont">
        <h1>Profile</h1>
        <div class="profile-info">
            <img src="../../public/uploads/avatars/<?= htmlspecialchars($profile_user['avatar']) ?>" alt="Avatar" class="avatar" loading="lazy">
            <h3><?= htmlspecialchars($profile_user['name'] . ' ' . $profile_user['lastname']) ?></h3>
        </div>
        <a href="profile.php"><button class="btn">Back</button></a>
    </div>
    <div class="right-cont">
        <div class="left-side">
            <h2>Posts</h2>
            <?php if (empty($posts)): ?>
                <p>No posts yet.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <p class="intro"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <footer class="time"><em><?= $post['created_at'] ?></em></footer>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="right-side">
            <h2>Photos</h2>
            <div class="photos">
                <?php if (!empty($photos)): ?>
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo">
                            <img src="<?= htmlspecialchars($photo['photo_path']) ?>" alt="Photo" >
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No photos uploaded.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

