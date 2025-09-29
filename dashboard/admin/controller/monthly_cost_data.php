<?php
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/admin-class.php';

$database = new Database();
$pdo = $database->dbConnection();

$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
$admin_id = $_SESSION['adminSession'];
$submeter_id = isset($_GET['submeter_id']) ? trim($_GET['submeter_id']) : null;


$stmt = $pdo->prepare("
    SELECT 
        month, 
        total_cost 
    FROM monthly_energy_cost 
    WHERE admin_id = :admin_id AND year = :year AND submeter_id = :submeter_id
    ORDER BY month ASC
");
$stmt->execute([
    ":admin_id" => $admin_id,
    ":year" => $year,
    ":submeter_id" => $submeter_id
]);

$data = [];
$months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Map results to month names (ensure all 12 months exist)
for ($i = 1; $i <= 12; $i++) {
    $found = array_filter($results, fn($r) => intval($r['month']) === $i);
    $cost = $found ? array_values($found)[0]['total_cost'] : 0;
    $data[] = ["month" => $months[$i-1], "cost" => (float)$cost];
}

header('Content-Type: application/json');
echo json_encode($data);