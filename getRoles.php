<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

// Exclude Role_id 1 (Admin) as requested
$sql = "SELECT Role_id, Role_name FROM role WHERE Role_id != 1";
$result = $conn->query($sql);

$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

echo json_encode($roles);
$conn->close();
