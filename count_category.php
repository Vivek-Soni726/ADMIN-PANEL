<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

$sql = "SELECT count(Cat_id) AS total from category"; 
$result = $conn->query($sql);

if($result){
    $row = $result->fetch_assoc();
    echo json_encode($row); 
} else {
    echo json_encode(["success" => false, "message" => "Query failed"]);
}

$conn->close();
?>