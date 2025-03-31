<?php

namespace App\managers\media;

use RuntimeException;

class MediaManager extends \App\dal\dto\photos\PhotoDto
{
    private static ?self $instance = null;

    public static function getInstance(): MediaManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function uploadPhoto(array $file, string $target_dir): string
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

        return $target_file;
    }

    public function deletePhoto(int $photo_id, string $photo_path): void
    {
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
    }
}