<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'project';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Can't connect to server!!!" . $con->connect_error);
}
$sql = 'SELECT sum(product_price)as inventory_value from product';
$result = $con->query($sql);
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
$con->close();
