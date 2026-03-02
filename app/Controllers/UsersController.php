<?php

namespace app\Controllers;

use config\DBConnection;
use app\Models\UsersModel;

class UsersController
{
    private $UsersModel;

    public function __construct()
    {
        $db = new DBConnection();
        $this->UsersModel = new UsersModel($db);
        $this->restrictToBookings();
    }

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

    public function index()
    {
        $userId = $_SESSION['user_id'] ?? '';
        $userType = $_SESSION['user_type'] ?? '';

        if ($userId == '') {
            echo $GLOBALS['templates']->render('Login');
            exit;
        }
        if ($userId != 0) {
            $status = $this->attendanceStatus();
            echo $GLOBALS['templates']->render('Dashboard', [
                'isTimedIn' => $status
            ]);
            exit;
        }
        if ($userType != 1) {
            $_SESSION['danger'][] = 'You are not allowed to proceed to the page you requested.';
            echo $GLOBALS['templates']->render('Login');
            exit;
        }

        header('Location: /login');
        exit;
    }

    public function login($username, $password)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo $GLOBALS['templates']->render('Login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($username) || empty($password)) {
            $_SESSION['danger'][] = 'All fields are required.';
            echo $GLOBALS['templates']->render('Login');
            exit;
        }

        $user = $this->UsersModel->getUserByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['danger'][] = 'Invalid username or password.';
            echo $GLOBALS['templates']->render('Login');
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];

        header('Location: /');
        exit;
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("Location: /login");
        exit;
    }

    public function getUsers()
    {
        $users = $this->UsersModel->getUsers();
        echo $GLOBALS['templates']->render('Users', [
            'users' => $users
        ]);
    }

    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['firstName'] ?? ' ';
            $lastName = $_POST['lastName'] ?? ' ';
            $password = trim($_POST['password'] ?? ' ');
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $userType = $_POST['userType'] ?? ' ';
            $username = $_POST['username'] ?? ' ';
            $IdNumber = $_POST['idNumber'] ?? ' ';

            $addUser = $this->UsersModel->addUser($firstName, $lastName, $username, $userType, $hashedPassword, $IdNumber);
            if ($addUser) {
                $_SESSION['success'][] = 'User Added Successfully!';
                header("Location: /addUser");
                exit;
            } else {
                $_SESSION['danger'][] = 'Add user failed.';
                header("Location: /addUser");
                exit;
            }
        }
    }

    public function getUser($id)
    {
        $user = $this->UsersModel->getUserById($id);

        if (!$user) {
            $_SESSION['danger'][] = 'User not found.';
            header("Location: /users");
            exit;
        }

        echo $GLOBALS['templates']->render('UpdateUser', [
            'user' => $user
        ]);
    }

    public function updateUser($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['firstName'] ?? ' ';
            $lastName = $_POST['lastName'] ?? ' ';
            $password = trim($_POST['password'] ?? ' ');
            $userType = $_POST['userType'] ?? ' ';
            $username = $_POST['username'] ?? ' ';
            $idNumber = $_POST['idNumber'] ?? ' ';

            $user = $this->UsersModel->getUserById($userId);
            if (empty($password)) {
                $hashedPassword = $user['password'];
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            }

            $updateUser = $this->UsersModel->updateUser($userId, $firstName, $lastName, $username, $userType, $hashedPassword, $idNumber);
            if ($updateUser) {
                $_SESSION['success'][] = 'User Updated Successfully!';
            } else {
                $_SESSION['danger'][] = 'Update user failed.';
            }
            header("Location: /users");
            exit;
        }
    }

    public function viewUser($id)
    {
        $user = $this->UsersModel->getUserById($id);
        echo $GLOBALS['templates']->render('ViewUser', [
            'user' => $user
        ]);
    }

    public function deleteUser($userId)
    {
        $adminId = $_SESSION['user_id'] ?? 0;

        $deleteUser = $this->UsersModel->deleteUser($userId, $adminId);
        if ($deleteUser) {
            $_SESSION['success'][] = 'User deleted successfully.';
        } else {
            $_SESSION['danger'][] = 'Failed to delete user.';
        }

        header("Location: /users");
        exit;
    }

    public function attendance()
    {
        $status = $this->attendanceStatus();
        echo $GLOBALS['templates']->render('Attendance', [
            'isTimedIn' => $status
        ]);
    }

    private function attendanceStatus()
    {
        $isTimedIn = false;
        if (isset($_SESSION['user_id'])) {
            $attendance = $this->UsersModel->getAttendanceByUserId($_SESSION['user_id']);
            if ($attendance && !$attendance['time_out']) {
                $isTimedIn = true;
            }
        }
        return $isTimedIn;
    }

    public function timeIn($idNumber)
    {
        $loggedInUserId = $_SESSION['user_id'] ?? 0;

        $isOwner = $this->UsersModel->verifyIdOwnership($idNumber, $loggedInUserId);

        if (!$isOwner) {
            $_SESSION['danger'][] = 'This ID card does not belong to your account.';
            header("Location: /attendance");
            exit;
        }
        $result = $this->UsersModel->timeIn($idNumber, $_SESSION['user_id'] ?? 0);
        if ($result) {
            $_SESSION['success'][] = 'Time in successful. Have a great day!';
        } else {
            $_SESSION['danger'][] = 'Failed to time in.';
        }
        header("Location: /");
        exit;
    }

    public function timeOut($idNumber)
    {
        $loggedInUserId = $_SESSION['user_id'] ?? 0;

        $isOwner = $this->UsersModel->verifyIdOwnership($idNumber, $loggedInUserId);

        if (!$isOwner) {
            $_SESSION['danger'][] = 'This ID card does not belong to your account.';
            header("Location: /attendance");
            exit;
        }
        $result = $this->UsersModel->timeOut($idNumber);
        if ($result) {
            $_SESSION['success'][] = 'Time out successful.';
        } else {
            $_SESSION['danger'][] = 'Failed to time out.';
        }
        header("Location: /");
        exit;
    }

    public function showLogs()
    {
        $userId = $_SESSION['user_id'] ?? '';
        if ($userId === '') {
            header('Location: /login');
            exit;
        }
        $logs = $this->UsersModel->getDailyLogs($userId);
        $historicalLogs = $this->UsersModel->getHistoricalLogs();

        $status = $this->attendanceStatus();
        echo $GLOBALS['templates']->render('AttendanceLogs', [
            'attendances' => $logs,
            'history' => $historicalLogs,
            'isTimedIn'   => $status
        ]);
    }
}
