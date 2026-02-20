<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error connecting server" . $conn->connect_error);
}

$sql = 'SELECT 
                p.Product_id,
                p.Product_name,
                COUNT(m.Movement_type) AS MovementCount,
                SUM(m.Movement_quantity) AS TotalQuantityMoved
            FROM stock_movement AS m
            LEFT JOIN product AS p ON m.Product_id = p.Product_id
            WHERE m.Movement_type = "OUT"
            GROUP BY p.Product_id, p.Product_name';

$sale = [];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sale[] = $row;
    }
}

$stmt = 'SELECT 
    p.Product_id,
    p.Product_name,
    SUM(
        CASE 
            WHEN m.Movement_type = "IN" THEN m.Movement_quantity
            WHEN m.Movement_type = "OUT" THEN -m.Movement_quantity
            ELSE 0
        END
    ) AS CurrentStock
FROM product AS p
LEFT JOIN stock_movement AS m ON p.Product_id = m.Product_id
GROUP BY p.Product_id, p.Product_name';

$stock = [];
$result = $conn->query($stmt);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stock[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode([
    'sales'=> $sale,
    'stock'=> $stock]);
$conn->close();
