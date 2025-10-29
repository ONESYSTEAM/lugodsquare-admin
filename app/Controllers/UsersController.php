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
            echo $GLOBALS['templates']->render('Dashboard');
            exit;
        }
        if ($userType != 1) {
            $_SESSION['danger'][] = 'You are not allowed to proceed to the page you requested.';
            echo $GLOBALS['templates']->render('Login');
            exit;
        }

        header('Location: /');
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

            $addUser = $this->UsersModel->addUser($firstName, $lastName, $username, $userType, $hashedPassword);
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
        // $userId = $id['id'] ?? 0;
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
            // $userId = $_POST['userId'] ?? 0;
            $firstName = $_POST['firstName'] ?? ' ';
            $lastName = $_POST['lastName'] ?? ' ';
            $password = trim($_POST['password'] ?? ' ');
            $userType = $_POST['userType'] ?? ' ';
            $username = $_POST['username'] ?? ' ';

            $user = $this->UsersModel->getUserById($userId);
            if (empty($password)) {
                $hashedPassword = $user['password'];
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            }

            $updateUser = $this->UsersModel->updateUser($userId, $firstName, $lastName, $username, $userType, $hashedPassword);
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
}
