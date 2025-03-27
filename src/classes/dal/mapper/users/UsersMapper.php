<?php

namespace App\dal\mapper\users;

 use App\dal\mapper\AbstractMapper;
 use App\User;
 use PDO;

 class  UsersMapper  extends AbstractMapper
{
     private static ?UsersMapper $instance = null;
     protected string $tableName = 'users';

     public function getPDO(): PDO
     {
         return $this->PDO;
     }

     public static function getInstance(): UsersMapper
     {
         if (self::$instance === null) {
             self::$instance = new self();
         }
         return self::$instance;
     }

     private function populateUser(User $user, array $data): void
     {
         $user->setId($data['id']);
         $user->setName($data['name']);
         $user->setLastname($data['lastname']);
         $user->setUsername($data['username']);
         $user->setAvatar($data['avatar']);
         if (isset($data['password'])) {
             $user->setPassword($data['password']);
         }
     }

     public function findById(int $id): ?User
     {
         $stmt = $this->PDO->prepare(query: "SELECT id, name, lastname, username, avatar FROM users WHERE id = :id");
         $stmt->execute(['id' => $id]);

         $userData = $stmt->fetch(PDO::FETCH_ASSOC);

         if (!$userData) {
             return null;
         }

         $user = new User($this->PDO);
         $this->populateUser($user, $userData);

         return $user;
     }

     public function findByUsername(string $username): ?User
     {
         $stmt = $this->PDO->prepare("SELECT * FROM users WHERE username = :username");
         $stmt->execute(['username' => $username]);
         $userData = $stmt->fetch();

         if (!$userData) {
             return null;
         }

         $user = new User($this->PDO);
         $this->populateUser($user, $userData);
         return $user;
     }

     public function insert(User $user): void
     {
         $stmt = $this->PDO->prepare(
             "INSERT INTO users (username, password, name, lastname, avatar) 
             VALUES (:username, :password, :name, :lastname, :avatar)"
         );
         $stmt->execute([
             'username' => $user->getUsername(),
             'password' => $user->getPassword(),
             'name' => $user->getName(),
             'lastname' => $user->getLastname(),
             'avatar' => $user->getAvatar() ?? 'default.png'
         ]);

         $user->setId((int)$this->PDO->lastInsertId());
     }

     public function updateAvatar(User $user): void
     {
         $stmt = $this->PDO->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
         $stmt->execute([
             'avatar' => $user->getAvatar(),
             'id' => $user->getId()
         ]);
     }

 }






