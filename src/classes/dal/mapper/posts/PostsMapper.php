<?php

namespace App\dal\mapper\posts;

use App\dal\mapper\AbstractMapper;
use App\Post;
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

    public function insert(Post $post): void
    {
        $stmt = $this->PDO->prepare("INSERT INTO posts (user_id, content) VALUES (:user_id, :content)");
        $stmt->execute([
            'user_id' => $post->getUserId(),
            'content' => $post->getContent()
        ]);
        $post->setId((int)$this->PDO->lastInsertId());
    }

    public function findAll(): array
    {
        $stmt = $this->PDO->prepare("
            SELECT posts.*, users.name, users.lastname 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC
        ");
        $stmt->execute();
        $postsData = $stmt->fetchAll();

        $posts = [];
        foreach ($postsData as $data) {
            $post = new Post($this->PDO);
            $this->populatePost($post, $data);
            $posts[] = $post;
        }
        return $posts;
    }

    public function findByUserId(int $user_id): array
    {
        $stmt = $this->PDO->prepare("
            SELECT * FROM posts 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        $postsData = $stmt->fetchAll();

        $posts = [];
        foreach ($postsData as $data) {
            $post = new Post($this->PDO);
            $this->populatePost($post, $data);
            $posts[] = $post;
        }
        return $posts;
    }

    private function populatePost(Post $post, array $data): void
    {
        $post->setId($data['id']);
        $post->setUserId($data['user_id']);
        $post->setContent($data['content']);
        $post->setCreatedAt($data['created_at']);
        if (isset($data['name'])) {
            $post->setUserName($data['name']);
        }
        if (isset($data['lastname'])) {
            $post->setUserLastname($data['lastname']);
        }
    }

    public function getPDO(): PDO
    {
        return $this->PDO;
    }
}






