<?php

namespace App\dal\mapper\photos;

use App\dal\dto\photos\PhotoDto;
use App\dal\mapper\AbstractMapper;
use PDO;
use PDOException;
use RuntimeException;

class PhotosMapper extends AbstractMapper
{
    private static ?PhotosMapper $instance = null;
    protected string $tableName = 'user_photos';

    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): PhotosMapper
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function createDto(): PhotoDto
    {
        return new PhotoDto();
    }

    public function findAllByUserId(int $userId): array
    {
        $params = [
            'user_id' => $userId,
            'fetchMode' => PDO::FETCH_ASSOC,
            'archived' => false,
        ];
        $selectFields = "photo_path, id, user_id, uploaded_at";
        $orderBy = "uploaded_at DESC";

        $photos = $this->getList($params, $selectFields, null, null, $orderBy);

        return $photos ?: [];
    }

    public function findAllArchivedByUserId(int $userId): array
    {
        $params = [
            'user_id' => $userId,
            'fetchMode' => PDO::FETCH_ASSOC,
            'archived' => true,
        ];
        $selectFields = "photo_path, id, user_id, uploaded_at";
        $orderBy = "uploaded_at DESC";

        $photos = $this->getList($params, $selectFields, null, null, $orderBy);

        return $photos ?: [];
    }

    public function findById(int $photoId, int $userId): ?array
    {
        $params = [
            'id' => $photoId,
            'user_id' => $userId,
            'fetchMode' => PDO::FETCH_ASSOC
        ];

        $photos = $this->getList($params);

        return !empty($photos) ? $photos[0] : null;

    }

    public function insert(string $photoPath, int $userId): void
    {
        try {
            $params = [
                [
                    'user_id' => $userId,
                    'photo_path' => $photoPath,
                ]
            ];

            $this->insertList($params);

        } catch (PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    public function moveToArchived(int $photoId): bool
    {
        try {
            $stmt = $this->PDO->prepare('UPDATE user_photos SET archived = ?, archived_at = NOW() WHERE id = ?');
            $stmt->execute([1, $photoId]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error archiving photo: " . $e->getMessage());
            return false;
        }
    }


    public function moveFromArchived(int $photoId): bool
    {
        try {
            $stmt = $this->PDO->prepare('UPDATE user_photos SET archived = ?, archived_at = NULL WHERE id = ?');
            $stmt->execute([0, $photoId]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error archiving photo: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $photoId, int $userId): void
    {
        $stmt = $this->PDO->prepare('
            DELETE FROM user_photos 
            WHERE id = :id AND user_id = :user_id
        ');
        $stmt->execute(['id' => $photoId, 'user_id' => $userId]);
    }

    public function findExpiredArchivedPhotos(): array
    {
        try {
            $stmt = $this->PDO->prepare(
                'SELECT id, photo_path, user_id 
                FROM user_photos 
                WHERE archived = 1 
                AND archived_at IS NOT NULL 
                AND archived_at < NOW() - INTERVAL 24 HOUR'
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Error finding expired archived photos: " . $e->getMessage());
            return [];
        }
    }

    public function deleteById(int $photoId): void
    {
        try {
            $stmt = $this->PDO->prepare('DELETE FROM user_photos WHERE id = ?');
            $stmt->execute([$photoId]);
        } catch (PDOException $e) {
            error_log("Error deleting photo ID $photoId: " . $e->getMessage());
        }
    }
}