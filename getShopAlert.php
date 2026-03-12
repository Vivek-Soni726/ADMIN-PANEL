<?php
header('Content-Type: application/json');

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'project';

// 1. Created as $con
$con = new mysqli($servername, $username, $password, $dbname);

// 2. FIX: Change $conn to $con so it matches line 9
if ($con->connect_error) {
    echo json_encode([
        "success" => false, 
        "message" => "Database connection failed",
        "debug" => $con->connect_error 
    ]);
    exit;
}

// This query finds how many unique shops have items with stock less than 10
$sql = "SELECT COUNT(DISTINCT Shop_id) AS low_stock_shops_count 
        FROM shop_inventory 
        WHERE Available_stock < 10";

$result = $con->query($sql);

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
        "message" => "Query failed: " . $con->error
    ]);
}

$con->close();
?>