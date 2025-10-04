<?php
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/user-class.php';

$user = new USER();

$currentUserId = $_SESSION['userSession'] ?? '';

// ✅ Get logged-in user’s data + associated room data
$stmt = $user->runQuery("
    SELECT 
        u.id AS user_id,
        u.profile,
        u.first_name,
        u.middle_name,
        u.last_name,
        u.sex,
        u.date_of_birth,
        u.age,
        u.civil_status,
        u.phone_number,
        u.email,
        r.id AS room_id,
        r.owner_id,
        r.room_number,
        r.submeter_id,
        r.kwh_limit,
        r.status AS room_status,
        r.updated_at AS room_last_update
    FROM users u
    INNER JOIN rooms r ON FIND_IN_SET(u.id, r.user_id)
    WHERE u.id = :user_id
      AND u.account_status = 'Active'
    LIMIT 1
");
$stmt->execute([':user_id' => $currentUserId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$rooms_user_id           = $data['user_id'] ?? '';
$admin_id               = $data['owner_id'] ?? '';

$year = isset($_GET['year']) ? intval($_GET['year']) : date("Y");
$submeter_id = isset($_GET['submeter_id']) ? trim($_GET['submeter_id']) : null;


$stmt = $user->runQuery("
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