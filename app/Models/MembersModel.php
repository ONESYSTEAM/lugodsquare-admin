<?php

namespace app\Models;

use config\DBConnection;
use PDO;

class MembersModel
{
    private $db;

    public function __construct(DBConnection $db)
    {
        $this->db = $db->getConnection();
    }

    // Add your custom methods below to interact with the database.

    public function getMembers()
    {
        $stmt = $this->db->prepare("SELECT * FROM members");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMemberById($memberId)
    {
        $stmt = $this->db->prepare("SELECT * FROM members WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $memberId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateMember($memberId, $membershipId, $cardId, $firstName, $lastName, $address, $contactNumber, $email, $wallet)
    {
        $stmt = $this->db->prepare("UPDATE members SET membership_id = :membership_id, card_number = :card_id, first_name = :first_name, last_name = :last_name, address = :address, contact_number = :contact_number, email = :email, wallet = :wallet WHERE id = :id");
        $stmt->bindParam(':membership_id', $membershipId, PDO::PARAM_STR);
        $stmt->bindParam(':card_id', $cardId, PDO::PARAM_STR);
        $stmt->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':contact_number', $contactNumber, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':wallet', $wallet, PDO::PARAM_STR);
        $stmt->bindParam(':id', $memberId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateBillingCycle($memberId, $billingCycle)
    {
        // 1. Set Start Date as today
        $startDate = new \DateTime();
        $endDate = new \DateTime();

        // 2. Calculate End Date based on plan
        if ($billingCycle === 'Yearly') {
            $endDate->modify('+1 year');
        } else {
            // Default to Monthly
            $endDate->modify('+1 month');
        }

        // 3. Format for Database (YYYY-MM-DD)
        $startStr = $startDate->format('Y-m-d');
        $endStr = $endDate->format('Y-m-d');

        // 4. Update the SQL to include dates
        $sql = "UPDATE members SET 
                membership_plan = :billing_cycle, 
                subscription_start = :start_date, 
                subscription_end = :end_date 
            WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':billing_cycle', $billingCycle, PDO::PARAM_STR);
        $stmt->bindParam(':start_date', $startStr, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $endStr, PDO::PARAM_STR);
        $stmt->bindParam(':id', $memberId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function activateMembership($memberId)
    {
        $stmt = $this->db->prepare("UPDATE members SET is_active = 1 WHERE id = :id");
        $stmt->bindParam(':id', $memberId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function renewMembership($memberId)
    {
        $stmt = $this->db->prepare("UPDATE members SET subscription_start = CURDATE(), subscription_end = DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
        WHERE membership_plan IS NOT NULL AND id = :id");
        $stmt->bindParam(':id', $memberId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function cancelMembership($memberId)
    {
        $stmt = $this->db->prepare("UPDATE members SET subscription_start = NULL, subscription_end = NULL, is_active = 0, membership_plan = NULL WHERE id = :id");
        $stmt->bindParam(':id', $memberId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function checkExpirations()
    {
        $today = date('Y-m-d');
        $stmt = $this->db->prepare("UPDATE members SET is_active = 0 
            WHERE subscription_end < :today AND is_active = 1");
        $stmt->bindParam(':today', $today, PDO::PARAM_STR);
        $stmt->execute();
        return [
            'updated_rows' => $stmt->rowCount()
        ];
    }
}
