<?php

namespace App;
use InvalidArgumentException;
use PDO;
use PDOException;
use RuntimeException;

class User
{
    private $pdo;
    private $id;
    private $name;
    private $lastname;
    private $username;
    private $avatar;

    public function __construct(PDO $pdo, $id = null)
    {
        $this->pdo = $pdo;
        if ($id) {
            $this->loadUser($id);
        }
    }

    private function loadUser($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, name, lastname, username, avatar FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        if ($user) {
            $this->id = $user['id'];
            $this->name = $user['name'];
            $this->lastname = $user['lastname'];
            $this->username = $user['username'];
            $this->avatar = $user['avatar'];
        }
    }

    public static function login(PDO $pdo, $username, $password)
    {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return new self($pdo, $user['id']);
        }
        return null;
    }

    public static function register(PDO $pdo, $username, $password, $firstname, $lastname, $avatar = 'default.png')
    {
        if (empty($username) || empty($password) || empty($firstname) || empty($lastname)) {
            throw new InvalidArgumentException("All fields (username, password, firstname, lastname) are required.");
        }

        if (strlen($password) < 2) {
            throw new InvalidArgumentException("Password must be at least 8 characters long.");
        }

        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if ($hashed_password === false) {
                throw new RuntimeException("Failed to hash the password.");
            }

            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            if ($stmt->fetchColumn() > 0) {
                throw new RuntimeException("Username is already in use.");
            }

            $stmt = $pdo->prepare("INSERT INTO users (username, password, name, lastname, avatar)
                               VALUES (:username, :password, :name, :lastname, :avatar)");
            $stmt->execute([
                'username' => $username,
                'password' => $hashed_password,
                'name' => $firstname,
                'lastname' => $lastname,
                'avatar' => $avatar
            ]);

            return new self($pdo, $pdo->lastInsertId());
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());

            if ($e->getCode() == 1062 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                throw new RuntimeException("Username is already in use.");
            }

            throw new RuntimeException("Registration failed: " . $e->getMessage());
        }
    }

    public function updateAvatar(array $file, string $target_dir, string $current_avatar): void
    {
        if (empty($file['name'])) {
            throw new RuntimeException("No avatar file provided.");
        }

        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                throw new RuntimeException("Could not create directory $target_dir. Check permissions.");
            }
        }

        if (!is_writable($target_dir)) {
            throw new RuntimeException("Directory $target_dir is not writable. Check permissions.");
        }

        $avatar_name = time() . "_" . basename($file["name"]);
        $target_file = $target_dir . $avatar_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            throw new RuntimeException("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if ($current_avatar !== "default.png" && file_exists($target_dir . $current_avatar)) {
            unlink($target_dir . $current_avatar);
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new RuntimeException("Error uploading avatar.");
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
            $stmt->execute(['avatar' => $avatar_name, 'id' => $this->id]);
        } catch (PDOException $e) {

            if (file_exists($target_file)) {
                unlink($target_file);
            }
            throw new RuntimeException("Error updating avatar: " . $e->getMessage());
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }
}
