<?php
// saveReport.php
header('Content-Type: application/json');
require_once 'adminHeader.php'; 
// 1. Get data from JavaScript
$type = $_POST['type'] ?? 'General Report'; 
$filename = $_POST['filename'] ?? 'report.csv';

// 2. Hardcoded conditions: Must be 1 and 1
$shop_id = 1; 
$user_id = 1; 
$date = date('Y-m-d');

// 3. Insert into the 'report' table
$stmt = $conn->prepare("INSERT INTO report (Report_type, Shop_id, User_id, Report_date, Report_file) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("siiss", $type, $shop_id, $user_id, $date, $filename);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Logged to database"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$stmt->close();
$conn->close();
?>