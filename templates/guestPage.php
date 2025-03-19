
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Profile of <?= htmlspecialchars($profile_user['name']) ?></title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
<div class="profile-container">
    <div class="left-cont">
        <h1>Profile</h1>
        <div class="profile-info">
            <img src="uploads/<?= htmlspecialchars($profile_user['avatar']) ?>" alt="Avatar" class="avatar" loading="lazy">
            <h3><?= htmlspecialchars($profile_user['name'] . ' ' . $profile_user['lastname']) ?></h3>
        </div>
        <a href="profile.php"><button class="btn">Back to Feed</button></a>
    </div>
    <div class="right-cont">
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
</div>
</body>
</html>
