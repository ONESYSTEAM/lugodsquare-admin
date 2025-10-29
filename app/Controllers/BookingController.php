<?php

namespace app\Controllers;

use config\DBConnection;
use app\Models\BookingModel;

class BookingController
{
    private $BookingModel;

    public function __construct()
    {
        $db = new DBConnection();
        $this->BookingModel = new BookingModel($db);
    }

    // Add your custom controllers below to handle business logic.

    public function getCourts()
    {
        $courts = $this->BookingModel->getCourts();
        echo $GLOBALS['templates']->render('Courts', ['courts' => $courts]);
    }

    public function addCourt()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $courtType = trim($_POST['courtType'] ?? '');
            $capacity = trim($_POST['capacity'] ?? '');
            $amount = trim($_POST['amount'] ?? '');

            $court = $this->BookingModel->addCourt($courtType, $capacity, $amount);
            if ($court) {
                $_SESSION['success'][] = 'Court added successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to add court. Please try again.';
            }

            header('Location: /courts');
            exit;
        }
    }

    public function getCourt($courtId)
    {
        $court = $this->BookingModel->getCourtById($courtId);
        echo $GLOBALS['templates']->render('UpdateCourt', ['court' => $court]);
    }

    public function viewCourt($courtId)
    {
        $court = $this->BookingModel->getCourtById($courtId);
        echo $GLOBALS['templates']->render('ViewCourt', ['court' => $court]);
    }

    public function updateCourt($courtId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $courtType = trim($_POST['courtType'] ?? '');
            $capacity = trim($_POST['capacity'] ?? '');
            $amount = trim($_POST['amount'] ?? '');

            $updated = $this->BookingModel->updateCourt($courtId, $courtType, $capacity, $amount);
            if ($updated) {
                $_SESSION['success'][] = 'Court updated successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to update court. Please try again.';
            }

            header('Location: /courts');
            exit;
        }
    }

    public function deleteCourt($courtId)
    {
        $userId = $_SESSION['user_id'] ?? 0;

        $deleted = $this->BookingModel->deleteCourt($courtId, $userId);
        if ($deleted) {
            $_SESSION['success'][] = 'Court deleted successfully.';
        } else {
            $_SESSION['danger'][] = 'Failed to delete court. Please try again.';
        }

        header('Location: /courts');
        exit;
    }

    // Member controllers
    public function getMembers()
    {
        $members = $this->BookingModel->getMembers();
        echo $GLOBALS['templates']->render('Members', ['members' => $members]);
    }

    public function viewMember($memberId)
    {
        $member = $this->BookingModel->getMemberById($memberId);
        echo $GLOBALS['templates']->render('ViewMember', ['member' => $member]);
    }

    // Schedule controllers
    public function getSchedules()
    {
        $schedules = $this->BookingModel->getSchedules();
        echo $GLOBALS['templates']->render('Schedules', ['schedules' => $schedules]);
    }

    public function viewSchedule($scheduleId)
    {
        $schedule = $this->BookingModel->getScheduleById($scheduleId);
        echo $GLOBALS['templates']->render('ViewSchedule', ['schedule' => $schedule]);
    }
}
