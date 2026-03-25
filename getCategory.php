<?php
// Set header first to ensure the browser expects JSON
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

// Cleaned up the SQL to avoid duplicate Cat_id columns
$sql = "SELECT 
            c.Cat_id, 
            c.Cat_name, 
            COUNT(p.Product_id) AS no_of_product
        FROM category AS c
        LEFT JOIN product AS p ON c.Cat_id = p.Cat_id
        GROUP BY c.Cat_id, c.Cat_name
        ORDER BY no_of_product DESC";

$result = $conn->query($sql);
$data = [];

if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
} else {
    // Return an empty array instead of an error object. 
    // This allows your JS .forEach() to simply do nothing instead of crashing.
    echo json_encode([]); 
    exit;
}

echo json_encode($data);
$conn->close();
?>