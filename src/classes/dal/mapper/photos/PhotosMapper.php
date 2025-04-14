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
    public function findAllByUserId(int $user_id): array
    {
        $params = [
            'user_id' => $user_id,
            'fetchMode' => PDO::FETCH_ASSOC
        ];
        $selectFields = "photo_path, id, user_id, uploaded_at";
        $orderBy = "uploaded_at DESC";

        $photos = $this->getList($params, $selectFields, null, null, $orderBy);

        return $photos ?: [];
    }
    public function findById(int $photo_id, int $user_id): ?array
    {
        $params = [
            'id' => $photo_id,
            'user_id' => $user_id,
            'fetchMode' => PDO::FETCH_ASSOC
        ];

        $photos = $this->getList($params);

        return !empty($photos) ? $photos[0] : null;

    }
    public function insert(string $photo_path, int $user_id): void
    {
        try {
            $params = [
                [
                    'user_id' => $user_id,
                    'photo_path' => $photo_path,
                ]
            ];

            $this->insertList($params);

        }catch (PDOException $e){
            throw new RuntimeException($e->getMessage());
        }
    }

    public function delete(int $photo_id, int $user_id): void
    {
        $stmt = $this->PDO->prepare('
            DELETE FROM user_photos 
            WHERE id = :id AND user_id = :user_id
        ');
        $stmt->execute(['id' => $photo_id, 'user_id' => $user_id]);
    }
}