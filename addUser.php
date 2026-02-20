<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_name'])) {
    
    $name    = $_POST['user_name'];
    $address = $_POST['user_address'];
    $contact = $_POST['user_contact'];
    $role_id = intval($_POST['role_id']);
    $shop_id = intval($_POST['shop_id']);

    // Great security check!
    if ($role_id === 1) {
        echo json_encode(["success" => false, "message" => "System Admin role cannot be assigned here."]);
        exit;
    }

    // Corrected bind_param: 3 strings, 2 integers
    $stmt = $conn->prepare("INSERT INTO user (User_name, User_address, User_contact, Role_id, Shop_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $name, $address, $contact, $role_id, $shop_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        // Use $stmt->error instead of $conn->error for better detail on prepared statements
        echo json_encode(["success" => false, "message" => "SQL Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid Request"]);
}

$conn->close();
?>