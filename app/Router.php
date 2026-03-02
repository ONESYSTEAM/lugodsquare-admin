<?php

namespace app;

use app\Controllers\BookingController;
use app\Controllers\MembersController;
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

        // User Routes
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
        Router::add('/members', fn() => (new MembersController())->getMembers());
        Router::add('/viewMember/{memberId}', fn($data) => (new MembersController())->viewMember($data['memberId'] ?? 0));
        //update member route
        Router::add('/updateMember/{memberId}', fn($data) => (new MembersController())->updateMemberView($data['memberId'] ?? 0));
        Router::add('/updateMember/{memberId}/update', fn($data) => (new MembersController())->updateMember($data['memberId']), 'POST');
        //membership plan route
        Router::add('/viewMember/{memberId}/update-billing', fn($data) => (new MembersController())->updateBilling($data['memberId'] ?? 0), 'POST');
        Router::add('/viewMember/{memberId}/activate-membership', fn($data) => (new MembersController())->activateMembership($data['memberId'] ?? 0));
        Router::add('/viewMember/{memberId}/renew-membership', fn($data) => (new MembersController())->renewMembership($data['memberId'] ?? 0));
        Router::add('/viewMember/{memberId}/cancel-membership', fn($data) => (new MembersController())->cancelMembership($data['memberId'] ?? 0));


        // Schedule Routes
        Router::add('/schedules', fn() => (new BookingController())->getSchedules());
        Router::add('/viewSchedule/{scheduleId}', fn($data) => (new BookingController())->viewSchedule($data['scheduleId'] ?? 0));
        Router::add('/viewSchedule/confirm/{scheduleId}', fn($data) => (new BookingController())->confirmSchedule($data['scheduleId'] ?? 0));
        Router::add('/viewSchedule/reschedule/{scheduleId}', fn($data) => (new BookingController())->rescheduleBooking($data['scheduleId'] ?? 0), 'POST');
        Router::add('/viewSchedule/cancel/{scheduleId}', fn($data) => (new BookingController())->cancelSchedule($data['scheduleId'] ?? 0));
        Router::add('/viewSchedule/undoCancel/{scheduleId}', fn($data) => (new BookingController())->undoCancelSchedule($data['scheduleId'] ?? 0));
        Router::add('/archive', fn() => (new BookingController())->getSchedulesArchived());
        Router::add('/viewArchive/{scheduleId}', fn($data) => (new BookingController())->viewArchive($data['scheduleId'] ?? 0));

        // Product Routes
        Router::add('/products', fn() => (new POSController())->getProducts());
        Router::add('/products/{category}', fn($data) => (new POSController())->getProductsByCategory($data['category']));
        Router::add('/addProduct/{category}', fn($data) => (new POSController())->addProductCategorySet($data['category']));
        Router::add('/addProductGeneral', fn() => Router::render('AddProductGeneral'));
        Router::add('/addProductGeneral/add', fn() => (new POSController())->addProduct(), 'POST');
        Router::add('/addProduct/{category}/add', fn() => (new POSController())->addProduct(), 'POST');
        Router::add('/viewProduct/{productId}', fn($data) => (new POSController())->viewProduct($data['productId'] ?? 0));
        Router::add('/updateProduct/{productId}', fn($data) => (new POSController())->getProduct($data['productId'] ?? 0));
        Router::add('/updateProduct/{productId}/update', fn($data) => (new POSController())->updateProduct($data['productId']), 'POST');
        Router::add('/deleteProduct/{productId}', fn($data) => (new POSController())->deleteProduct($data['productId'] ?? 0));

        // Sales Routes
        Router::add('/sales', fn() => (new POSController())->getSales());

        // Inventory Routes
        Router::add('/inventory', fn() => (new POSController())->getInventory());

        // GCash Receipt Route
        Router::add('/gcashReceipt/{fileName}', fn($data) => (new BookingController())->getGcashReceipt($data['fileName']));

        // Reschedule Routes
        Router::add('/get-booked-slots', fn() => (new BookingController())->getBookedSlots(), 'POST');

        // Cashier Sales Route
        Router::add('/cashier-sales', fn() => Router::render('CashierSales'));
        Router::add('/live-cashier-sales', fn() => (new POSController())->liveCashierSales());

        //Amount Paid Route
        Router::add('/setAmountPaid/{scheduleId}', fn($data) => (new BookingController())->setAmountPaid($data['scheduleId'] ?? 0));

        //login & logout with ID route
        Router::add('/attendance', fn() => (new UsersController())->attendance());
        Router::add('/attendance/timeIn', fn() => (new UsersController())->timeIn($_POST['idNumber'] ?? 0), 'POST');
        Router::add('/attendance/timeOut', fn() => (new UsersController())->timeOut($_POST['idNumber'] ?? 0), 'POST');
        Router::add('/attendance-logs', fn() => (new UsersController())->showLogs());

        //calendar route
        Router::add('/calendar', fn() => Router::render('Calendar'));
        Router::add('/get-booked-dates', fn() => (new BookingController())->getBookedDates(), 'POST');

        // Check Expirations Route
        Router::add('/check-expirations', fn() => (new MembersController())->checkExpirations());


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
