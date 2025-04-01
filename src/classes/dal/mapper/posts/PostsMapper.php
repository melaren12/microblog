<?php

namespace App\dal\mapper\posts;

use App\dal\dto\posts\PostDto;
use App\dal\mapper\AbstractMapper;
use PDO;

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

    public function insert(PostDto $post): void
    {
        $stmt = $this->PDO->prepare("INSERT INTO posts (user_id, content) VALUES (:user_id, :content)");
        $stmt->execute([
            'user_id' => $post->getUserId(),
            'content' => $post->getContent()
        ]);
        $post->setId((int)$this->PDO->lastInsertId());
    }
    /**
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

}






