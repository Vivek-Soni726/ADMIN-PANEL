<?php
header('Content-Type: application/json');

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'project';

// You defined it as $con here
$con = new mysqli($servername, $username, $password, $dbname);

// FIX: Changed $conn to $con
if ($con->connect_error) {
    echo json_encode([
        "success" => false, 
        "message" => "Database connection failed",
        "debug" => $con->connect_error 
    ]);
    exit; 
}

$sql = "SELECT count(Cat_id) AS total from category"; 
$result = $con->query($sql);

if($result){
    $row = $result->fetch_assoc();
    echo json_encode($row); 
} else {
    echo json_encode(["success" => false, "message" => "Query failed"]);
}

$con->close();
?>