<?php

namespace App\managers\users;

use App\dal\mapper\users\UsersMapper;
use App\managers\AbstractManager;
use App\User;
use InvalidArgumentException;
use RuntimeException;

class  UsersManager extends AbstractManager
{
    private static ?self $instance = null;

    public static function getInstance(): UsersManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getMapper(): UsersMapper
    {
        return UsersMapper::getInstance();
    }

    public function getUserById(int $id): ?User
    {
        $mapper = $this->getMapper();
        $user = $mapper->findById($id);
        return $user;
    }

    public function register(string $username, string $password, string $firstname, string $lastname, string $avatar = 'default.png'): User
    {

        if (empty($username) || empty($password) || empty($firstname) || empty($lastname)) {
            throw new InvalidArgumentException("All fields (username, password, firstname, lastname) are required.");
        }

        if (strlen($password) < 2) {
            throw new InvalidArgumentException("Password must be at least 8 characters long.");
        }

        $existingUser = $this->getMapper()->findByUsername($username);
        if ($existingUser) {
            throw new RuntimeException("Username is already in use.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($hashedPassword === false) {
            throw new RuntimeException("Failed to hash the password.");
        }

        $user = new User($this->getMapper()->getPDO());
        $user->setUsername($username);
        $user->setPassword($hashedPassword);
        $user->setName($firstname);
        $user->setLastname($lastname);
        $user->setAvatar($avatar);

        $this->getMapper()->insert($user);

        return $user;
    }

    public function login(string $username, string $password): ?User
    {
        $user = $this->getMapper()->findByUsername($username);
        if ($user && password_verify($password, $user->getPassword())) {
            return $user;
        }
        return null;
    }

    public function updateAvatar(User $user, array $file, string $target_dir, string $current_avatar): void
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
            $user->setAvatar($avatar_name);
            $this->getMapper()->updateAvatar($user);
        } catch (\Exception $e) {
            if (file_exists($target_file)) {
                unlink($target_file);
            }
            throw new RuntimeException("Error updating avatar: " . $e->getMessage());
        }
    }

}






