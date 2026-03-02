<?php

namespace app\Controllers;

use config\DBConnection;
use app\Models\MembersModel;

class MembersController
{
    private $MembersModel;

    public function __construct()
    {
        $db = new DBConnection();
        $this->MembersModel = new MembersModel($db);
        $this->restrictToBookings();
    }

    // Add your custom controllers below to handle business logic.
    public function restrictToBookings()
    {
        $currentUri = strtolower($_SERVER['REQUEST_URI']);

        // 1. PUBLIC ROUTES: Do NOT run restrictions on these paths
        $publicPaths = ['/login', '/', '/logout']; // Add your login processing route here
        foreach ($publicPaths as $path) {
            if ($currentUri == $path || $currentUri == $path . '/') {
                return; // Exit the function and allow the page to load
            }
        }

        // 2. Safety check: If not logged in at all, go to login
        if (!isset($_SESSION['user_type'])) {
            header('Location: /login');
            exit();
        }

        // 3. Identify the Cashier (Type 2)
        if ($_SESSION['user_type'] == 2) {
            $allowedKeywords = [
                '/courts',
                '/addcourt',
                '/viewcourt',
                '/updatecourt',
                '/deletecourt',
                '/schedules',
                '/viewschedule',
                '/archive',
                '/viewarchive',
                '/calendar',
                '/get-booked-dates',
                '/get-booked-slots',
                '/setamountpaid',
                '/gcashreceipt'
            ];

            $isAllowed = false;
            foreach ($allowedKeywords as $keyword) {
                if (str_contains($currentUri, $keyword)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                header('Location: /schedules?error=unauthorized');
                exit();
            }
        }
    }

    public function getMembers()
    {
        $members = $this->MembersModel->getMembers();
        echo $GLOBALS['templates']->render('Members', ['members' => $members]);
    }

    private function getSubscriptionStatus($member)
    {
        if (empty($member['subscription_end'])) {
            return 'No Plan';
        }

        $now = new \DateTime();
        $expiry = new \DateTime($member['subscription_end']);

        if ($now > $expiry) {
            return 'Expired'; // This is your "Past Due" state
        }

        return 'Active';
    }

    public function viewMember($memberId)
    {
        $member = $this->MembersModel->getMemberById($memberId);
        $isExpired = $this->getSubscriptionStatus($member);
        echo $GLOBALS['templates']->render('ViewMember', ['member' => $member, 'isExpired' => $isExpired]);
    }

    public function updateMemberView($memberId)
    {
        $member = $this->MembersModel->getMemberById($memberId);

        if (!$member) {
            $_SESSION['danger'][] = 'Member not found.';
            header("Location: /members");
            exit;
        }

        echo $GLOBALS['templates']->render('UpdateMember', [
            'member' => $member
        ]);
    }

    public function updateMember($memberId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['firstName'] ?? ' ';
            $lastName = $_POST['lastName'] ?? ' ';
            $address = $_POST['address'] ?? ' ';
            $contactNumber = $_POST['contactNumber'] ?? ' ';
            $email = $_POST['email'] ?? ' ';
            $membershipId = $_POST['membershipId'] ?? ' ';
            $cardId = $_POST['cardId'] ?? ' ';
            $wallet = $_POST['wallet'] ?? ' ';

            $updateMember = $this->MembersModel->updateMember($memberId, $membershipId, $cardId, $firstName, $lastName, $address, $contactNumber, $email, $wallet);

            if ($updateMember) {
                $_SESSION['success'][] = 'Member Updated Successfully!';
                header("Location: /members");
                exit;
            } else {
                $_SESSION['danger'][] = 'Update member failed.';
                header("Location: /updatemember/$memberId");
                exit;
            }
        }
    }

    public function updateBilling($memberId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $billingCycle = $_POST['billing_cycle'];

            $update = $this->MembersModel->updateBillingCycle($memberId, $billingCycle);
            if ($update) {
                $_SESSION['success'][] = 'Membership plan updated successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to update membership plan.';
            }
            header("Location: /viewMember/$memberId");
            exit;
        }
    }

    public function activateMembership($memberId)
    {
        $activate = $this->MembersModel->activateMembership($memberId);
        if ($activate) {
            $_SESSION['success'][] = 'Membership activated successfully.';
        } else {
            $_SESSION['danger'][] = 'Failed to activate membership.';
        }
        header("Location: /viewMember/$memberId");
        exit;
    }

    public function renewMembership($memberId)
    {
        $renew = $this->MembersModel->renewMembership($memberId);
        if ($renew) {
            $_SESSION['success'][] = 'Membership renewed successfully.';
        } else {
            $_SESSION['danger'][] = 'Failed to renew membership.';
        }
        header("Location: /viewMember/$memberId");
        exit;
    }

    public function cancelMembership($memberId)
    {
        $renew = $this->MembersModel->cancelMembership($memberId);
        if ($renew) {
            $_SESSION['success'][] = 'Membership canceled successfully.';
        } else {
            $_SESSION['danger'][] = 'Failed to cancel membership.';
        }
        header("Location: /viewMember/$memberId");
        exit;
    }

    public function checkExpirations()
    {
        $isExpired = $this->MembersModel->checkExpirations();

        echo json_encode([
            'status' => 'success',
            'checked_at' => date('H:i:s'),
            'updated_rows' => $isExpired['updated_rows']
        ]);
        exit;
    }
}
