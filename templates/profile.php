<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Microblog</title>
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>
    <div class="profile-container">
        <div class="left-cont">
            <h1>Microblog</h1>
            <div class="profile-info">
                <?php if (isset($user)): ?>
                    <img src="../uploads/<?= htmlspecialchars($user->getAvatar()) ?>" alt="Аватар" class="avatar" loading="lazy">
                    <h3><?= htmlspecialchars($user->getName()) ?></h3>
                    <a href="../ChangeProfile.php"><button class="btn">Edit</button></a>
                <?php else: ?>
                    <p>Error: User not found</p>
                <?php endif; ?>
            </div>
            <form action="../post.php" method="post" class="post-form">
                <label for="content">What's new with you?</label>
                <textarea id="content" name="content" placeholder="What's new with you?" required></textarea>
                <br>
                <button type="submit">Publish</button>
            </form>
            <a href="../logout.php"><button class="btn logout">Logout</button></a>
        </div>
        <div class="right-cont">
            <?php if (isset($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <div class="text">
                            <p>
                                <strong>
                                    <a href="../GuestPage.php?user_id=<?= htmlspecialchars($post['user_id']) ?>">
                                        <?= htmlspecialchars($post['name']) . ' ' . htmlspecialchars($post['lastname']) ?>
                                    </a>
                                </strong> Wrote:
                            </p>
                            <p class="intro"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                            <p class="time"><em><?= $post['created_at'] ?></em></p>
                        </div>
                        <div class="action">
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>