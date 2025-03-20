<main class="profile-container">
    <aside class="sidebar">
        <h1>Microblog</h1>
        <section class="profile-info">
            <?php if (isset($user)): ?>
                <img src="../../public/uploads/avatars/<?= htmlspecialchars($user->getAvatar()) ?>" alt="Avatar" class="avatar" loading="lazy">
                <h3><?= htmlspecialchars($user->getName()) ?></h3>
                <a href="../../ChangeProfile.php" class="btn">Edit</a>
            <?php else: ?>
                <p>Error: User not found</p>
            <?php endif; ?>
        </section>
        <form action="../../post.php" method="post" class="post-form">
            <label for="content">What's new with you?</label>
            <textarea id="content" name="content" placeholder="What's new with you?" required></textarea>
            <button type="submit" class="btn">Publish</button>
        </form>
        <a href="../../logout.php" class="btn logout">Logout</a>
    </aside>
    <section class="content">
        <?php if (isset($posts) && !empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <div class="post-text">
                        <p>
                            <strong>
                                <a href="../../GuestPage.php?user_id=<?= htmlspecialchars($post['user_id']) ?>">
                                    <?= htmlspecialchars($post['name'] . ' ' . $post['lastname']) ?>
                                </a>
                            </strong> Wrote:
                        </p>
                        <p class="intro"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <time class="time"><?= $post['created_at'] ?></time>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </section>
</main>