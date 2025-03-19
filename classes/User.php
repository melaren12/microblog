<?php
class User {
    private $pdo;
    private $id;
    private $name;
    private $lastname;
    private $username;
    private $avatar;

    public function __construct(PDO $pdo, $id = null) {
        $this->pdo = $pdo;
        if ($id) {
            $this->loadUser($id);
        }
    }

    private function loadUser($id) {
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

    public static function login(PDO $pdo, $username, $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return new self($pdo, $user['id']);
        }
        return null;
    }

    public static function register(PDO $pdo, $username, $password, $firstname, $lastname, $avatar = 'default.png') {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, name, lastname, avatar) VALUES (:username, :password, :name, :lastname, :avatar)');
        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password,
            'name' => $firstname,
            'lastname' => $lastname,
            'avatar' => $avatar
        ]);
        return new self($pdo, $pdo->lastInsertId());
    }

    public function updateAvatar($avatar_name) {
        $stmt = $this->pdo->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
        $stmt->execute(['avatar' => $avatar_name, 'id' => $this->id]);
        $this->avatar = $avatar_name;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getLastname() { return $this->lastname; }
    public function getUsername() { return $this->username; }
    public function getAvatar() { return $this->avatar; }
}
