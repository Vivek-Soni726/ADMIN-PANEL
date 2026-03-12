<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    echo json_encode([
        "success" => false, 
        "message" => "Database connection failed",
        "debug" => $conn->connect_error 
    ]);
    exit;
}

// Collect Name and optional Description
$cat_name = isset($_POST['cat_name']) ? trim($_POST['cat_name']) : '';
$cat_desc = isset($_POST['cat_description']) ? trim($_POST['cat_description']) : ''; // New
$cat_id   = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : null;

if (empty($cat_name)) {
    echo json_encode(["success" => false, "message" => "Category name is required."]);
    exit;
}

// Check for duplicates (excluding current ID if updating)
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

// Insert or Update logic
if ($cat_id) {
    // Including Cat_description in the update
    $stmt = $conn->prepare("UPDATE category SET Cat_name = ?, Cat_description = ? WHERE Cat_id = ?");
    $stmt->bind_param("ssi", $cat_name, $cat_desc, $cat_id);
    $action = "updated";
} else {
    // Including Cat_description in the insert
    $stmt = $conn->prepare("INSERT INTO category (Cat_name, Cat_description) VALUES (?, ?)");
    $stmt->bind_param("ss", $cat_name, $cat_desc);
    $action = "added";
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Category $action successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}

$conn->close();
?>