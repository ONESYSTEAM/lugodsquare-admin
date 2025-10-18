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

    // Add your custom controllers below to handle business logic.
    public function index()
    {
        $userId = $_SESSION["user_id"] ?? '';

        if ($userId == '') {
            echo $GLOBALS['templates']->render('Login');
        }
    }
}
