<?php
header('Content-Type: application/json');

// ✅ Example static JSON output for testing
// You can change "ON" or "OFF" to test your ESP32 response

$data = [
    [
        "switchId" => "SW-2025907824",
        "state" => "OFF"   // Change to "OFF" to test relay off
    ]
];

echo json_encode($data, JSON_PRETTY_PRINT);
?>