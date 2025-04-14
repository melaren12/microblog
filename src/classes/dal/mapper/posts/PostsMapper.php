<?php

namespace App\dal\mapper\posts;

use App\dal\dto\posts\PostDto;
use App\dal\mapper\AbstractMapper;
use Exception;
use PDO;
use RuntimeException;

class  PostsMapper extends AbstractMapper
{
    private static ?self $instance = null;
    protected string $tableName = 'posts';
    public static function getInstance(): PostsMapper
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    public function createDto(): PostDto
    {
       return new PostDto();
    }

    /**
     * @throws Exception
     */
    public function insert(PostDto $post): int
    {
        $params = [
            [
                'user_id' => $post->getUserId(),
                'content' => $post->getContent()
            ]
        ];

        return $this->insertList($params);
    }
    /**
     *
     *
     * @return PostDto[]
     */
    public function findAll(): array
    {
        $params = [
            'fetchMode' => PDO::FETCH_CLASS
        ];
        $selectFields = "posts.*, users.name, users.lastname";
        $join = "JOIN users ON posts.user_id = users.id";
        $orderBy = "posts.created_at DESC";

        return $this->getList($params, $selectFields, null, $join, $orderBy);
    }

    public function delete(int $id, int $userId): void
    {
        $stmt = $this->PDO->prepare("
            DELETE FROM posts
            WHERE id = :id AND user_id = :user_id   
        ");
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('Post not found or not authorized');
        }
    }

}