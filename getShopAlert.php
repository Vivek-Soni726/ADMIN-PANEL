<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

// This query finds how many unique shops have items with stock less than 10
$sql = "SELECT COUNT(DISTINCT Shop_id) AS low_stock_shops_count 
        FROM shop_inventory 
        WHERE Available_stock < 10";

$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    // Return a single object instead of an array of arrays
    echo json_encode([
        "success" => true,
        "count" => (int)$row['low_stock_shops_count']
    ]);
} else {
    echo json_encode([
        "success" => false, 
        "message" => "Query failed: " . $conn->error
    ]);
}

$conn->close();
?>