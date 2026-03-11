<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

if (!isset($_GET['user_id'])) {
    echo json_encode(["success" => false, "message" => "No user_id provided"]);
    exit;
}

$user_id = intval($_GET['user_id']);

// Start transaction to ensure both tables are updated together
$conn->begin_transaction();

try {
    // 1. Delete from the user table
    $stmt1 = $conn->prepare("DELETE FROM user WHERE User_id = ?");
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();

    // 2. Delete from the login table
    $stmt2 = $conn->prepare("DELETE FROM login WHERE Login_id = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();

    // Check if anything was actually deleted
    if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0) {
        $conn->commit();
        echo json_encode(["success" => true, "message" => "User and credentials deleted successfully"]);
    } else {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "User not found"]);
    }

} catch (Exception $e) {
    // If any query fails, undo everything
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "System Error: " . $e->getMessage()]);
}

$conn->close();
?>