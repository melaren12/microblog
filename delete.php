<?php
header('Content-Type: application/json');

global $pdo;

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Invalid photo ID']);
    exit;
}

$stmt = $pdo->prepare('SELECT photo_path FROM user_photos WHERE id = :id AND user_id = :user_id');
$stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id']]);
$photo = $stmt->fetch();

if (!$photo) {
    echo json_encode(['success' => false, 'error' => 'Photo not found or not authorized']);
    exit;
}

if (file_exists($photo['photo_path'])) {
    if (!unlink($photo['photo_path'])) {
        echo json_encode(['success' => false, 'error' => 'Could not delete file']);
        exit;
    }
}

$stmt = $pdo->prepare('DELETE FROM user_photos WHERE id = :id AND user_id = :user_id');
if (!$stmt->execute(['id' => $id, 'user_id' => $_SESSION['user_id']])) {
    echo json_encode(['success' => false, 'error' => 'Could not delete photo from database']);
    exit;
}

echo json_encode(['success' => true]);
exit;
