<?php
// Directory to store the latest switch data
$dataDir = 'switch_data/';
$timeoutDuration = 60; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ensure the data directory exists
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0777, true);
    }

    // Receive JSON from ESP
    $data = file_get_contents('php://input');
    $switchData = json_decode($data, true);

    // Must include switchId
    if (isset($switchData['switchId'])) {

        $deviceId = $switchData['switchId'];
        $dataFile = $dataDir . $deviceId . '.json';

        // Add timestamp
        $switchData['timestamp'] = time();

        // Detect load state from the current reading
        if (isset($switchData['current'])) {
            if ($switchData['current'] > 0) {
                $switchData['deviceLoadStatus'] = "CONNECTED"; // may load
            } else {
                $switchData['deviceLoadStatus'] = "NONE"; // walang load
            }
        }

        // Save JSON file per device
        file_put_contents($dataFile, json_encode($switchData, JSON_PRETTY_PRINT));

        echo 'switch Data received';
    } else {
        echo 'switchId missing';
    }

} else {

    // Serve latest data for ALL devices
    $allData = [];
    foreach (glob($dataDir . '*.json') as $filename) {

        $deviceData = json_decode(file_get_contents($filename), true);

        $currentTime = time();
        $dataAge = $currentTime - ($deviceData['timestamp'] ?? 0);

        if ($dataAge <= $timeoutDuration) {

            // Device online
            $allData[] = $deviceData;

        } else {

            // Device offline -> return default values
            $allData[] = [
                'wifi_status' => 'OFFLINE',
                'switchId' => basename($filename, ".json"),
                'state' => 'OFF',
                'voltage' => 0,
                'current' => 0,
                'power' => 0,
                'energyWh' => 0,
                'energyKWh' => 0,
                'frequency' => 0,
                'powerFactor' => 0,
                'deviceLoadStatus' => 'NONE',
                'timestamp' => 0
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($allData);
}