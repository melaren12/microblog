<?php

namespace App\managers\photos;

use App\dal\mapper\photos\PhotosMapper;
use App\managers\AbstractManager;
use App\managers\media\MediaManager;
use App\util\LogHelper;
use Exception;
use RuntimeException;

class PhotosManager extends AbstractManager
{
    private static ?self $instance = null;

    public static function getInstance(): PhotosManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getMapper(): PhotosMapper
    {
        return PhotosMapper::getInstance();
    }

    public function getId()
    {

    }

    public function getUserPhotos(int $userId): array
    {
        return $this->getMapper()->findAllByUserId($userId);
    }

    public function getUserArchivedPhotos(int $userId): array
    {
        return $this->getMapper()->findAllArchivedByUserId($userId);
    }

    public function uploadPhoto(array $file, string $targetDir, int $userId): void
    {
        $mediaManager = MediaManager::getInstance();
        $targetFile = $mediaManager->uploadPhoto($file, $targetDir);

        try {
            $this->getMapper()->insert($targetFile, $userId);
            LogHelper::getInstance()->createInfoLog('Photo uploaded successfully!');
        } catch (Exception $e) {
            if (file_exists($targetFile)) {
                unlink($targetFile);
            }
            LogHelper::getInstance()->createErrorLog('Error adding photo to database: ' . $e->getMessage());
            throw new RuntimeException("Error adding photo to database.");
        }
    }

    public function deletePhoto(int $userId, int $photoId): void
    {

        $photoData = $this->getMapper()->findById($photoId, $userId);

        if (!$photoData) {
            LogHelper::getInstance()->createErrorLog('Error deleting photo id: ' . $photoId);
            throw new RuntimeException("Photo not found or you do not have permission to delete it.");
        }

        $filePath = $photoData['photo_path'];

        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                LogHelper::getInstance()->createErrorLog('Could not delete file: ' . $filePath);
                throw new RuntimeException("Could not delete file.");
            }
        } else {
            LogHelper::getInstance()->createErrorLog('File not found: ' . $filePath);
        }

        $this->getMapper()->delete($photoId, $userId);
    }

    public function moveToArchive(int $userId, int $photoId): void
    {

        $photoData = $this->getMapper()->findById($photoId, $userId);

        if (!$photoData) {
            LogHelper::getInstance()->createErrorLog('Error deleting photo id: ' . $photoId);
            throw new RuntimeException("Photo not found or you do not have permission to delete it.");
        }

        $this->getMapper()->moveToArchived($photoId);
    }

    public function moveFromArchive(int $userId, int $photoId): void
    {

        $photoData = $this->getMapper()->findById($photoId, $userId);

        if (!$photoData) {
            LogHelper::getInstance()->createErrorLog('Error deleting photo id: ' . $photoId);
            throw new RuntimeException("Photo not found or you do not have permission to delete it.");
        }

        $this->getMapper()->moveFromArchived($photoId);
    }

    public function getPhotosById(int $id, int $userId): array
    {
        return $this->getMapper()->findById($id, $userId);
    }

    public function cleanupExpiredArchivedPhotos(): void
    {
        $expiredPhotos = $this->getMapper()->findExpiredArchivedPhotos();

        foreach ($expiredPhotos as $photo) {
            $photoId = $photo['id'];
            $photoPath = '/var/www/microblog/public/' . $photo['photo_path'];

            if (file_exists($photoPath)) {
                if (!unlink($photoPath)) {
                    LogHelper::getInstance()->createErrorLog("Could not delete file: $photoPath for photo ID: $photoId");
                    continue;
                }
            } else {
                LogHelper::getInstance()->createErrorLog("File not found: $photoPath for photo ID: $photoId");
            }

            $this->getMapper()->deleteById($photoId);
            LogHelper::getInstance()->createInfoLog("Successfully deleted expired archived photo ID: $photoId");
        }
    }

}