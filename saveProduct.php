<?php
header('Content-Type: application/json');
// 1. Defined as $con
require_once 'adminHeader.php'; 
// 3. Collecting Data
$p_id    = !empty($_POST['product_id']) ? intval($_POST['product_id']) : null;
$p_name  = isset($_POST['Product_name']) ? trim($_POST['Product_name']) : '';
$p_price = isset($_POST['Cost_price']) ? floatval($_POST['Cost_price']) : 0.0;
$cat_id  = isset($_POST['Cat_id']) ? intval($_POST['Cat_id']) : 0;

if (empty($p_name)) {
    echo json_encode(["success" => false, "message" => "Product name is required"]);
    exit;
}

// 4. DUPLICATE CHECK
if ($p_id) {
    $check = $conn->prepare("SELECT Product_id FROM product WHERE Product_name = ? AND Product_id != ?");
    $check->bind_param("si", $p_name, $p_id);
} else {
    $check = $conn->prepare("SELECT Product_id FROM product WHERE Product_name = ?");
    $check->bind_param("s", $p_name);
}

$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Alert: Product '$p_name' already exists!"]);
    exit;
}

// 5. SAVE OR UPDATE
if ($p_id) {
    // "sdii" = string, double (for price), integer, integer
    $stmt = $conn->prepare("UPDATE product SET Product_name=?, Cost_price=?, Cat_id=? WHERE Product_id=?");
    $stmt->bind_param("sdii", $p_name, $p_price, $cat_id, $p_id);
    $res_msg = "Product updated successfully!";
} else {
    // "sdi" = string, double, integer
    $stmt = $conn->prepare("INSERT INTO product (Product_name, Cost_price, Cat_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $p_name, $p_price, $cat_id);
    $res_msg = "Product added successfully!";
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => $res_msg]);
} else {
    echo json_encode(["success" => false, "message" => "Database Error: " . $conn->error]);
}

$conn->close();
?>