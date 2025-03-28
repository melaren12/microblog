<?php

require_once 'init.php';
global $pdo;

use App\managers\users\UsersManager;

session_start();

$output = '';
$username = '';
$firstname = '';
$lastname = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $avatar_name = 'default.png';

    try {

        if (!empty($_FILES['avatar']['name'])) {
            $target_dir = "public/uploads/avatars/";

            if (!is_dir($target_dir)) {
                if (!mkdir($target_dir, 0755, true)) {
                    throw new RuntimeException("Failed to create directory for avatar upload.");
                }
            }

            $avatar_name = time() . "_" . basename($_FILES["avatar"]["name"]);
            $target_file = $target_dir . $avatar_name;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowed_types = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($imageFileType, $allowed_types)) {
                throw new RuntimeException("Only JPG, JPEG, PNG & GIF files are allowed.");
            }

            if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                throw new RuntimeException("Failed to upload avatar.");
            }
        }

        $userManager = UsersManager::getInstance();
        $user = $userManager->register($username, $password, $firstname, $lastname, $avatar_name);


        $_SESSION['success_message'] = "Registration successful! Please log in.";
        header("Location: login.php");
        exit;
    } catch (InvalidArgumentException|RuntimeException $e) {
        $output = $e->getMessage();
    } catch (Exception $e) {
        $output = "An unexpected error occurred: " . $e->getMessage();
        error_log("Registration error: " . $e->getMessage());
    }
}

$page_title = "Microblog - Register";
$extra_css = "auth";
$content_template = "src/templates/register.php";
include "src/templates/layout.php";