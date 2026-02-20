<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Error connecting server". $conn->connect_error);
}

$sql = "SELECT s.Shop_id, s.Shop_name, s.Shop_address, u.User_name, u.User_contact, u.Role_id
        FROM shop AS s LEFT JOIN user AS u ON s.Shop_id = u.Shop_id
        WHERE u.Role_id = 2 OR u.Role_id IS NULL;";
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