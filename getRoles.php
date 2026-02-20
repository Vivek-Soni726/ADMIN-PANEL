<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error connecting server" . $conn->connect_error);
}

// Exclude Role_id 1 (Admin) as requested
$sql = "SELECT Role_id, Role_name FROM role WHERE Role_id != 1";
$result = $conn->query($sql);

$roles = [];
while ($row = $result->fetch_assoc()) {
    $roles[] = $row;
}

echo json_encode($roles);
$conn->close();
