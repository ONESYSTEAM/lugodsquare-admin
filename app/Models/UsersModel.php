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
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($firstName, $lastName, $username, $userType, $password, $IdNumber)
    {
        $stmt = $this->db->prepare("INSERT INTO users (user_type, username, password, first_name, last_name, id_number) VALUES (:user_type, :username, :password, :first_name, :last_name, :id_number)");
        $stmt->bindParam(':user_type', $userType, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':id_number', $IdNumber, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getUserById($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $firstName, $lastName, $username, $userType, $password, $idNumber)
    {
        $stmt = $this->db->prepare("UPDATE users SET user_type = :user_type, username = :username, password = :password, first_name = :first_name, last_name = :last_name, id_number = :id_number WHERE id = :id");
        $stmt->bindParam(':user_type', $userType, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':id_number', $idNumber, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteUser($userId, $adminId)
    {
        $stmt = $this->db->prepare("UPDATE users SET is_deleted = 1, deleted_by = :admin WHERE id = :id");
        $stmt->bindParam(':admin', $adminId, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUserByIdNumber($idNumber)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id_number = :id_number LIMIT 1");
        $stmt->bindParam(':id_number', $idNumber, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function verifyIdOwnership($idNumber, $userId)
    {
        // Use a COUNT or SELECT to see if a record exists with BOTH values
        $stmt = $this->db->prepare("SELECT id FROM users WHERE id_number = :id_number AND id = :id LIMIT 1");
        $stmt->bindParam(':id_number', $idNumber);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // If a row is returned, the ownership is valid
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
    public function timeIn($idNumber, $userId)
    {
        $stmt = $this->db->prepare("INSERT INTO attendance (user_id, id_number, time_in, work_date) VALUES (:user_id, :id_number, NOW(), CURDATE())");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':id_number', $idNumber, PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function timeOut($idNumber)
    {
        $stmt = $this->db->prepare("UPDATE attendance SET time_out = NOW() WHERE id_number = :id_number AND time_out IS NULL");
        $stmt->bindParam(':id_number', $idNumber, PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function getAttendanceByUserId($userId)
    {
        // We add work_date = CURDATE() to ensure we only catch shifts started today
        $stmt = $this->db->prepare("SELECT * FROM attendance 
            WHERE user_id = :user_id AND work_date = CURDATE() 
            AND time_out IS NULL 
            ORDER BY time_in DESC 
            LIMIT 1
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDailyLogs()
    {
        $stmt = $this->db->prepare(
            "SELECT a.user_id, a.id_number, a.time_in, a.time_out, u.first_name, u.last_name 
            FROM attendance a
            JOIN users u ON a.user_id = u.id
            WHERE a.work_date = CURDATE()
            ORDER BY a.time_in DESC"
        );

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoricalLogs()
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.first_name, u.last_name 
        FROM attendance a
        JOIN users u ON a.user_id = u.id
        -- Change < to <= just to see if data appears
        WHERE a.work_date <= CURDATE() 
        ORDER BY a.work_date DESC, a.time_in DESC"
        );

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
