<?php
header('Content-Type: application/json');
date_default_timezone_set('Asia/Manila');

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON received."]);
    exit;
}

// Extract fields
$medicine = $data["medicine_name"] ?? "";
$dispenseTime = $data["dispenseTime"] ?? "";
$day = $data["day"] ?? "";
$taken = $data["taken"] ?? false;

// Example: Log to file (you can replace with DB or email)
$logEntry = sprintf("[%s] %s - %s at %s - Taken: %s\n", 
    date('Y-m-d H:i:s'),
    $day,
    $medicine,
    $dispenseTime,
    $taken ? "YES" : "NO"
);

file_put_contents("notifications.log", $logEntry, FILE_APPEND);

// ✅ Optional: Send email notification
/*
mail("your_email@example.com", 
    "Medicine Taken - $medicine", 
    "The patient has taken $medicine on $day at $dispenseTime.");
*/

// Response back to ESP32
echo json_encode([
    "success" => true,
    "message" => "Notification logged successfully",
    "received_data" => $data
]);
?>