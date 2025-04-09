<?php

namespace App\dal\dto\photos;

class
PhotoDto
{
    private ?int $id = null;
    private ?string $uploaded_at;

public function getId(): int
{
    return $this->id;
}
public function setId(int $id): void
{
    $this->id = $id;
}
public function getUploadedAt(): ?string
{
    return $this->uploaded_at;
}
public function setUploadedAt($uploaded_at): void
{
    $this->uploaded_at = $uploaded_at;
}
}