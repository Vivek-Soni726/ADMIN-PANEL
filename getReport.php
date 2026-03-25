<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 

// Capture Date Inputs
$from = $_GET['from'] ?? '';
$to   = $_GET['to']   ?? '';

// 1=1 is always true, so it pulls overall data by default
$dateCondition = " WHERE 1=1 ";

if (!empty($from) && !empty($to)) {
    $from_esc = $conn->real_escape_string($from);
    $to_esc = $conn->real_escape_string($to);
    
    // If dates are provided, we append the specific filter
    $dateCondition .= " AND Order_date BETWEEN '$from_esc' AND '$to_esc' ";
}

// 1. Calculate Net Profit (Overall if no dates, Filtered if dates exist)
$profitQuery = "SELECT SUM(Total_amount) as net_profit FROM `Order` $dateCondition";
$profitResult = $conn->query($profitQuery);
$profit = $profitResult->fetch_assoc()['net_profit'] ?? 0;

// 2. Count Total Shops
$shopQuery = "SELECT COUNT(*) as total_shops FROM `Shop`";
$shopResult = $conn->query($shopQuery);
$shops = $shopResult->fetch_assoc()['total_shops'] ?? 0;

// 3. Count Total Users
$userQuery = "SELECT COUNT(*) as total_users FROM `user`";
$userResult = $conn->query($userQuery);
$users = $userResult->fetch_assoc()['total_users'] ?? 0;

echo json_encode([
    "net_profit" => "₹" . number_format($profit),
    "active_shops" => $shops,
    "active_users" => number_format($users)
]);

$conn->close();
?>