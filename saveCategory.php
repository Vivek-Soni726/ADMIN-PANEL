<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$cat_name = trim($_POST['cat_name']);
$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : null;

// FIX: Use Cat_name and Cat_id (matches your schema)
if ($cat_id) {
    $check = $conn->prepare("SELECT * FROM category WHERE Cat_name = ? AND Cat_id != ?");
    $check->bind_param("si", $cat_name, $cat_id);
} else {
    $check = $conn->prepare("SELECT * FROM category WHERE Cat_name = ?");
    $check->bind_param("s", $cat_name);
}

$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Alert: A category with this name already exists!"]);
    exit;
}

if ($cat_id) {
    $stmt = $conn->prepare("UPDATE category SET Cat_name = ? WHERE Cat_id = ?");
    $stmt->bind_param("si", $cat_name, $cat_id);
    $action = "updated";
} else {
    $stmt = $conn->prepare("INSERT INTO category (Cat_name) VALUES (?)");
    $stmt->bind_param("s", $cat_name);
    $action = "added";
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Category $action successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}
?>