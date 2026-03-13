<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$from = $_GET['from'] ?? '';
$to   = $_GET['to']   ?? '';
$dateCondition = " WHERE 1=1 ";

if (!empty($from) && !empty($to)) {
    $from_esc = $conn->real_escape_string($from);
    $to_esc = $conn->real_escape_string($to);
    $dateCondition .= " AND o.Order_date BETWEEN '$from_esc' AND '$to_esc' ";
}

// 1. Product Inventory Table
// Bridges product -> shop_inventory -> order_item -> order
$pQuery = "SELECT 
            p.Product_name, 
            SUM(oi.Item_quantity) as qty, 
            SUM(oi.Item_price * oi.Item_quantity) as rev, 
            s.Shop_name 
          FROM product p 
          JOIN shop_inventory si ON p.Product_id = si.Product_id 
          JOIN shop s ON si.Shop_id = s.Shop_id 
          LEFT JOIN order_item oi ON p.Product_id = oi.Product_id 
          LEFT JOIN `order` o ON oi.Order_id = o.Order_id 
          $dateCondition 
          GROUP BY p.Product_id, s.Shop_id";

$pRes = $conn->query($pQuery);
$inventory = []; 
while($r = $pRes->fetch_assoc()) {
    $inventory[] = [
        "Product_name" => $r['Product_name'],
        "qty" => $r['qty'] ?? 0,
        "rev" => $r['rev'] ?? 0,
        "Shop_name" => $r['Shop_name']
    ];
}

// 2. Shop Performance Table
// Bridges shop -> user -> order (via Customer_id) -> order_item
// NOTE: u.User_contact and u.role_id = 2 match your schema
$sQuery = "SELECT 
            s.Shop_name, 
            u.User_name AS Owner_name, 
            u.User_contact, 
            SUM(oi.Item_price * oi.Item_quantity) as rev
          FROM shop s
          JOIN user u ON s.Shop_id = u.Shop_id
          JOIN `order` o ON u.User_id = o.Customer_id 
          JOIN order_item oi ON o.Order_id = oi.Order_id
          $dateCondition AND u.Role_id = 2 
          GROUP BY s.Shop_id";

$sRes = $conn->query($sQuery);
$directory = []; 
while($r = $sRes->fetch_assoc()) {
    $directory[] = $r;
}

echo json_encode([
    "inventory" => $inventory, 
    "directory" => $directory
]);

$conn->close();
?>