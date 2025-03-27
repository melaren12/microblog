<?php

namespace App;

use PDO;

class Post
{
    private $pdo;
    private $id;
    private $user_id;
    private $content;
    private $created_at;
    private $user_name;
    private $user_lastname;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function getUserLastname(): ?string
    {
        return $this->user_lastname;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function setUserName(string $name): void
    {
        $this->user_name = $name;
    }

    public function setUserLastname(string $lastname): void
    {
        $this->user_lastname = $lastname;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}