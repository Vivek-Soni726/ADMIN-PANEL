<?php
header('Content-Type: application/json');

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'project';

$conn = new mysqli($servername, $username, $password, $dbname);

// Error handling - formatted as JSON so JS doesn't crash
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed"]);
    exit;
}

/**
 * SQL EXPLANATION:
 * 1. We JOIN 'product' (p) and 'shop_inventory' (i) on Product_id.
 * 2. We multiply the individual cost by the stock in each shop.
 * 3. SUM() gives us the grand total for the whole business.
 */
$sql = "SELECT SUM(p.Cost_price * i.Available_stock) as overall_value 
        FROM product p 
        JOIN shop_inventory i ON p.Product_id = i.Product_id"; 

$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    
    // Fallback to 0 if no inventory exists
    $totalValue = $row['overall_value'] ?? 0;
    
    echo json_encode([
        "success" => true,
        "inventory_value" => (float)$totalValue,
        "formatted_value" => "₹" . number_format($totalValue, 2)
    ]); 
} else {
    echo json_encode(["success" => false, "message" => "Query Error: " . $conn->error]);
}

$conn->close();
?>