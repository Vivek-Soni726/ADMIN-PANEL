<?php
header('Content-Type: application/json');

require_once 'adminHeader.php';

$sql = "SELECT 
    s.Shop_id, 
    s.Shop_name, 
    s.Shop_address, 
    u.User_name, 
    u.User_contact
FROM shop AS s 
LEFT JOIN user AS u ON s.Shop_id = u.Shop_id AND u.Role_id IN (2, 3)
GROUP BY s.Shop_id;";

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