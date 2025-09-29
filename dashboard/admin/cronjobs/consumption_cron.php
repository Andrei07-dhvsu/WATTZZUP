<?php

include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/admin-class.php';

$user = new ADMIN();
$proxyURL = $user->proxyUrl();

$currentTime = date('H:i');
$currentDate = date('Y-m-d');
error_log("Script executed at: " . date('Y-m-d H:i:s'));

try {
    $database = new Database();
    $pdo = $database->dbConnection();

    // Fetch all submeters assigned to rooms
    $stmt = $pdo->prepare("SELECT submeter_id FROM rooms WHERE submeter_id IS NOT NULL");
    $stmt->execute();
    $assignedSubmeters = $stmt->fetchAll(PDO::FETCH_COLUMN);

    error_log("Assigned Submeters: " . json_encode($assignedSubmeters));

    // Fetch submeter data
    $response = file_get_contents($proxyURL);
    $submeterData = json_decode($response, true);

    if ($response !== false && is_array($submeterData)) {
        foreach ($submeterData as $submeter) {
            if (in_array($submeter['submeterId'], $assignedSubmeters)) {
                $submeterId = $submeter['submeterId'];
                $energyKWh = $submeter['energyKWh'] ?? 0;

                // Check if a row for today already exists
                $stmt = $pdo->prepare("SELECT * FROM daily_logs WHERE submeter_id = :submeter_id AND consumption_date = :currentDate");
                $stmt->execute([
                    ':submeter_id' => $submeterId,
                    ':currentDate' => $currentDate
                ]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    // No row exists yet, create with kwh_start
                    $stmtInsert = $pdo->prepare("INSERT INTO daily_logs (submeter_id, consumption_date, kwh_start, kwh_end) 
                                                 VALUES (:submeter_id, :currentDate, :kwh_start, :kwh_end)");
                    $stmtInsert->execute([
                        ':submeter_id' => $submeterId,
                        ':currentDate' => $currentDate,
                        ':kwh_start'   => $energyKWh,
                        ':kwh_end'     => $energyKWh
                    ]);
                    error_log("kwh_start inserted for submeter_id $submeterId with value $energyKWh");
                } else {
                    // Row exists, just update kwh_end
                    $stmtUpdate = $pdo->prepare("UPDATE daily_logs SET kwh_end = :kwh_end 
                                                 WHERE submeter_id = :submeter_id AND consumption_date = :currentDate");
                    $stmtUpdate->execute([
                        ':submeter_id' => $submeterId,
                        ':currentDate' => $currentDate,
                        ':kwh_end'     => $energyKWh
                    ]);
                    error_log("kwh_end updated for submeter_id $submeterId with value $energyKWh");
                }
            }
        }
    } else {
        error_log("Failed to fetch or parse data from receive_data.php");
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
}

?>