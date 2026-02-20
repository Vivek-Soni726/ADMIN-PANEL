<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'project';

$con = new mysqli($servername, $username, $password, $dbname);

if($con->connect_error){
    die("Can't connect to server!!". $con->connect_error);
}

$sql = "SELECT count(Cat_id) AS category_count from category";
$data = [];
$result = $con->query($sql);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
$con->close();
?>