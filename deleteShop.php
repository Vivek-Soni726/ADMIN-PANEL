<?php
header('Content-Type: application/json');

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

if (isset($_GET['shop_id'])) {
    $shop_id = intval($_GET['shop_id']);

    // 1. Check if any users are associated with this shop
    $checkUser = $conn->prepare("SELECT COUNT(*) as user_count FROM user WHERE Shop_id = ?");
    $checkUser->bind_param("i", $shop_id);
    $checkUser->execute();
    $result = $checkUser->get_result();
    $row = $result->fetch_assoc();

    if ($row['user_count'] > 0) {
        // There are users associated with this shop
        echo json_encode([
            "success" => false, 
            "message" => "Cannot delete: This shop still has " . $row['user_count'] . " users assigned to it."
        ]);
    } else {
        // 2. No users found, proceed to delete the shop
        $deleteStmt = $conn->prepare("DELETE FROM shop WHERE Shop_id = ?");
        $deleteStmt->bind_param("i", $shop_id);

        if ($deleteStmt->execute()) {
            if ($deleteStmt->affected_rows > 0) {
                echo json_encode(["success" => true, "message" => "Shop deleted successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => "Shop not found"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to execute delete query"]);
        }
        $deleteStmt->close();
    }
    $checkUser->close();
} else {
    echo json_encode(["success" => false, "message" => "No shop_id provided"]);
}

$conn->close();
?>