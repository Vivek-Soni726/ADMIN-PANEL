<?php
header('Content-Type: application/json');
$con = new mysqli("localhost", "root", "", "project");

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    $stmt = $con->prepare("DELETE FROM product WHERE Product_id = ?");
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Could not delete product."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid Product ID."]);
}

$con->close();
?>