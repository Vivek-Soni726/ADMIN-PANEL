<?php
header('Content-Type: application/json');

$servername = 'localhost';
$username   = 'root';
$password   = '';
$dbname     = 'project';

// 1. Defined as $con
$con = new mysqli($servername, $username, $password, $dbname);

// 2. FIX: Use $con to match line 8
if ($con->connect_error) {
    echo json_encode([
        "success" => false, 
        "message" => "Database connection failed",
        "debug"   => $con->connect_error 
    ]);
    exit;
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
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$con->close();
?>