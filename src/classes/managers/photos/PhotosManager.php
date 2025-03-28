<?php

namespace App\managers\photos;

use App\dal\mapper\photos\PhotosMapper;
use App\managers\AbstractManager;
use App\Photos;
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

    public function uploadPhoto(int $user_id, array $file, string $target_dir): void
    {
        $photo = new Photos($this->getMapper()->getPDO(), $user_id);
        $target_file = $photo->uploadPhoto($file, $target_dir);

        try {
            $this->getMapper()->insert($photo, $target_file);
        } catch (\Exception $e) {
            if (file_exists($target_file)) {
                unlink($target_file);
            }
            throw new RuntimeException("Error adding photo to database: " . $e->getMessage());
        }
    }
    public function deletePhoto(int $user_id, int $photo_id): void
    {
        $photoData = $this->getMapper()->findById($photo_id, $user_id);
        if (!$photoData) {
            throw new RuntimeException("Photo not found or you do not have permission to delete it.");
        }

        $photo = new Photos($this->getMapper()->getPDO(), $user_id);
        $photo->deletePhoto($photo_id, $photoData['photo_path']);
        $this->getMapper()->delete($photo_id, $user_id);
    }

    public function getPhotosById( int $id, int $user_id): array
    {
        return $this->getMapper()->findById($id, $user_id);
    }
}