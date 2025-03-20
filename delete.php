<?php
global $pdo;
require 'init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$id = $_GET['id'];

$stmt = $pdo->prepare('DELETE FROM user_photos WHERE id = :id AND user_id = :user_id');
$stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id']]);

header('Location: ChangeProfile.php');
exit;
