<?php
header('Content-Type: application/json');

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'project';

// 1. Defined as $con
$con = new mysqli($servername, $username, $password, $dbname);

// 2. FIX: Changed $conn to $con to match line 9
if ($con->connect_error) {
    echo json_encode([
        "success" => false, 
        "message" => "Database connection failed",
        "debug" => $con->connect_error 
    ]);
    exit; 
}

$sql = "SELECT count(Product_id) as product_count from product";
$result = $con->query($sql);

if($result) {
    $row = $result->fetch_assoc();
    // 3. Return a single object instead of an array of arrays
    echo json_encode($row); 
} else {
    echo json_encode(["success" => false, "message" => "Query failed"]);
}

$con->close();
?>