<?php
header('Content-Type: application/json');
$con = new mysqli("localhost", "root", "", "project");

if ($con->connect_error) {
    echo json_encode(["success" => false, "message" => "Database Connection Failed"]);
    exit;
}

$p_id    = !empty($_POST['product_id']) ? intval($_POST['product_id']) : null;
$p_name  = trim($_POST['product_name']);
$p_price = $_POST['product_price'];
$cat_id  = intval($_POST['cat_id']);

// 1. DUPLICATE CHECK
if ($p_id) {
    // Editing: Check if name exists for a DIFFERENT product ID
    $check = $con->prepare("SELECT Product_id FROM product WHERE Product_name = ? AND Product_id != ?");
    $check->bind_param("si", $p_name, $p_id);
} else {
    // Adding: Check if name exists anywhere
    $check = $con->prepare("SELECT Product_id FROM product WHERE Product_name = ?");
    $check->bind_param("s", $p_name);
}

$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Alert: Product '$p_name' already exists!"]);
    exit;
}

// 2. SAVE OR UPDATE
if ($p_id) {
    $stmt = $con->prepare("UPDATE product SET Product_name=?, Product_price=?, Cat_id=? WHERE Product_id=?");
    $stmt->bind_param("sdii", $p_name, $p_price, $cat_id, $p_id);
    $res_msg = "Product updated successfully!";
} else {
    // Note: If your table requires Shop_id, you should add a default or a dropdown for it.
    $stmt = $con->prepare("INSERT INTO product (Product_name, Product_price, Cat_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $p_name, $p_price, $cat_id);
    $res_msg = "Product added successfully!";
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => $res_msg]);
} else {
    echo json_encode(["success" => false, "message" => "Database Error: " . $con->error]);
}

$con->close();
?>