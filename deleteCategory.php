<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed"]);
    exit;
}

$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;

if ($cat_id > 0) {
    // FIX: Use Cat_id to match your product table schema
    $checkStmt = $conn->prepare("SELECT COUNT(*) as product_count FROM product WHERE Cat_id = ?");
    $checkStmt->bind_param("i", $cat_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['product_count'] > 0) {
        echo json_encode([
            "success" => false, 
            "message" => "Cannot delete! This category has " . $row['product_count'] . " products linked to it."
        ]);
    } else {
        // FIX: Use Cat_id to match your category table schema
        $delStmt = $conn->prepare("DELETE FROM category WHERE Cat_id = ?");
        $delStmt->bind_param("i", $cat_id);
        
        if ($delStmt->execute()) {
            echo json_encode(["success" => true, "message" => "Category deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error during deletion."]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid Category ID."]);
}
$conn->close();
?>