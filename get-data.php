<?php
header("Content-Type: application/json; charset=UTF-8");

// DB config
$host = "localhost";
$dbname = "smart_energy_management";
$username = "root";
$password = "";

// Remote ESP32 proxy
$proxyServerUrl = "https://enersense.space/heartbeat-data.php";

$response = @file_get_contents($proxyServerUrl);

if ($response === false) {
    echo json_encode([
        "status" => "error",
        "message" => "Cannot fetch data from proxy URL"
    ]);
    exit;
}

$data = json_decode($response, true);

if (!isset($data['data']) || !is_array($data['data'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON structure"
    ]);
    exit;
}

// PDO connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO heartbeat_log (device_id, heartbeat, recorded_at) VALUES (:device_id, :heartbeat, :recorded_at)");

    $inserted = 0;

    foreach ($data['data'] as $device_id => $info) {
        if (!isset($info['BPM'])) continue;

        $bpm = $info['BPM'];
        $ts  = $info['timestamp'] ?? date("Y-m-d H:i:s");

        $stmt->execute([
            ":device_id" => $device_id,
            ":heartbeat" => $bpm,
            ":recorded_at" => $ts
        ]);

        $inserted++;
    }

    echo json_encode([
        "status" => "success",
        "message" => "$inserted record(s) inserted",
        "data" => $data['data'] // optional, to see live BPM
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "db_error",
        "message" => $e->getMessage()
    ]);
}