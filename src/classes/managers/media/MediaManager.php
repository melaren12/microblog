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

    public function uploadPhoto(array $file, string $targetDir): string
    {
        if (empty($file['name'])) {
            LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'No photo file provided.');
            throw new RuntimeException("No photo file provided.");
        }

        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'Could not create directory' . $targetDir);
                throw new RuntimeException("Error uploading photo.");
            }
        }

        if (!is_writable($targetDir)) {
            LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'Directory' . $targetDir . ' is not writable.');
            throw new RuntimeException("Error uploading photo.");
        }

        $photoName = time() . "_" . basename($file["name"]);
        $targetFile = $targetDir . $photoName;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'Invalid image file type.');
            throw new RuntimeException("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
            LogHelper::getInstance()->createErrorLog('uploadPhoto error: ' . 'Failed to move uploaded file.' . error_get_last()['message']);
            throw new RuntimeException("Error uploading file: ");
        }

        return $targetFile;
    }
}