<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

// 3. Collect Data (Ensuring we use the correct types)
$p_id    = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$s_id    = isset($_POST['shop_id']) ? intval($_POST['shop_id']) : 0;
$qty     = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
$s_price = isset($_POST['selling_price']) ? floatval($_POST['selling_price']) : 0.0;
$u_id    = 1; // Replace with actual Session User ID if available

if ($p_id === 0 || $s_id === 0 || $qty === 0) {
    echo json_encode(["success" => false, "message" => "Invalid input data."]);
    exit;
}

// 4. Start Transaction
$conn->begin_transaction();

try {
    // A. Update Shop Inventory
    // FIX: Type definition "iidi" corrected to "iidi" (integer, integer, double/float, integer)
    $inventory_sql = "INSERT INTO shop_inventory (Product_id, Shop_id, Selling_price, Available_stock) 
                      VALUES (?, ?, ?, ?) 
                      ON DUPLICATE KEY UPDATE 
                      Available_stock = Available_stock + VALUES(Available_stock), 
                      Selling_price = VALUES(Selling_price)";
    
    $inv_stmt = $conn->prepare($inventory_sql);
    $inv_stmt->bind_param("iidi", $p_id, $s_id, $s_price, $qty);
    $inv_stmt->execute();

    // B. Record Stock Movement
    $movement_sql = "INSERT INTO stock_movement (Product_id, Movement_quantity, Movement_type, User_id, Movement_date) 
                     VALUES (?, ?, 'IN', ?, NOW())";
    
    $mov_stmt = $conn->prepare($movement_sql);
    $mov_stmt->bind_param("iii", $p_id, $qty, $u_id);
    $mov_stmt->execute();

    // C. Commit the changes
    $conn->commit();
    echo json_encode(["success" => true, "message" => "Stock refilled and movement logged!"]);

} catch (Exception $e) {
    // Rollback if anything goes wrong
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}

$conn->close();
?>