<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_name'])) {
    
    // 1. Data Collection & Trimming
    $name     = trim($_POST['user_name']);
    $address  = trim($_POST['user_address']);
    $contact  = trim($_POST['user_contact']);
    $role_id  = intval($_POST['role_id']);
    $shop_id  = intval($_POST['shop_id']);
    $email    = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    // 2. One Manager Per Shop Validation
    if ($role_id === 2) {
        $check = $conn->prepare("SELECT User_name FROM user WHERE Shop_id = ? AND Role_id = 2 LIMIT 1");
        $check->bind_param("i", $shop_id);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows > 0) {
            $existing = $res->fetch_assoc();
            echo json_encode([
                "success" => false, 
                "message" => "This shop already has a Manager: " . $existing['User_name']
            ]);
            $check->close();
            exit;
        }
        $check->close();
    }

    // 3. Security check (Don't let this script create Admins)
    if ($role_id === 1) {
        echo json_encode(["success" => false, "message" => "Unauthorized role assignment."]);
        exit;
    }

    // 4. Secure Password Hashing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // 5. Transaction Logic
    $conn->begin_transaction();

    try {
        // STEP A: Insert into login table
        $stmt_login = $conn->prepare("INSERT INTO login (Login_name, Login_email, Login_contact, Login_password, Role_id, Reg_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt_login->bind_param("ssssi", $name, $email, $contact, $hashed_password, $role_id);
        $stmt_login->execute();
        
        $master_id = $conn->insert_id; 

        // STEP B: Insert into user table
        $stmt_user = $conn->prepare("INSERT INTO user (User_id, User_name, User_address, User_contact, Role_id, Shop_id, Login_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_user->bind_param("isssiii", $master_id, $name, $address, $contact, $role_id, $shop_id, $master_id);
        $stmt_user->execute();

        $conn->commit();
        echo json_encode(["success" => true, "assigned_id" => $master_id]);

        $stmt_login->close();
        $stmt_user->close();

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Database Error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid Request Method"]);
}

// $conn is closed automatically at end of script, or you can use $conn->close() if preferred.
?>