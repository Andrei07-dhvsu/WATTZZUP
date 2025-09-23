<?php
require_once __DIR__ . '/../authentication/admin-class.php';
$user = new ADMIN();
$proxyURL = $user->proxyUrl();

$proxyServerUrl = $proxyURL; // Replace with your proxy server URL

$response = file_get_contents($proxyServerUrl);
if ($response !== false) {
    header('Content-Type: application/json');
    echo $response;
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'wifi_status' => 'No device found',
        'voltage' => 0.0,
        'current' => 0.0,
        'power' => 0.0,
        'energyWh' => 0.0,
        'energyKWh' => 0.0,
        'frequency' => 0.0,
        'powerFactor' => 0.0
    ]);

    error_log("Failed to fetch data from proxy server.");
}


?>
