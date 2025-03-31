<?php

namespace App\dal\dto\photos;

class
PhotoDto
{
    private ?int $id = null;
    private ?string $path = null;
    private $uploaded_at;

public function getId(): int
{
    return $this->id;
}
public function setId(int $id): void
{
    $this->id = $id;
}
public function getPhotoPath(): string
{
    return $this->photo_path;
}
public function setPhotoPath(string $photo_path): void
{
    $this->photo_path = $photo_path;
}
public function getUploadedAt()
{
    return $this->uploaded_at;
}
public function setUploadedAt($uploaded_at): void
{
    $this->uploaded_at = $uploaded_at;
}
}