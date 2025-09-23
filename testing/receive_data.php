<?php

$proxyServerUrl = 'https://servify.cloud/data.php'; // Replace with your proxy server UR

$response = file_get_contents($proxyServerUrl);
if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;
} else {
    header('Content-Type: applicataion/json');
    echo json_encode([
        'wifi_status' => 'No device found',
        'pumpStatus' => 'OFF',
        'valve1Status' => 'CLOSED',
        'valve2Status' => 'CLOSED',
        'soilMoisture1' => 0,
        'soilMoisture2' => 0,
        'waterStatus' => 'No water' // Default water status
    ]);

    error_log("Failed to fetch data from proxy server.");
}


?>
