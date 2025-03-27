<?php

namespace App;

use PDO;

class User
{
    private $pdo;
    private $id;
    private $name;
    private $lastname;
    private $username;
    private $avatar;
    private $password;

    public function __construct(PDO $pdo, $id = null)
    {
        $this->pdo = $pdo;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}