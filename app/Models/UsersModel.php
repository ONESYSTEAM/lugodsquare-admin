<?php

namespace app\Models;

use config\DBConnection;
use PDO;

class UsersModel
{
    private $db;

    public function __construct(DBConnection $db)
    {
        $this->db = $db->getConnection();
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users_tbl WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users_tbl WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($firstName, $lastName, $username, $userType, $password)
    {
        $stmt = $this->db->prepare("INSERT INTO users_tbl (user_type, username, password, first_name, last_name) VALUES (:user_type, :username, :password, :first_name, :last_name)");
        $stmt->bindParam(':user_type', $userType, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getUserById($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users_tbl WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $firstName, $lastName, $username, $userType, $password)
    {
        $stmt = $this->db->prepare("UPDATE users_tbl SET user_type = :user_type, username = :username, password = :password, first_name = :first_name, last_name = :last_name WHERE id = :id");
        $stmt->bindParam(':user_type', $userType, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteUser($userId, $adminId)
    {
        $stmt = $this->db->prepare("UPDATE users_tbl SET is_deleted = 1, deleted_by = :admin WHERE id = :id");
        $stmt->bindParam(':admin', $adminId, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
}
