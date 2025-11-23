<?php
header('Content-Type: application/json');

// Allow only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Only POST requests are allowed"]);
    exit;
}

// Get raw POST data
$json = file_get_contents('php://input');

if (!$json) {
    http_response_code(400);
    echo json_encode(["error" => "No JSON received"]);
    exit;
}

// Decode JSON
$data = json_decode($json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid JSON"]);
    exit;
}

// Validate required fields
if (!isset($data['device_id']) || !isset($data['BPM'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing device_id or BPM"]);
    exit;
}

// Extract data
$device_id = $data['device_id'];
$bpm       = $data['BPM'];
$timestamp = date("Y-m-d H:i:s");

// Optional: store data in a CSV file (or use a database)
$file = 'bpm_data.csv';
$entry = [$timestamp, $device_id, $bpm];
$fp = fopen($file, 'a');
fputcsv($fp, $entry);
fclose($fp);

// Respond back to device
http_response_code(200);
echo json_encode([
    "status" => "success",
    "message" => "BPM received",
    "device_id" => $device_id,
    "BPM" => $bpm
]);
?>