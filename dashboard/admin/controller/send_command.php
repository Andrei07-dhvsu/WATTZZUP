<?php
header('Content-Type: application/json');

include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/admin-class.php';

$user = new ADMIN();
$database = new Database();
$db = $database->dbConnection();

// ✅ Fetch all appliances from DB
$stmt = $user->runQuery("SELECT switch_id, status FROM appliances");
$stmt->execute();

$appliances = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $appliances[] = [
        "switchId" => $row['switch_id'],
        "state" => strtoupper($row['status']) // ensure values like "ON"/"OFF"
    ];
}

// ✅ Return JSON (pretty for debugging)
echo json_encode($appliances, JSON_PRETTY_PRINT);
?>