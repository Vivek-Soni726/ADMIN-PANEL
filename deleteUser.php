<?php
// deleteUser.php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username   = "root";        // adjust if needed
$password   = "";            // adjust if needed
$dbname     = "project";  // your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

// Validate input
if (!isset($_GET['user_id'])) {
    echo json_encode(["success" => false, "message" => "No user_id provided"]);
    exit;
}

$user_id = intval($_GET['user_id']); // sanitize

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM user WHERE User_id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "User deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete user"]);
}

$stmt->close();
$conn->close();
?>