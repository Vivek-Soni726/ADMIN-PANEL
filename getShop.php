<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 
$sql = "SELECT * FROM shop";
$data =[];

$result = $conn->query($sql);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
}
else{
    echo json_encode(["error"=> "No data found!!"]);
    exit;
}

echo json_encode($data);
$conn->close();
?>