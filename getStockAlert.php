<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'project';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Can't connect to server!!!" . $con->connect_error);
}
$sql = "SELECT p.Shop_id, COUNT(Product_id) as stock_alert, s.Shop_id, s.Shop_name from product as p LEFT JOIN shop as s ON p.Shop_id=s.Shop_id WHERE Product_quantity < 15 GROUP BY Shop_name";

$data = [];
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
$con->close();
