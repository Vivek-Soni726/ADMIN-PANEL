<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

if (isset($_POST['user_id'])) {
    $id = intval($_POST['user_id']);
    $name = $_POST['user_name'];
    $address = $_POST['user_address'];
    $contact = $_POST['user_contact'];
    
    // Optional: Capture role and shop if you allow editing them
    $role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : null;
    $shop_id = isset($_POST['shop_id']) ? intval($_POST['shop_id']) : null;

    // --- NEW VALIDATION: Ensure no duplicate manager during update ---
    if ($role_id === 2 && $shop_id !== null) {
        $check = $conn->prepare("SELECT User_name FROM user WHERE Shop_id = ? AND Role_id = 2 AND User_id != ?");
        $check->bind_param("ii", $shop_id, $id);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "Cannot update: This shop already has a different Manager."]);
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE user SET User_name = ?, User_address = ?, User_contact = ? WHERE User_id = ?");
    $stmt->bind_param("sssi", $name, $address, $contact, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update database"]);
    }
    $stmt->close();
}
$conn->close();
?>