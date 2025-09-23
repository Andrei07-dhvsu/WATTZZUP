<?php
// Directory to store the latest submeter data
$dataDir = 'submeter_data/';
$timeoutDuration = 60; // 1 minute timeout duration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure the data directory exists
    if (!file_exists($dataDir)) {
        mkdir($dataDir, 0777, true);
    }

    // Receive data from submeter
    $data = file_get_contents('php://input');
    $submeterData = json_decode($data, true);

    // Check for submeterId in the data
    if (isset($submeterData['submeterId'])) {
        $deviceId = $submeterData['submeterId'];
        $dataFile = $dataDir . $deviceId . '.json';
        $submeterData['timestamp'] = time(); // Add/update timestamp
        file_put_contents($dataFile, json_encode($submeterData));
        echo 'Submeter Data received';
    } else {
        echo 'submeterId missing';
    }
} else {
    // Serve the latest data for all submeters
    $allData = [];
    foreach (glob($dataDir . '*.json') as $filename) {
        $deviceData = json_decode(file_get_contents($filename), true);
        $currentTime = time();
        $dataAge = $currentTime - ($deviceData['timestamp'] ?? 0);

        if ($dataAge <= $timeoutDuration) {
            // Device is online — return actual data
            $allData[] = $deviceData;
        } else {
            // Device is offline — return zeros, including submeterId and timestamp set to 0
            $allData[] = [
                'wifi_status' => 'No device found',
                'submeterId' => 0,
                'voltage' => 0.0,
                'current' => 0.0,
                'power' => 0.0,
                'energyWh' => 0.0,
                'energyKWh' => 0.0,
                'frequency' => 0.0,
                'powerFactor' => 0.0,
                'timestamp' => 0
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($allData);
}
