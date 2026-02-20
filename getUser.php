<?php
header('Content-Type: application/json');
$servername = 'localhost';
$username   = 'root';
$password   = '';
$dbname     = 'project';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Can't connect to server!! " . $con->connect_error);
}

$shop_id = $_GET['shop_id'] ?? null;

if ($shop_id) {
    $stmt = $con->prepare("SELECT 
                                u.User_id, 
                                u.User_name, 
                                u.User_contact, 
                                u.User_address, 
                                u.Shop_id, 
                                s.Shop_id AS ShopId, 
                                s.Shop_name
                           FROM user AS u 
                           LEFT JOIN shop AS s 
                           ON u.Shop_id = s.Shop_id
                           WHERE u.Shop_id = ?");
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $con->query("SELECT 
                                u.User_id, 
                                u.User_name, 
                                u.User_contact, 
                                u.User_address, 
                                u.Shop_id, 
                                s.Shop_id AS ShopId, 
                                s.Shop_name
                           FROM user AS u 
                           LEFT JOIN shop AS s 
                           ON u.Shop_id = s.Shop_id");
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

$con->close();
?>