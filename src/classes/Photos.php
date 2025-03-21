<?php

namespace App;

use PDO;
use PDOException;
use RuntimeException;

class Photos
{
    private $pdo;
    private $user_id;

    public function __construct(PDO $pdo, $user_id)
    {
        $this->pdo = $pdo;
        $this->user_id = $user_id;
    }

    public function getUserPhotos(): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT photo_path, id FROM user_photos WHERE user_id = :user_id ORDER BY uploaded_at DESC");
            $stmt->execute(['user_id' => $this->user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            throw new RuntimeException("Error retrieving photos: " . $e->getMessage());
        }
    }

    public function uploadPhoto(array $file, string $target_dir): void
    {
        if (empty($file['name'])) {
            throw new RuntimeException("No photo file provided.");
        }

        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                throw new RuntimeException("Could not create directory $target_dir. Check permissions.");
            }
        }

        if (!is_writable($target_dir)) {
            throw new RuntimeException("Directory $target_dir is not writable. Check permissions.");
        }

        $photo_name = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $photo_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            throw new RuntimeException("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new RuntimeException("Error uploading file: " . error_get_last()['message']);
        }

        try {
            $stmt = $this->pdo->prepare("INSERT INTO user_photos (user_id, photo_path) VALUES (:user_id, :photo_path)");
            $stmt->execute([
                'user_id' => $this->user_id,
                'photo_path' => $target_file
            ]);
        } catch (PDOException $e) {

            if (file_exists($target_file)) {
                unlink($target_file);
            }
            throw new RuntimeException("Error adding photo to database: " . $e->getMessage());
        }
    }

    public function deletePhoto(int $photo_id): void
    {
        try {

            $stmt = $this->pdo->prepare("SELECT photo_path FROM user_photos WHERE id = :id AND user_id = :user_id");
            $stmt->execute(['id' => $photo_id, 'user_id' => $this->user_id]);
            $photo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$photo) {
                throw new RuntimeException("Photo not found or you do not have permission to delete it.");
            }

            $stmt = $this->pdo->prepare("DELETE FROM user_photos WHERE id = :id AND user_id = :user_id");
            $stmt->execute(['id' => $photo_id, 'user_id' => $this->user_id]);

            if (file_exists($photo['photo_path'])) {
                unlink($photo['photo_path']);
            }
        } catch (PDOException $e) {
            throw new RuntimeException("Error deleting photo: " . $e->getMessage());
        }
    }
}