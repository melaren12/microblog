<?php

namespace App\dal\mapper\users;

 use App\dal\dto\users\UserDto;
 use App\dal\mapper\AbstractMapper;

 class  UsersMapper  extends AbstractMapper
{
     private static ?UsersMapper $instance = null;
     protected string $tableName = 'users';

     public static function getInstance(): UsersMapper
     {
         if (self::$instance === null) {
             self::$instance = new self();
         }
         return self::$instance;
     }

     function createDto(): UserDto
     {
         return new UserDto();
     }
     public function findById(int $id): ?UserDto
     {
         $users = $this->getList(['id' => $id]);

         if (empty($users)) {
             return null;
         }

         return $users[0];
     }

     public function findByUsername(string $username): ?UserDto
     {
         $users = $this->getList(['username' => $username]);

         if (empty($users)) {
             return null;
         }

         return $users[0];
     }

     public function insert(UserDto $user): void
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

     public function updateAvatar(UserDto $user): void
     {
         $stmt = $this->PDO->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
         $stmt->execute([
             'avatar' => $user->getAvatar(),
             'id' => $user->getId()
         ]);
     }
 }






