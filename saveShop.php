<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "project");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed"]);
    exit;
}

// Use isset to prevent "Undefined Index" errors
$name = isset($_POST['shop_name']) ? $_POST['shop_name'] : null;
$address = isset($_POST['shop_address']) ? $_POST['shop_address'] : null;
$shop_id = (!empty($_POST['shop_id'])) ? intval($_POST['shop_id']) : null;

if (!$name || !$address) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

if ($shop_id) {
    $stmt = $conn->prepare("UPDATE shop SET Shop_name = ?, Shop_address = ? WHERE Shop_id = ?");
    $stmt->bind_param("ssi", $name, $address, $shop_id);
} else {
    $stmt = $conn->prepare("INSERT INTO shop (Shop_name, Shop_address) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $address);
}

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>