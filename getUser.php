<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

$shop_id = $_GET['shop_id'] ?? null;

if ($shop_id) {
    $stmt = $conn->prepare("SELECT 
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
                           WHERE u.Shop_id = ?
                           AND (u.Role_id = 2 OR u.Role_id = 3)");
    $stmt->bind_param("i", $shop_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT 
                                u.User_id, 
                                u.User_name, 
                                u.User_contact, 
                                u.User_address, 
                                u.Shop_id, 
                                s.Shop_id AS ShopId, 
                                s.Shop_name
                           FROM user AS u 
                           LEFT JOIN shop AS s ON u.Shop_id = s.Shop_id
                           WHERE u.Role_id IN (2, 3)");
}

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();
?>