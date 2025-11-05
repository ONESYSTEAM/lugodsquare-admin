<?php

namespace app;

use app\Controllers\BookingController;
use app\Controllers\POSController;
use app\Controllers\UsersController;

class Router
{
    public static $routes = [];

    public static function init()
    {
        // Define application routes here
        Router::add('/', fn() => (new UsersController())->index());
        Router::add('/login', fn() => (new UsersController())->login($_POST['username'] ?? 0, $_POST['password'] ?? 0), 'POST');
        Router::add('/logout', fn() => (new UsersController())->logout());

        Router::add('/users', fn() => (new UsersController())->getUsers(), 'POST');
        Router::add('/addUser', fn() => Router::render('AddUser'));
        Router::add('/addUser/add', fn() => (new UsersController())->addUser(), 'POST');
        Router::add('/viewUser/{userId}', fn($data) => (new UsersController())->viewUser($data['userId'] ?? 0));
        Router::add('/updateUser/{userId}', fn($data) => (new UsersController())->getUser($data['userId'] ?? 0));
        Router::add('/updateUser/{userId}/update', fn($data) => (new UsersController())->updateUser($data['userId']), 'POST');
        Router::add('/deleteUser/{userId}', fn($data) => (new UsersController())->deleteUser($data['userId'] ?? 0));

        // Court Routes
        Router::add('/courts', fn() => (new BookingController())->getCourts());
        Router::add('/addCourt', fn() => Router::render('AddCourt'));
        Router::add('/addCourt/add', fn() => (new BookingController())->addCourt(), 'POST');
        Router::add('/viewCourt/{courtId}', fn($data) => (new BookingController())->viewCourt($data['courtId'] ?? 0));
        Router::add('/updateCourt/{courtId}', fn($data) => (new BookingController())->getCourt($data['courtId'] ?? 0));
        Router::add('/updateCourt/{courtId}/update', fn($data) => (new BookingController())->updateCourt($data['courtId']), 'POST');
        Router::add('/deleteCourt/{courtId}', fn($data) => (new BookingController())->deleteCourt($data['courtId'] ?? 0));

        // Member Routes
        Router::add('/members', fn() => (new BookingController())->getMembers());
        Router::add('/viewMember/{memberId}', fn($data) => (new BookingController())->viewMember($data['memberId'] ?? 0));

        // Schedule Routes
        Router::add('/schedules', fn() => (new BookingController())->getSchedules());
        Router::add('/viewSchedule/{scheduleId}', fn($data) => (new BookingController())->viewSchedule($data['scheduleId'] ?? 0));

        // Product Routes
        Router::add('/products', fn() => (new POSController())->getProducts());
        Router::add('/addProduct', fn() => Router::render('AddProduct'));
        Router::add('/addProduct/add', fn() => (new POSController())->addProduct(), 'POST');
        Router::add('/updateProduct/{productId}', fn($data) => (new POSController())->getProduct($data['productId'] ?? 0));
        Router::add('/updateProduct/{productId}/update', fn($data) => (new POSController())->updateProduct($data['productId']), 'POST');
        Router::add('/deleteProduct/{productId}', fn($data) => (new POSController())->deleteProduct($data['productId'] ?? 0));

        //Sales Routes
        Router::add('/sales', fn() => (new POSController())->getSales());

        //Inventory Routes
        Router::add('/inventory', fn() => (new POSController())->getInventory());

        Router::run();
    }

    public static function add($path, $callback)
    {
        $path = str_replace(['{', '}'], ['(?P<', '>[^/]+)'], $path);

        Router::$routes[$path] = $callback;
    }

    public static function run()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach (self::$routes as $route => $callback) {
            if (preg_match("#^$route$#", $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                echo call_user_func($callback, $params);

                return;
            }
        }
        echo template()->render('Errors/404');
    }

    public static function render($view, $data = [])
    {
        $viewPath = __DIR__ . "/Views/{$view}.php";

        if (file_exists($viewPath)) {
            $templates = new \League\Plates\Engine(__DIR__ . '/Views');
            echo $templates->render($view, $data);
        } else {
            echo template()->render('Errors/404');
        }
    }
}
