<?php
$servername = 'localhost';
$username   = 'root';
$password   = '';
$dbname     = 'project';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Can't connect to server!! " . $con->connect_error);
}

$cat_id = $_GET['Cat_id'] ?? null;

if ($cat_id) {
    $stmt = $con->prepare("SELECT 
                                p.Product_id,
                                p.Shop_id,
                                p.Product_name,
                                p.Product_price,
                                p.Cat_id,
                                c.Cat_id AS CatId,
                                c.Cat_name,
                                s.Shop_name
                            FROM product AS p
                            LEFT JOIN category AS c ON p.Cat_id = c.Cat_id
                            LEFT JOIN shop AS s ON p.Shop_id = s.Shop_id
                            WHERE p.Cat_id = ?");

    $stmt->bind_param("i", $cat_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $con->query("SELECT 
                                p.Product_id,
                                p.Shop_id,
                                p.Product_name,
                                p.Product_price,
                                p.Cat_id,
                                c.Cat_id AS CatId,
                                c.Cat_name,
                                s.Shop_name
                            FROM product AS p
                            LEFT JOIN category AS c ON p.Cat_id = c.Cat_id
                            LEFT JOIN shop AS s ON p.Shop_id = s.Shop_id;");
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

$con->close();
?>