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

    // Add your custom methods below to interact with the database.
    public function fetchAllProducts()
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE is_deleted = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertProduct($productNumber, $productName, $price, $qty)
    {
        $stmt = $this->db->prepare("INSERT INTO products (product_number, product_name, price, qty) VALUES (:product_number, :product_name, :price)");
        $stmt->bindParam(':product_number', $productNumber, PDO::PARAM_STR);
        $stmt->bindParam(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function fetchProductById($productId)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProduct($productId, $productNumber, $productName, $price, $qty)
    {
        $stmt = $this->db->prepare("UPDATE products SET product_number = :product_number, product_name = :product_name, price = :price, qty = :qty WHERE id = :id");
        $stmt->bindParam(':product_number', $productNumber, PDO::PARAM_STR);
        $stmt->bindParam(':product_name', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_STR);
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
        $stmt = $this->db->prepare("SELECT si.item_name, SUM(si.qty) AS total_qty, si.price AS unit_price, SUM(s.sub_total) AS raw_sales, p.product_number,
            SUM(s.discount) AS total_discount, SUM(s.final_total) AS total_sales
            FROM sales_items si
            JOIN sales s ON si.sale_id = s.id
            JOIN products p ON si.item_name = p.product_name 
            WHERE DATE(si.created_at) = CURDATE() GROUP BY si.item_name, si.price");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeeklySales()
    {
        $stmt = $this->db->prepare("SELECT si.item_name, SUM(si.qty) AS total_qty, si.price AS unit_price, SUM(s.sub_total) AS raw_sales, p.product_number,
            SUM(s.discount) AS total_discount, SUM(s.final_total) AS total_sales
            FROM sales_items si
            JOIN sales s ON si.sale_id = s.id
            JOIN products p ON si.item_name = p.product_name 
            WHERE DATE(si.created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AND CURDATE() 
            GROUP BY si.item_name, si.price");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlySales()
    {
        $stmt = $this->db->prepare("SELECT si.item_name, SUM(si.qty) AS total_qty, si.price AS unit_price, SUM(s.sub_total) AS raw_sales, p.product_number,
            SUM(s.discount) AS total_discount, SUM(s.final_total) AS total_sales
            FROM sales_items si
            JOIN sales s ON si.sale_id = s.id
            JOIN products p ON si.item_name = p.product_name 
            WHERE MONTH(si.created_at) = MONTH(CURDATE()) AND YEAR(si.created_at) = YEAR(CURDATE())
            GROUP BY si.item_name, si.price");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getYearlySales()
    {
        $stmt = $this->db->prepare("SELECT si.item_name, SUM(si.qty) AS total_qty, si.price AS unit_price, SUM(s.sub_total) AS raw_sales, p.product_number,
            SUM(s.discount) AS total_discount, SUM(s.final_total) AS total_sales
            FROM sales_items si
            JOIN sales s ON si.sale_id = s.id
            JOIN products p ON si.item_name = p.product_name 
            WHERE YEAR(si.created_at) = YEAR(CURDATE())
            GROUP BY si.item_name, si.price");
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
}
