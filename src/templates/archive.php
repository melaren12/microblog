<main class="archive-container">
    <div class="photos" >
        <?php if (!empty($photos)): ?>
            <?php foreach ($photos as $photo): ?>
                <div class="photo">
                    <img src="../<?= htmlspecialchars($photo['photo_path']) ?>" alt="Photo" >
                    <div class="buttons">
                        <button class="delete-photo btn" data-id="<?= htmlspecialchars($photo['id']) ?>">Delete</button>
                        <button class="restore-photo btn" data-id="<?= htmlspecialchars($photo['id']) ?>">Restore</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No photos in archive</p>
        <?php endif; ?>
    </div>
    <a href="/controllers/ChangeProfile.php"><button class="btn archive">Back</button> </a>
</main>



