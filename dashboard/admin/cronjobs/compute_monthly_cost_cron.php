<?php
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/admin-class.php';

$user = new ADMIN();
$proxyURL = $user->proxyUrl();

$admin_id = $_SESSION['adminSession'];

$database = new Database();
$pdo = $database->dbConnection();

function generateMonthlyCost($pdo, $admin_id) {
    // 🔹 Get all unique submeters from daily_logs
    $stmt = $pdo->query("SELECT DISTINCT submeter_id FROM daily_logs");
    $submeters = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($submeters as $submeter_id) {
        // Get grouped months per submeter
        $stmt = $pdo->prepare("
            SELECT 
                YEAR(created_at) AS year, 
                MONTH(created_at) AS month,
                MIN(kwh_start) AS kwh_start,
                MAX(kwh_end) AS kwh_end
            FROM daily_logs
            WHERE submeter_id = :submeter_id
            GROUP BY YEAR(created_at), MONTH(created_at)
        ");
        $stmt->execute([":submeter_id" => $submeter_id]);
        $months = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($months as $m) {
            $total_kwh = $m['kwh_end'] - $m['kwh_start'];

            // Get latest kwh cost for this admin
            $stmt2 = $pdo->prepare("
                SELECT cost 
                FROM kwh_cost 
                WHERE admin_id = :admin_id 
                ORDER BY id DESC LIMIT 1
            ");
            $stmt2->execute([":admin_id" => $admin_id]);
            $kwh_cost = $stmt2->fetchColumn();

            if (!$kwh_cost) {
                $kwh_cost = 0;
            }

            $total_cost = $total_kwh * $kwh_cost;

            // Insert or update into monthly_energy_cost
            $stmt3 = $pdo->prepare("
                INSERT INTO monthly_energy_cost 
                    (admin_id, submeter_id, year, month, total_kwh, kwh_cost, total_cost) 
                VALUES 
                    (:admin_id, :submeter_id, :year, :month, :total_kwh, :kwh_cost, :total_cost)
                ON DUPLICATE KEY UPDATE 
                    total_kwh = VALUES(total_kwh), 
                    kwh_cost = VALUES(kwh_cost), 
                    total_cost = VALUES(total_cost), 
                    updated_at = NOW()
            ");
            $stmt3->execute([
                ":admin_id"   => $admin_id,
                ":submeter_id"=> $submeter_id,
                ":year"       => $m['year'],
                ":month"      => $m['month'],
                ":total_kwh"  => $total_kwh,
                ":kwh_cost"   => $kwh_cost,
                ":total_cost" => $total_cost
            ]);
        }
    }
}

// 👉 Call the function
generateMonthlyCost($pdo, $admin_id);