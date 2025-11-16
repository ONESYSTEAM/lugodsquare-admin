<?php

namespace app\Models;

use config\DBConnection;
use PDO;

class BookingModel
{
    private $db;

    public function __construct(DBConnection $db)
    {
        $this->db = $db->getConnection();
    }

    public function getCourts()
    {
        $stmt = $this->db->prepare("SELECT * FROM courts_tbl WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCourt($courtType, $capacity, $amount)
    {
        $stmt = $this->db->prepare("INSERT INTO courts_tbl (court_type, capacity, amount) VALUES (:court_type, :capacity, :amount)");
        $stmt->bindParam(':court_type', $courtType);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':amount', $amount);
        return $stmt->execute();
    }

    public function getCourtById($courtId)
    {
        $stmt = $this->db->prepare("SELECT * FROM courts_tbl WHERE id = :id");
        $stmt->bindParam(':id', $courtId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCourt($courtId, $courtType, $capacity, $amount)
    {
        $stmt = $this->db->prepare("UPDATE courts_tbl SET court_type = :court_type, capacity = :capacity, amount = :amount WHERE id = :id");
        $stmt->bindParam(':court_type', $courtType);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':id', $courtId);
        return $stmt->execute();
    }

    public function deleteCourt($courtId, $userId)
    {
        $stmt = $this->db->prepare("UPDATE courts_tbl SET is_deleted = 1, deleted_by = :user WHERE id = :id");
        $stmt->bindParam(':user', $userId);
        $stmt->bindParam(':id', $courtId);
        return $stmt->execute();
    }

    public function getMembers()
    {
        $stmt = $this->db->prepare("SELECT * FROM members");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMemberById($memberId)
    {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE id = :id");
        $stmt->bindParam(':id', $memberId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSchedules()
    {
        $stmt = $this->db->prepare("SELECT b.*, c.court_type AS court_name
        FROM booking AS b
        INNER JOIN courts_tbl AS c ON b.court_type = c.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScheduleById($scheduleId)
    {
        $stmt = $this->db->prepare("SELECT b.*, c.court_type AS court_name
            FROM booking AS b
            INNER JOIN courts_tbl AS c ON b.court_type = c.id WHERE b.id = :id"
        );
        $stmt->bindParam(':id', $scheduleId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
