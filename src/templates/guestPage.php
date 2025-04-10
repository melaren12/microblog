<main class="profile-container">
    <div class="left-cont">
        <h1>Profile</h1>
        <div class="profile-info">
            <img src="/public/uploads/avatars/<?= htmlspecialchars($profile_user->getAvatar()) ?>" alt="Avatar" class="avatar" loading="lazy">
            <h3><?= htmlspecialchars($profile_user->getName() . ' ' . $profile_user->getLastname()) ?></h3>
        </div>
        <a href="/controllers/profile.php"><button class="btn">Back</button></a>
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
                            <img src="../<?= htmlspecialchars($photo['photo_path']) ?>" alt="Photo" >
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No photos uploaded.</p>
                <?php endif; ?>
            </div>

        </div>
    </div>
</main>

