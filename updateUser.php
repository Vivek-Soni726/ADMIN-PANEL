<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed"]);
    exit;
}

if (isset($_POST['user_id'])) {
    $id = intval($_POST['user_id']);
    $name = $_POST['user_name'];
    $address = $_POST['user_address'];
    $contact = $_POST['user_contact'];

    // Using prepared statements for security
    $stmt = $conn->prepare("UPDATE user SET User_name = ?, User_address = ?, User_contact = ? WHERE User_id = ?");
    $stmt->bind_param("sssi", $name, $address, $contact, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update database"]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid data provided"]);
}
$conn->close();
?>