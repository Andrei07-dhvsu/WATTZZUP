<?php
header('Content-Type: application/json');

// Path to store latest readings
$logFile = 'device_readings.json';

// Read raw POST data
$rawData = file_get_contents("php://input");
if (!$rawData) {
    echo json_encode(["status" => "error", "message" => "No data received"]);
    exit;
}

// Decode JSON
$data = json_decode($rawData, true);
if (!$data || !isset($data['device_id']) || !isset($data['BPM'])) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON format"]);
    exit;
}

// Load existing readings if file exists
$readings = [];
if (file_exists($logFile)) {
    $json = file_get_contents($logFile);
    $readings = json_decode($json, true);
    if (!is_array($readings)) $readings = [];
}

// Update reading for this device
$deviceId = $data['device_id'];
$readings[$deviceId] = [
    "BPM" => $data['BPM'],
    "timestamp" => date("Y-m-d H:i:s")
];

// Save updated readings back to file
file_put_contents($logFile, json_encode($readings, JSON_PRETTY_PRINT));

// Return success
echo json_encode([
    "status" => "success",
    "message" => "Data received",
    "device_id" => $deviceId,
    "BPM" => $data['BPM']
]);
?>