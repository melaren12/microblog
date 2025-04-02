<?php

namespace App\managers\media;

use App\dal\dto\photos\PhotoDto;
use App\util\LogHelper;
use RuntimeException;

class MediaManager extends PhotoDto
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
            LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'No photo file provided.');
            throw new RuntimeException("No photo file provided.");
        }

        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'Could not create directory' . $target_dir);
                throw new RuntimeException("Error uploading photo.");
            }
        }

        if (!is_writable($target_dir)) {
            LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'Directory' . $target_dir . ' is not writable.');
            throw new RuntimeException("Error uploading photo.");
        }

        $photo_name = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $photo_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'Invalid image file type.');
            throw new RuntimeException("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'Failed to move uploaded file.'  . error_get_last()['message']);
            throw new RuntimeException("Error uploading file: ");
        }

        return $target_file;
    }

    public function deletePhoto(string $photo_path): void
    {
        if (file_exists($photo_path)) {
            unlink($photo_path);
        }
    }
}