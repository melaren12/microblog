<?php
require_once 'vendor/autoload.php';

$params =[
'user_id' => 6,
    'content' => 'Hi'
];

/** @var \App\dal\dto\posts\PostDto[] $posts */
$posts = (\App\managers\posts\PostManager::getInstance()->getList($params));

print_r($posts);exit();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
header('Location: profile.php');