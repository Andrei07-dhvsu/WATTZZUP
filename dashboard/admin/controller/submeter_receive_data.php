<?php
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/admin-class.php';

$user = new ADMIN();
$proxyURL = $user->proxyUrl();

if (!$user->isUserLoggedIn()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$admin_id = $_SESSION['adminSession'];


// ✅ 1. Get all submeter_ids that belong to this admin (from `rooms` table)
$stmt = $user->runQuery("SELECT submeter_id FROM rooms WHERE owner_id = :admin_id AND submeter_id IS NOT NULL");
$stmt->execute([':admin_id' => $admin_id]);
$submeter_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// If no registered submeter, return empty
if (empty($submeter_ids)) {
    echo json_encode([]);
    exit;
}

// ✅ 2. Fetch all real-time submeter data from your proxy server
$response = @file_get_contents($proxyURL);
if ($response === false) {
    echo json_encode([]);
    exit;
}

$data = json_decode($response, true);

// ✅ 3. Filter JSON to only include submeters that belong to this admin
$filtered = array_filter($data, function ($item) use ($submeter_ids) {
    return in_array($item['submeterId'], $submeter_ids);
});

// ✅ 4. Send filtered JSON
header('Content-Type: application/json');
echo json_encode(array_values($filtered));
?>