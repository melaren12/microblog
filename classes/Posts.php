<?php
class Post {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create($user_id, $content) {
        $stmt = $this->pdo->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
        $stmt->execute([$user_id, $content]);
    }

    public function getAllPosts() {
        $stmt = $this->pdo->prepare("
            SELECT posts.*, users.name, users.lastname 
            FROM posts 
            JOIN users ON posts.user_id = users.id 
            ORDER BY posts.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPostsByUser($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM posts 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }
}