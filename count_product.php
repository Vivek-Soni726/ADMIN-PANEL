<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

$sql = "SELECT count(Product_id) as product_count from product";
$result = $conn->query($sql);

if($result) {
    $row = $result->fetch_assoc();
    // 3. Return a single object instead of an array of arrays
    echo json_encode($row); 
} else {
    echo json_encode(["success" => false, "message" => "Query failed"]);
}

$conn->close();
?>