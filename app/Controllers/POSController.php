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

    // Add your custom controllers below to handle business logic.
    public function getProducts()
    {
        $products = $this->POSModel->fetchAllProducts();
        echo $GLOBALS['templates']->render('Products', ['products' => $products]);
    }

    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productNumber = $_POST['productNumber'] ?? '';
            $productName = $_POST['productName'] ?? '';
            $price = $_POST['price'] ?? '';
            $qty = $_POST['qty'] ?? '';

            $product = $this->POSModel->insertProduct($productNumber, $productName, $price, $qty);
            if ($product) {
                $_SESSION['success'][] = 'Product Added successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to add product. Please try again.';
            }
            header('Location: /products');
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

            $updated = $this->POSModel->updateProduct($productId, $productNumber, $productName, $price, $qty);
            if ($updated) {
                $_SESSION['success'][] = 'Product updated successfully.';
            } else {
                $_SESSION['danger'][] = 'Failed to update product. Please try again.';
            }
            header('Location: /products');
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
        $today = date('j, Y'); // today

        $daily = $this->POSModel->getDailySales();
        $dailyTotalSales = array_sum(array_column($daily, 'total_sales'));

        $weekly = $this->POSModel->getWeeklySales();
        $weeklyTotalSales = array_sum(array_column($weekly, 'total_sales'));
        $weekStart = date('F j', strtotime('monday this week'));

        $monthly = $this->POSModel->getMonthlySales();
        $monthlyTotalSales = array_sum(array_column($monthly, 'total_sales'));
        $monthStart = date('F j', strtotime('first day of this month'));

        $yearly = $this->POSModel->getYearlySales();
        $yearlyTotalSales = array_sum(array_column($yearly, 'total_sales'));
        $yearStart = date('F j', strtotime('first day of January this year'));

        echo $GLOBALS['templates']->render('Sales', [
            'today' => $today,
            'daily' => $daily, 'dailyTotal' => number_format($dailyTotalSales),
            'weekly' => $weekly, 'weeklyTotal' => number_format($weeklyTotalSales), 'weekStart' => $weekStart,
            'monthly' => $monthly, 'monthlyTotal' => number_format($monthlyTotalSales), 'monthStart' => $monthStart,           
            'yearly' => $yearly, 'yearlyTotal' => number_format($yearlyTotalSales), 'yearStart' => $yearStart      
        ]);
    }

    public function getInventory()
    {
        $inventory = $this->POSModel->getProductInventory();
        echo $GLOBALS['templates']->render('Inventory', ['inventory' => $inventory]);
    }
}
