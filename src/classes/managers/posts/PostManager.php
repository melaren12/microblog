<?php

namespace App\managers\posts;

use App\dal\dto\posts\PostDto;
use App\dal\mapper\posts\PostsMapper;
use App\managers\AbstractManager;
//use App\Post;
use App\util\LogHelper;
use InvalidArgumentException;
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

    public function create(int $user_id, string $content): PostDto
    {
        if (strlen($content) > 1000) {
            LogHelper::getInstance()->createErrorLog('Error creating post: Post content cannot exceed 1000 characters.');
            throw new InvalidArgumentException("Post content cannot exceed 1000 characters.");
        }
        $post = new PostDto();
        $post->setUserId($user_id);
        $post->setContent($content);

        $id = $this->getMapper()->insert($post);

        $post->setId($id);

        return $post;
    }

    public function getAllPosts(): array
    {
        return $this->getMapper()->findAll();

    }

    public function getPostsByUser(int $user_id): array
    {
        $params = ['user_id' => $user_id];
        return (PostManager::getInstance()->getList($params));
    }
}