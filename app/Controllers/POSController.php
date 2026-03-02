<?php

namespace app\Controllers;

use config\DBConnection;
use app\Models\POSModel;

class POSController
{
    private $POSModel;

    public function __construct()
    {
        $db = new DBConnection();
        $this->POSModel = new POSModel($db);
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

    public function getProducts()
    {
        $products = $this->POSModel->fetchProductCategory();
        echo $GLOBALS['templates']->render('Products', ['products' => $products]);
    }

    public function getProductsByCategory($category)
    {
        $products = $this->POSModel->fetchProductItems($category);
        echo $GLOBALS['templates']->render('Product-Items', ['products' => $products, 'category' => $category]);
    }

    public function addProductCategorySet($category)
    {
        echo $GLOBALS['templates']->render('AddProduct', ['category' => $category]);
    }

    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productNumber = $_POST['productNumber'] ?? '';
            $productName = $_POST['productName'] ?? '';
            $price = $_POST['price'] ?? '';
            $qty = $_POST['qty'] ?? '';
            $category = $_POST['productCat'] ?? '';

            $imageName = 'default-product.png';

            if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
                $fileName = $_FILES['productImage']['name'];
                $newFileName = time() . '_' . $fileName; // Using timestamp for uniqueness

                // Save to the CURRENT project's upload folder
                $uploadDir = __DIR__ . '/../../public/uploads/products/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadDir . $newFileName)) {
                    $imageName = $newFileName;
                }
            }

            $product = $this->POSModel->insertProduct($productNumber, $productName, $price, $qty, $category, $imageName);
            if ($product) {
                $_SESSION['success'][] = 'Product Added successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to add product. Please try again.';
            }
            header('Location: /products/' . urlencode($category));
            exit();
        }
    }

    public function getProduct($productId)
    {
        $product = $this->POSModel->fetchProductById($productId);
        echo $GLOBALS['templates']->render('UpdateProduct', ['product' => $product]);
    }

    public function viewProduct($productId)
    {
        $product = $this->POSModel->fetchProductById($productId);
        echo $GLOBALS['templates']->render('ViewProduct', [
            'product' => $product
        ]);
    }

    public function updateProduct($productId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productNumber = $_POST['productNumber'] ?? '';
            $productName = $_POST['productName'] ?? '';
            $price = $_POST['price'] ?? '';
            $qty = $_POST['qty'] ?? '';
            $category = $_POST['productCat'] ?? '';

            $existingImage = $_POST['existingImage'] ?? ''; // From a hidden input

            $imageToSave = $existingImage; // Default to the old image

            // Check if user uploaded a NEW image
            if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
                $fileName = $_FILES['productImage']['name'];
                $newFileName = time() . '_' . $fileName;
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/products/';

                if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadDir . $newFileName)) {
                    $imageToSave = $newFileName; // Update to the new filename

                    // Optional: Delete the old file from the folder to save space
                    if (!empty($existingImage) && file_exists($uploadDir . $existingImage)) {
                        unlink($uploadDir . $existingImage);
                    }
                }
            }

            $updated = $this->POSModel->updateProduct($productId, $productNumber, $productName, $price, $qty, $category, $imageToSave);
            if ($updated) {
                $_SESSION['success'][] = 'Product updated successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to update product. Please try again.';
            }
            header('Location: /products/' . urlencode($category));
            exit();
        }
    }

    public function deleteProduct($productId)
    {
        $userId = $_SESSION['user_id'] ?? 0;
        $deleted = $this->POSModel->deleteProduct($productId, $userId);
        if ($deleted) {
            $_SESSION['success'][] = 'Product deleted successfully.';
        } else {
            $_SESSION['danger'][] = 'Failed to delete product. Please try again.';
        }
        header('Location: /products');
        exit();
    }

    public function getSales()
    {
        $today = date('F j, Y');

        $daily = $this->POSModel->getDailySales();
        $dailyTotals = ['Foods' => 0, 'Merch' => 0];
        foreach ($daily as $sale) {
            if (isset($dailyTotals[$sale['product_category']])) {
                $dailyTotals[$sale['product_category']] += $sale['total_sales'];
            }
        }

        $weekly = $this->POSModel->getWeeklySales();
        $weeklyTotals = ['Foods' => 0, 'Merch' => 0];
        foreach ($weekly as $sale) {
            if (isset($weeklyTotals[$sale['product_category']])) {
                $weeklyTotals[$sale['product_category']] += $sale['total_sales'];
            }
        }
        $weekStart = date('F j', strtotime('monday this week'));

        $monthly = $this->POSModel->getMonthlySales();
        $monthlyTotals = ['Foods' => 0, 'Merch' => 0];
        foreach ($monthly as $sale) {
            if (isset($monthlyTotals[$sale['product_category']])) {
                $monthlyTotals[$sale['product_category']] += $sale['total_sales'];
            }
        }
        $monthStart = date('F j', strtotime('first day of this month'));

        $yearly = $this->POSModel->getYearlySales();
        $yearlyTotals = ['Foods' => 0, 'Merch' => 0];
        foreach ($yearly as $sale) {
            if (isset($yearlyTotals[$sale['product_category']])) {
                $yearlyTotals[$sale['product_category']] += $sale['total_sales'];
            }
        }
        $yearStart = date('F j', strtotime('first day of January this year'));

        echo $GLOBALS['templates']->render('Sales', [
            'today' => $today,
            'daily' => $daily,
            'dailyTotal' => number_format(array_sum($dailyTotals)),
            'dailyByCategory' => array_map('number_format', $dailyTotals),

            'weekly' => $weekly,
            'weeklyTotal' => number_format(array_sum($weeklyTotals)),
            'weeklyByCategory' => array_map('number_format', $weeklyTotals),
            'weekStart' => $weekStart,

            'monthly' => $monthly,
            'monthlyTotal' => number_format(array_sum($monthlyTotals)),
            'monthlyByCategory' => array_map('number_format', $monthlyTotals),
            'monthStart' => $monthStart,

            'yearly' => $yearly,
            'yearlyTotal' => number_format(array_sum($yearlyTotals)),
            'yearlyByCategory' => array_map('number_format', $yearlyTotals),
            'yearStart' => $yearStart
        ]);
    }

    public function getInventory()
    {
        $inventory = $this->POSModel->getProductInventory();
        echo $GLOBALS['templates']->render('Inventory', ['inventory' => $inventory]);
    }

    public function liveCashierSales()
    {
        $cashierShifts = $this->POSModel->getCashierSales();

        foreach ($cashierShifts as &$shift) {
            // Initialize categories
            $shift['category_totals'] = [
                'Foods' => 0,
                'Merch' => 0
            ];

            if ($shift['status'] === 'open') {
                // Live shift: calculate totals since start_time
                $liveSales = $this->POSModel->getLiveShiftTotalByCategory($shift['user_id']);
            } else {
                // Closed shift: calculate totals between start_time and end_time
                $liveSales = $this->POSModel->getShiftTotalByCategory($shift['user_id'], $shift['start_time'], $shift['end_time']);
            }

            // Populate category totals
            foreach ($liveSales as $sale) {
                $shift['category_totals'][$sale['product_category']] = $sale['total_sales'];
            }

            // Ensure total_sales matches sum of categories
            $shift['total_sales'] = array_sum($shift['category_totals']);
        }
        unset($shift);

        // Calculate dashboard totals
        $dashboardTotals = [
            'Foods' => 0,
            'Merch' => 0,
            'Total' => 0
        ];

        foreach ($cashierShifts as $shift) {
            $dashboardTotals['Foods'] += $shift['category_totals']['Foods'];
            $dashboardTotals['Merch'] += $shift['category_totals']['Merch'];
        }
        $dashboardTotals['Total'] = $dashboardTotals['Foods'] + $dashboardTotals['Merch'];

        // Return JSON
        echo json_encode([
            'cashierShifts' => $cashierShifts,
            'dashboardTotals' => $dashboardTotals
        ]);
    }
}
