<?php
header('Content-Type: application/json');

$servername = 'localhost';
$username   = 'root';
$password   = '';
$dbname     = 'project';

// 1. Defined as $con
$con = new mysqli($servername, $username, $password, $dbname);

// 2. FIX: Changed $conn to $con
if ($con->connect_error) {
    echo json_encode([
        "success" => false, 
        "message" => "Database connection failed",
        "debug" => $con->connect_error 
    ]);
    exit;
}

$cat_id = $_GET['Cat_id'] ?? null;

/**
 * FIX: Added SUM(i.Available_stock) and JOINed shop_inventory.
 * This allows the Admin to see total stock levels across all shops.
 */
$baseQuery = "SELECT 
                p.Product_id,
                p.Product_name,
                p.Cost_price,
                p.Cat_id,
                c.Cat_name,
                SUM(i.Available_stock) AS total_stock
              FROM product AS p
              LEFT JOIN category AS c ON p.Cat_id = c.Cat_id
              LEFT JOIN shop_inventory AS i ON p.Product_id = i.Product_id";

if ($cat_id) {
    $sql = $baseQuery . " WHERE p.Cat_id = ? GROUP BY p.Product_id";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $cat_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = $baseQuery . " GROUP BY p.Product_id";
    $result = $con->query($sql);
}

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Data cleaning: if stock is NULL, make it 0
        $row['total_stock'] = $row['total_stock'] ?? 0;
        $data[] = $row;
    }
}

echo json_encode($data);

$con->close();
?>