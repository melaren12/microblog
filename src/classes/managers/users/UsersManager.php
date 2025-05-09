<?php

namespace App\managers\users;

use App\dal\dto\users\UserDto;
use App\dal\mapper\users\UsersMapper;
use App\managers\AbstractManager;
use App\util\LogHelper;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class  UsersManager extends AbstractManager
{
    private static ?self $instance = null;

    private function __construct()
    {
    }

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

    public function getUserById(?int $id): ?UserDto
    {
        $mapper = $this->getMapper();
        return $mapper->findById($id);
    }

    /**
     * @throws Exception
     */
    public function register(string $username, string $password, string $firstname, string $lastname, string $avatar = 'default.png'): UserDto
    {

        if (empty($username) || empty($password) || empty($firstname) || empty($lastname)) {
            LogHelper::getInstance()->createErrorLog('Registration failed! All fields (username, password, firstname, lastname) are required.');
            throw new InvalidArgumentException("All fields (username, password, firstname, lastname) are required.");
        }

        if (strlen($password) < 2) {
            LogHelper::getInstance()->createErrorLog($username . ' Registration failed! Password must be at least 8 characters long.');
            throw new InvalidArgumentException("Password must be at least 2 characters long.");
        }

        $existingUser = $this->getMapper()->findByUsername($username);
        if ($existingUser) {
            LogHelper::getInstance()->createErrorLog($username . ' Registration failed! Username is already in use.');
            throw new RuntimeException("Username is already in use.");
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        if ($hashedPassword === false) {
            LogHelper::getInstance()->createErrorLog($username . ' Registration failed! Failed to hash the password.');
            throw new RuntimeException("Registration failed! Try again.");
        }

        $user = new UserDto();
        $user->setUsername($username);
        $user->setPassword($hashedPassword);
        $user->setName($firstname);
        $user->setLastname($lastname);
        $user->setAvatar($avatar);

        $this->getMapper()->insert($user);

        return $user;
    }

    public function login(string $username, string $password): ?UserDto
    {
        $user = $this->getMapper()->findByUsername($username);
        if ($user && password_verify($password, $user->getPassword())) {
            return $user;
        }
        return null;
    }

    public function updateAvatar(UserDto $user, array $file, string $targetDir, string $current_avatar): void
    {
        if (empty($file['name'])) {
            LogHelper::getInstance()->createErrorLog('updateAvatar error: ' . 'Update user avatar failed!');
            throw new RuntimeException("No avatar file provided.");
        }

        if (!file_exists($targetDir)) {
            if (!mkdir($targetDir, 0777, true)) {
                LogHelper::getInstance()->createErrorLog('updateAvatar error: ' . 'Could not create directory' . $targetDir . '. Check permissions.');
            }
        }

        if (!is_writable($targetDir)) {
            LogHelper::getInstance()->createErrorLog('updateAvatar error: ' . 'Directory' . $targetDir . 'is not writable. Check permissions.');
        }

        $avatarName = time() . "_" . basename($file["name"]);
        $targetFile = $targetDir . $avatarName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            LogHelper::getInstance()->createErrorLog('updateAvatar error: ' . 'Invalid file type: ' . $imageFileType);
            throw new RuntimeException("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        if ($current_avatar !== "default.png" && file_exists($targetDir . $current_avatar)) {
            unlink($targetDir . $current_avatar);
        }

        if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
            LogHelper::getInstance()->createErrorLog('updateAvatar error: ' . 'Could not upload avatar: ' . $targetFile);
            throw new RuntimeException("Error uploading avatar.");
        }

        try {
            $user->setAvatar($avatarName);
            $this->getMapper()->updateAvatar($user);
            LogHelper::getInstance()->createInfoLog('updateAvatar success!');
        } catch (Exception $e) {
            if (file_exists($targetFile)) {
                unlink($targetFile);
            }
            LogHelper::getInstance()->createErrorLog('updateAvatar error: ' . 'Error updating avatar:' . $e->getMessage());
            throw new RuntimeException("Error updating avatar: " . $e->getMessage());
        }
    }
}