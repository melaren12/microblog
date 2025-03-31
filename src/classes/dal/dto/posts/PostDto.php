<?php

namespace App\dal\dto\posts;

class
PostDto
{
    private ?int $id = null;
    private ?int $user_id = null;
    private ?string $content = null;
    private ?string $created_at = null;
    private $user_name;

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param mixed $user_name
     */
    public function setUserName($user_name): void
    {
        $this->user_name = $user_name;
    }

    /**
     * @return mixed
     */
    public function getUserLastname()
    {
        return $this->user_lastname;
    }

    /**
     * @param mixed $user_lastname
     */
    public function setUserLastname($user_lastname): void
    {
        $this->user_lastname = $user_lastname;
    }
    private $user_lastname;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(?string $created_at): void
    {
        $this->created_at = $created_at;
    }


}