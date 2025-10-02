<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once __DIR__ . '/../../../database/dbconfig.php';

$database = new Database();
$pdo = $database->dbConnection();

function generateMonthlyCost($pdo, $admin_id) {
    // 🔹 Get all unique submeters linked to this admin
    $stmt = $pdo->prepare("
        SELECT DISTINCT dl.submeter_id
        FROM daily_logs dl
        INNER JOIN rooms r ON dl.submeter_id = r.submeter_id
        WHERE r.owner_id = :owner_id
    ");
    $stmt->execute([":owner_id" => $admin_id]);
    $submeters = $stmt->fetchAll(PDO::FETCH_COLUMN);

    foreach ($submeters as $submeter_id) {
        // Group by year/month
        $stmt = $pdo->prepare("
            SELECT 
                YEAR(dl.created_at) AS year, 
                MONTH(dl.created_at) AS month,
                MIN(dl.kwh_start) AS kwh_start,
                MAX(dl.kwh_end) AS kwh_end
            FROM daily_logs dl
            INNER JOIN rooms r ON dl.submeter_id = r.submeter_id
            WHERE dl.submeter_id = :submeter_id
              AND r.owner_id = :owner_id
            GROUP BY YEAR(dl.created_at), MONTH(dl.created_at)
        ");
        $stmt->execute([
            ":submeter_id" => $submeter_id,
            ":owner_id"    => $admin_id
        ]);
        $months = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($months as $m) {
            $total_kwh = $m['kwh_end'] - $m['kwh_start'];

            // Get latest kWh cost for this admin
            $stmt2 = $pdo->prepare("
                SELECT cost 
                FROM kwh_cost 
                WHERE admin_id = :admin_id 
                ORDER BY id DESC LIMIT 1
            ");
            $stmt2->execute([":admin_id" => $admin_id]);
            $kwh_cost = $stmt2->fetchColumn() ?: 0;

            $total_cost = $total_kwh * $kwh_cost;

            // Insert/update monthly_energy_cost
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

// 🔹 Get all admins (from users table)
$stmt = $pdo->query("SELECT id FROM users WHERE user_type = 1");
$admins = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 🔹 Run for each admin
foreach ($admins as $admin_id) {
    generateMonthlyCost($pdo, $admin_id);
    echo "✅ Computed monthly cost for admin_id: $admin_id\n";
}