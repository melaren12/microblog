<?php

require_once 'init.php';
global $pdo;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);

    $avatar_name = "default.png";

    if (!empty($_FILES['avatar']['name'])) {
        $target_dir = "uploads/";
        $avatar_name = time() . "_" . basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . $avatar_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Error: Only JPG, JPEG, PNG & GIF files are allowed.");
        }
        if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            die("File upload error.");
        }
    }

    if (empty($username) || empty($password) || empty($firstname) || empty($lastname)) {
        die("All fields are required!");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, name, lastname, avatar) VALUES (:username, :password, :name, :lastname, :avatar)');
        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password,
            'name' => $firstname,
            'lastname' => $lastname,
            'avatar' => $avatar_name
        ]);

        header("Location: login.php");
    } catch (PDOException $e) {
        die("Registration error: " . $e->getMessage());
    }
}

require 'templates/register.php';