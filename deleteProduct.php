<?php
header('Content-Type: application/json');
// 1. You defined it as $con here
$con = new mysqli("localhost", "root", "", "project");

// 2. FIX: Change $conn to $con
if ($con->connect_error) {
    echo json_encode([
        "success" => false, 
        "message" => "Database connection failed",
        "debug" => $con->connect_error 
    ]);
    exit; 
}

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    $stmt = $con->prepare("DELETE FROM product WHERE Product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Product deleted"]);
    } else {
        echo json_encode(["success" => false, "message" => "Could not delete product."]);
    }
    $stmt->close(); // Good practice to close the statement
} else {
    echo json_encode(["success" => false, "message" => "Invalid Product ID."]);
}
$con->close();
?>