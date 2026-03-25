<?php
header('Content-Type: application/json');

require_once 'adminHeader.php';

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
    c.Cat_name,
    COUNT(DISTINCT p.Product_id) AS TotalProductsInCategory,
    SUM(
        CASE 
            WHEN m.Movement_type = "IN" THEN m.Movement_quantity
            WHEN m.Movement_type = "OUT" THEN -m.Movement_quantity
            ELSE 0
        END
    ) AS CurrentStock
FROM category AS c
LEFT JOIN product AS p ON c.Cat_id = p.Cat_id
LEFT JOIN stock_movement AS m ON p.Product_id = m.Product_id
GROUP BY c.Cat_id, c.Cat_name';

$stock = [];
$result = $conn->query($stmt);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stock[] = $row;
    }
}

echo json_encode([
    'sales' => $sale,
    'stock' => $stock
]);
$conn->close();
