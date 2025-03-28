<?php

namespace App\dal\mapper\photos;

use App\dal\mapper\AbstractMapper;
use App\Photos;
use PDO;

class PhotosMapper extends AbstractMapper
{
    private static ?PhotosMapper $instance = null;
    protected string $tableName = 'user_photos';

    public static function getInstance(): PhotosMapper
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        parent::__construct();
    }


    public function getPDO(): PDO
    {
        return $this->PDO;
    }

    public function findAllByUserId(int $user_id): array
    {
        $stmt = $this->PDO->prepare("
            SELECT photo_path, id, user_id, uploaded_at 
            FROM user_photos 
            WHERE user_id = :user_id 
            ORDER BY uploaded_at DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $photo_id, int $user_id): ?array
    {
        $stmt = $this->PDO->prepare("
            SELECT photo_path, id, user_id, uploaded_at 
            FROM user_photos 
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute(['id' => $photo_id, 'user_id' => $user_id]);
        $photo = $stmt->fetch(PDO::FETCH_ASSOC);
        return $photo ?: null;
    }

    public function insert(Photos $photo, string $photo_path): void
    {
        $stmt = $this->PDO->prepare("
            INSERT INTO user_photos (user_id, photo_path) 
            VALUES (:user_id, :photo_path)
        ");
        $stmt->execute([
            'user_id' => $photo->getUserId(),
            'photo_path' => $photo_path
        ]);
    }

    public function delete(int $photo_id, int $user_id): void
    {
        $stmt = $this->PDO->prepare("
            DELETE FROM user_photos 
            WHERE id = :id AND user_id = :user_id
        ");
        $stmt->execute(['id' => $photo_id, 'user_id' => $user_id]);
    }
}