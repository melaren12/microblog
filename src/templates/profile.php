<main class="profile-container">
    <aside class="sidebar">
        <h1>Microblog</h1>
        <section class="profile-info">
            <?php if (isset($user)): ?>
            <div class="avatar">
                <img src="/public/uploads/avatars/<?= htmlspecialchars($user->getAvatar()) ?>" alt="Avatar" loading="lazy">
            </div>
                <h3><?= htmlspecialchars($user->getName()) ?></h3>
                <a href="/controllers/ChangeProfile.php" class="btn">Edit</a>
            <?php else: ?>
                <p>Error: User not found</p>
            <?php endif; ?>
        </section>
        <form action="/controllers/post.php" method="post" class="post-form">
            <label for="content">What's new with you?</label>
            <textarea id="content" name="content" placeholder="What's new with you?" required></textarea>
            <button type="submit" class="btn">Publish</button>
        </form>
        <a href="/controllers/logout.php" class="btn logout">Logout</a>
    </aside>
    <section class="content">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <div class="post-text">
                        <p class="user-name">
                            <strong>
                                <a href="<?php echo ($user->getId() === $post->getUserId()) ? '/controllers/ChangeProfile.php' :
                                    '/controllers/GuestPage.php?user_id=' . htmlspecialchars($post->getUserId()); ?>">
                                    <?= htmlspecialchars($post->getUserName() . ' ' . $post->getUserLastname()) ?>
                                </a>
                            </strong> Wrote:
                        </p>
                        <p class="intro"><?= nl2br(htmlspecialchars($post->getContent())) ?></p>
                        <time class="time"><?= $post->getCreatedAt() ?></time>
                    </div>
                </article>
        <?php endforeach; ?>

        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </section>
</main>