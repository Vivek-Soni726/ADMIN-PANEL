<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false, 
        "message" => "Database connection failed",
        "debug" => $conn->connect_error // Optional: only for development
    ]);
    exit; // Stop further script execution
}

$sql = "SELECT s.Shop_id, s.Shop_name, s.Shop_address, u.User_name, u.User_contact, u.Role_id
        FROM shop AS s LEFT JOIN user AS u ON s.Shop_id = u.Shop_id
        WHERE u.Role_id = 2 OR u.Role_id IS NULL";

$result = $conn->query($sql);
$data = [];

if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
}

// Always echo the array. If empty, JS gets [] which is valid JSON.
echo json_encode($data);
$conn->close();
?>