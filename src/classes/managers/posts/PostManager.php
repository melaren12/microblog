<?php

namespace App\managers\posts;

use App\dal\dto\posts\PostDto;
use App\dal\mapper\posts\PostsMapper;
use App\managers\AbstractManager;

//use App\Post;
use App\util\LogHelper;
use InvalidArgumentException;
use RuntimeException;

/** @var PostDto[] $posts */
class  PostManager extends AbstractManager
{
    private static ?self $instance = null;

    public static function getInstance(): PostManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getMapper(): PostsMapper
    {
        return PostsMapper::getInstance();
    }

    public function create(int $userId, string $content): PostDto
    {
        if (strlen($content) > 1000) {
            LogHelper::getInstance()->createErrorLog('Error creating post: Post content cannot exceed 1000 characters.');
            throw new InvalidArgumentException("Post content cannot exceed 1000 characters.");
        }
        $post = new PostDto();
        $post->setUserId($userId);
        $post->setContent($content);

        $id = $this->getMapper()->insert($post);

        $post->setId($id);

        return $post;
    }

    public function getAllPosts(): array
    {
        return $this->getMapper()->findAll();

    }

    public function getPostsByUser(int $userId): array
    {
        $params = ['user_id' => $userId];
        return (PostManager::getInstance()->getList($params));
    }

    public function deletePost($userId, $id): void
    {
        $this->getMapper()->delete($id, $userId);
    }
}