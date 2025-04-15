<main class="photo-container">
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
    <a href="/controllers/ChangeProfile.php" class="back btn">Back</a>
</main>
