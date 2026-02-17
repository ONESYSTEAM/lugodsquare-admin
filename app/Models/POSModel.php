<?php

namespace app\Models;

use config\DBConnection;
use PDO;

class POSModel
{
    private $db;

    public function __construct(DBConnection $db)
    {
        $this->db = $db->getConnection();
    }

    public function fetchProductCategory()
    {
        $stmt = $this->db->prepare("SELECT product_category, COUNT(*) AS no_of_items FROM products WHERE is_deleted = 0 GROUP BY product_category");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchProductItems($category)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE is_deleted = 0 AND product_category = :category");
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertProduct($productNumber, $productName, $price, $qty, $category)
    {
        $stmt = $this->db->prepare("INSERT INTO products (product_number, product_name, price, qty, product_category) VALUES (:product_number, :product_name, :price, :qty, :category)");
        $stmt->bindParam(':product_number', $productNumber, PDO::PARAM_STR);
        $stmt->bindParam(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function fetchProductById($productId)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProduct($productId, $productNumber, $productName, $price, $qty, $category)
    {
        $stmt = $this->db->prepare("UPDATE products SET product_number = :product_number, product_name = :product_name, price = :price, qty = :qty, product_category = :product_category WHERE id = :id");
        $stmt->bindParam(':product_number', $productNumber, PDO::PARAM_STR);
        $stmt->bindParam(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_STR);
        $stmt->bindParam(':product_category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteProduct($productId, $userId)
    {
        $stmt = $this->db->prepare("UPDATE products SET is_deleted = 1, deleted_by = :userId WHERE id = :id");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getDailySales()
    {
        $stmt = $this->db->prepare("SELECT s.user_id, CONCAT(u.first_name, ' ', u.last_name) AS cashier_name, si.item_name,  SUM(si.qty) AS total_qty,  si.price AS unit_price, 
         SUM(s.sub_total) AS raw_sales,  p.product_number,  p.product_category, SUM(s.discount) AS total_discount, SUM(s.final_total) AS total_sales
        FROM sales_items si
        JOIN sales s ON si.sale_id = s.id
        JOIN products p ON si.item_name = p.product_name
        JOIN users u ON s.user_id = u.id
        WHERE DATE(si.created_at) = CURDATE() 
        GROUP BY s.user_id, u.first_name, u.last_name , si.item_name, si.price");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getWeeklySales()
    {
        $stmt = $this->db->prepare("SELECT s.user_id, CONCAT(u.first_name, ' ', u.last_name) AS cashier_name, si.item_name, SUM(si.qty) AS total_qty, si.price AS unit_price, SUM(s.sub_total) AS raw_sales, p.product_number, p.product_category,
            SUM(s.discount) AS total_discount, SUM(s.final_total) AS total_sales
            FROM sales_items si
            JOIN sales s ON si.sale_id = s.id
            JOIN products p ON si.item_name = p.product_name 
            JOIN users u ON s.user_id = u.id
            WHERE DATE(si.created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AND CURDATE() 
            GROUP BY si.item_name, si.price, u.first_name, u.last_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlySales()
    {
        $stmt = $this->db->prepare("SELECT s.user_id, CONCAT(u.first_name, ' ', u.last_name) AS cashier_name, si.item_name, SUM(si.qty) AS total_qty, si.price AS unit_price, SUM(s.sub_total) AS raw_sales, p.product_number, p.product_category,
            SUM(s.discount) AS total_discount, SUM(s.final_total) AS total_sales
            FROM sales_items si
            JOIN sales s ON si.sale_id = s.id
            JOIN products p ON si.item_name = p.product_name 
            JOIN users u ON s.user_id = u.id
            WHERE MONTH(si.created_at) = MONTH(CURDATE()) AND YEAR(si.created_at) = YEAR(CURDATE())
            GROUP BY si.item_name, si.price, u.first_name, u.last_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getYearlySales()
    {
        $stmt = $this->db->prepare("SELECT s.user_id, CONCAT(u.first_name, ' ', u.last_name) AS cashier_name, si.item_name, SUM(si.qty) AS total_qty, si.price AS unit_price, SUM(s.sub_total) AS raw_sales, p.product_number, p.product_category,
            SUM(s.discount) AS total_discount, SUM(s.final_total) AS total_sales
            FROM sales_items si
            JOIN sales s ON si.sale_id = s.id
            JOIN products p ON si.item_name = p.product_name 
            JOIN users u ON s.user_id = u.id
            WHERE YEAR(si.created_at) = YEAR(CURDATE())
            GROUP BY si.item_name, si.price, u.first_name, u.last_name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductInventory()
    {
        $stmt = $this->db->prepare("SELECT p.*, COALESCE(SUM(si.qty), 0) AS total_qty, p.price AS unit_price, COALESCE(SUM(si.qty * si.price), 0) AS total_sales
            FROM products p LEFT JOIN sales_items si ON si.item_name = p.product_name
            GROUP BY p.id, p.product_name, p.price");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCashierSales()
    {
        $stmt = $this->db->prepare("SELECT cs.*, CONCAT(u.first_name, ' ', u.last_name) AS cashier_name
        FROM cashier_shifts cs JOIN users u ON cs.user_id = u.id
        WHERE DATE(cs.start_time) = CURDATE()
        ORDER BY cs.start_time DESC ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLiveShiftTotalByCategory($user_id)
    {
        $stmt = $this->db->prepare("SELECT p.product_category, COALESCE(SUM(s.final_total),0) AS total_sales FROM sales s
        JOIN sales_items si ON s.id = si.sale_id
        JOIN products p ON si.item_name = p.product_name
        JOIN cashier_shifts cs ON s.user_id = cs.user_id
        WHERE cs.user_id = :user_id AND cs.status = 'open' AND s.created_at BETWEEN cs.start_time AND NOW()
        GROUP BY p.product_category ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getShiftTotalByCategory($user_id, $start_time, $end_time)
    {
        $stmt = $this->db->prepare("SELECT p.product_category, COALESCE(SUM(s.final_total), 0) AS total_sales FROM sales s
        JOIN sales_items si ON s.id = si.sale_id
        JOIN products p ON si.item_name = p.product_name
        WHERE s.user_id = :user_id AND s.created_at BETWEEN :start_time AND :end_time
        GROUP BY p.product_category");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
