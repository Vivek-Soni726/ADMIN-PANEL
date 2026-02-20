<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Error connecting server". $conn->connect_error);
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

header('Content-Type: application/json');
echo json_encode($data);
$conn->close();
?>