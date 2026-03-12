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