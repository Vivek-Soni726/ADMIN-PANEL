<?php
header('Content-Type: application/json');

require_once 'adminHeader.php'; 
// Capture Date Inputs
$from = $_GET['from'] ?? '';
$to   = $_GET['to']   ?? '';

// 1=1 is true by default (Overall Data)
$dateCondition = " WHERE 1=1 ";

if (!empty($from) && !empty($to)) {
    $from_esc = $conn->real_escape_string($from);
    $to_esc = $conn->real_escape_string($to);
    
    // Using backticks for `order` table and 'o' alias
    $dateCondition .= " AND o.Order_date BETWEEN '$from_esc' AND '$to_esc' ";
}

// --- 1. Bar Chart Data (Revenue per Shop) ---
// Using LEFT JOIN ensures shops with 0 revenue still appear in the bar chart
$barQuery = "SELECT s.Shop_name, SUM(oi.Item_price * oi.Item_quantity) as revenue 
             FROM shop s 
             LEFT JOIN user u ON s.Shop_id = u.Shop_id 
             LEFT JOIN `order` o ON u.User_id = o.Customer_id 
             LEFT JOIN order_item oi ON o.Order_id = oi.Order_id
             $dateCondition
             GROUP BY s.Shop_id";

$barResult = $conn->query($barQuery);
$barLabels = [];
$barData = [];

if ($barResult) {
    while ($row = $barResult->fetch_assoc()) {
        $barLabels[] = $row['Shop_name'] ?? 'Unknown';
        $barData[] = (float)($row['revenue'] ?? 0);
    }
}

// --- 2. Line Chart Data (Sales Trend) ---
// Default to last 30 days if no date range is picked
$lineDateCondition = !empty($from) ? $dateCondition : " WHERE o.Order_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) ";

$lineQuery = "SELECT o.Order_date as day, SUM(oi.Item_price * oi.Item_quantity) as daily_revenue 
              FROM `order` o 
              JOIN order_item oi ON o.Order_id = oi.Order_id
              $lineDateCondition
              GROUP BY o.Order_date 
              ORDER BY day ASC";

$lineResult = $conn->query($lineQuery);
$lineLabels = [];
$lineData = [];

if ($lineResult && $lineResult->num_rows > 0) {
    while ($row = $lineResult->fetch_assoc()) {
        $lineLabels[] = date("d M", strtotime($row['day'])); 
        $lineData[] = (float)$row['daily_revenue'];
    }
} else {
    $lineLabels = [date("d M")];
    $lineData = [0];
}

echo json_encode([
    "barChart" => [
        "labels" => $barLabels,
        "data" => $barData
    ],
    "lineChart" => [
        "labels" => $lineLabels,
        "data" => $lineData
    ]
]);

$conn->close();
?>