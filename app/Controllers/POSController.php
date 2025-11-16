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

            $product = $this->POSModel->insertProduct($productNumber, $productName, $price, $qty, $category);
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

    public function updateProduct($productId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productNumber = $_POST['productNumber'] ?? '';
            $productName = $_POST['productName'] ?? '';
            $price = $_POST['price'] ?? '';
            $qty = $_POST['qty'] ?? '';
            $category = $_POST['productCat'] ?? '';

            $updated = $this->POSModel->updateProduct($productId, $productNumber, $productName, $price, $qty, $category);
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
}
