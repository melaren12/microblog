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
    public function getUserPhotos(int $user_id): array
    {
        return $this->getMapper()->findAllByUserId($user_id);
    }
    public function uploadPhoto(array $file, string $target_dir, int $user_id): void
    {
        $mediaManager = MediaManager::getInstance();
        $targetFile = $mediaManager->uploadPhoto($file, $target_dir);

        try {
            $this->getMapper()->insert( $targetFile, $user_id);
            LogHelper::getInstance()->createInfoLog('Photo uploaded successfully!');
        } catch (Exception $e) {
            if (file_exists($targetFile)) {
                unlink($targetFile);
            }
            LogHelper::getInstance()->createErrorLog('Error adding photo to database: ' . $e->getMessage());
            throw new RuntimeException("Error adding photo to database.");
        }
    }
    public function deletePhoto(int $user_id, int $photo_id): void
    {
        $mediaManager = MediaManager::getInstance();
        $photoData = $this->getMapper()->findById($photo_id, $user_id);

        if (!$photoData) {
            LogHelper::getInstance()->createErrorLog('Error deleting photo id: ' . $photo_id);
            throw new RuntimeException("Photo not found or you do not have permission to delete it.");
        }

        $mediaManager->deletePhoto($photo_id);
        $this->getMapper()->delete($photo_id, $user_id);

    }
    public function getPhotosById( int $id, int $user_id): array
    {
        return $this->getMapper()->findById($id, $user_id);
    }
}