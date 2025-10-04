<?php
// Directory to store the latest switch data
$dataDir = 'switch_data/';
$timeoutDuration = 60; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the data directory exists
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0777, true);
    }

    // Receive data from switch
    $data = file_get_contents('php://input');
    $switchData = json_decode($data, true);

    // Check for switchId in the data
    if (isset($switchData['switchId'])) {
        $deviceId = $switchData['switchId'];
        $dataFile = $dataDir . $deviceId . '.json';
        $switchData['timestamp'] = time(); // Add/update timestamp
        file_put_contents($dataFile, json_encode($switchData));
        echo 'switch Data received';
    } else {
        echo 'switchId missing';
    }
} else {
    // Serve the latest data for all switchs
    $allData = [];
    foreach (glob($dataDir . '*.json') as $filename) {
        $deviceData = json_decode(file_get_contents($filename), true);
        $currentTime = time();
        $dataAge = $currentTime - ($deviceData['timestamp'] ?? 0);

        if ($dataAge <= $timeoutDuration) {
            // Device is online — return actual data
            $allData[] = $deviceData;
        } else {
            // Device is offline — return zeros, including switchId and timestamp set to 0
            $allData[] = [
                'wifi_status' => 'No device found',
                'switchId' => 0,
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($allData);
}
