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
        $stmt = $this->db->prepare("SELECT * FROM courts WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCourt($courtType, $capacity, $amount)
    {
        $stmt = $this->db->prepare("INSERT INTO courts (court_type, capacity, amount) VALUES (:court_type, :capacity, :amount)");
        $stmt->bindParam(':court_type', $courtType);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':amount', $amount);
        return $stmt->execute();
    }

    public function getCourtById($courtId)
    {
        $stmt = $this->db->prepare("SELECT * FROM courts WHERE id = :id");
        $stmt->bindParam(':id', $courtId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCourt($courtId, $courtType, $capacity, $amount)
    {
        $stmt = $this->db->prepare("UPDATE courts SET court_type = :court_type, capacity = :capacity, amount = :amount WHERE id = :id");
        $stmt->bindParam(':court_type', $courtType);
        $stmt->bindParam(':capacity', $capacity);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':id', $courtId);
        return $stmt->execute();
    }

    public function deleteCourt($courtId, $userId)
    {
        $stmt = $this->db->prepare("UPDATE courts SET is_deleted = 1, deleted_by = :user WHERE id = :id");
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
        INNER JOIN courts AS c ON b.court_type = c.id
        WHERE DATE(b.date) >= CURDATE()
        ORDER BY b.date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSchedulesArchived()
    {
        $stmt = $this->db->prepare("SELECT b.*, c.court_type AS court_name
        FROM booking AS b
        INNER JOIN courts AS c ON b.court_type = c.id
        WHERE DATE(b.date) < CURDATE() AND b.status = 1
        ORDER BY b.date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getScheduleById($scheduleId)
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, c.court_type AS court_name
            FROM booking AS b
            INNER JOIN courts AS c ON b.court_type = c.id WHERE b.id = :id"
        );
        $stmt->bindParam(':id', $scheduleId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getArchivedScheduleById($scheduleId)
    {
        $stmt = $this->db->prepare(
            "SELECT b.*, c.court_type AS court_name
            FROM booking AS b
            INNER JOIN courts AS c ON b.court_type = c.id WHERE b.id = :id"
        );
        $stmt->bindParam(':id', $scheduleId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function confirmSchedule($scheduleId, $remainingAmount)
    {
        $stmt = $this->db->prepare("UPDATE booking SET status = 1, total_amount = :remaining WHERE id = :id");
        $stmt->bindParam(':remaining', $remainingAmount);
        $stmt->bindParam(':id', $scheduleId);
        return $stmt->execute();
    }

    public function cancelSchedule($scheduleId)
    {
        $stmt = $this->db->prepare("UPDATE booking SET status = 2 WHERE id = :id");
        $stmt->bindParam(':id', $scheduleId);
        return $stmt->execute();
    }
    public function undoCancelSchedule($scheduleId)
    {
        $stmt = $this->db->prepare("UPDATE booking SET status = 0 WHERE id = :id");
        $stmt->bindParam(':id', $scheduleId);
        return $stmt->execute();
    }

    public function bookedSlots($court, $date, $exclude_id = null)
    {
        $sql = "SELECT start_time, end_time FROM booking 
            WHERE court_type = :court 
            AND date = :date 
            AND status != 2";
        if ($exclude_id) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':court', $court, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);

        if ($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rescheduleBooking($scheduleId, $newDate, $newStartTime, $newEndTime)
    {
        $stmt = $this->db->prepare("UPDATE booking SET date = :newDate, start_time = :newStartTime, end_time = :newEndTime WHERE id = :id");
        $stmt->bindParam(':newDate', $newDate);
        $stmt->bindParam(':newStartTime', $newStartTime);
        $stmt->bindParam(':newEndTime', $newEndTime);
        $stmt->bindParam(':id', $scheduleId);
        return $stmt->execute();
    }
}
