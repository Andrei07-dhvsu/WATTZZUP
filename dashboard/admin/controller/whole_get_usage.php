<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../../../database/dbconfig.php';

$database = new Database();
$conn = $database->dbConnection();

// --- Get admin ID from session ---
session_start();
$admin_id = $_SESSION['adminSession'] ?? null;
if (!$admin_id) {
    echo json_encode(['error' => 'Admin not logged in']);
    exit;
}

// --- Params ---
$type  = isset($_GET['type']) ? trim($_GET['type']) : 'daily';
$year  = isset($_GET['year']) && $_GET['year'] !== '' ? intval($_GET['year']) : intval(date('Y'));
$month = isset($_GET['month']) && $_GET['month'] !== '' ? intval($_GET['month']) : intval(date('n'));

// --- Get all submeters of this admin ---
$subQuery = $conn->prepare("SELECT submeter_id FROM rooms WHERE owner_id = :owner_id AND submeter_id IS NOT NULL");
$subQuery->execute([':owner_id' => $admin_id]);
$submeterIds = $subQuery->fetchAll(PDO::FETCH_COLUMN);

if (!$submeterIds) {
    echo json_encode([]); // no submeters
    exit;
}

$placeholders = implode(',', array_fill(0, count($submeterIds), '?'));

try {
    if ($type === 'daily') {
        // daily usage for selected month
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end_day = date('t', strtotime($start));
        $end = sprintf('%04d-%02d-%02d', $year, $month, $end_day);

        $sql = "
            SELECT consumption_date AS date,
                   SUM(kwh_end - kwh_start) AS usage_value
            FROM daily_logs
            WHERE submeter_id IN ($placeholders)
              AND consumption_date BETWEEN ? AND ?
            GROUP BY consumption_date
            ORDER BY consumption_date ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($submeterIds, [$start, $end]));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fill missing days with 0
        $output = [];
        for ($d = 1; $d <= $end_day; $d++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $d);
            $usage = 0.0;
            foreach ($rows as $r) {
                if ($r['date'] === $date) {
                    $usage = floatval($r['usage_value']);
                    break;
                }
            }
            $output[] = ['date' => $date, 'usage' => $usage];
        }

    } elseif ($type === 'weekly') {
        // weekly usage for selected month
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end_day = date('t', strtotime($start));
        $end = sprintf('%04d-%02d-%02d', $year, $month, $end_day);

        $sql = "
            SELECT YEARWEEK(consumption_date, 1) AS weeknum,
                   MIN(consumption_date) AS date,
                   SUM(kwh_end - kwh_start) AS usage_value
            FROM daily_logs
            WHERE submeter_id IN ($placeholders)
              AND consumption_date BETWEEN ? AND ?
            GROUP BY YEARWEEK(consumption_date, 1)
            ORDER BY date ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($submeterIds, [$start, $end]));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fill missing weeks
        $startWeek = (int)date('W', strtotime($start));
        $endWeek   = (int)date('W', strtotime($end));
        $output = [];
        for ($w = $startWeek; $w <= $endWeek; $w++) {
            $usage = 0.0;
            $date = null;
            foreach ($rows as $r) {
                if ((int)date('W', strtotime($r['date'])) === $w) {
                    $usage = floatval($r['usage_value']);
                    $date = $r['date'];
                    break;
                }
            }
            // fallback date = Monday of that week
            if (!$date) {
                $date = date('Y-m-d', strtotime("{$year}-W{$w}-1"));
            }
            $output[] = ['date' => $date, 'usage' => $usage];
        }

    } elseif ($type === 'monthly') {
        // monthly usage for selected year
        $sql = "
            SELECT DATE_FORMAT(consumption_date, '%Y-%m-01') AS date,
                   SUM(kwh_end - kwh_start) AS usage_value
            FROM daily_logs
            WHERE submeter_id IN ($placeholders)
              AND YEAR(consumption_date) = ?
            GROUP BY YEAR(consumption_date), MONTH(consumption_date)
            ORDER BY date ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($submeterIds, [$year]));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fill missing months
        $output = [];
        for ($m = 1; $m <= 12; $m++) {
            $date = sprintf('%04d-%02d-01', $year, $m);
            $usage = 0.0;
            foreach ($rows as $r) {
                if ($r['date'] === $date) {
                    $usage = floatval($r['usage_value']);
                    break;
                }
            }
            $output[] = ['date' => $date, 'usage' => $usage];
        }

    } else {
        echo json_encode(['error' => 'Invalid type']);
        exit;
    }

    echo json_encode($output);
    exit;

} catch (PDOException $e) {
    echo json_encode(['error' => 'DB error', 'message' => $e->getMessage()]);
    exit;
}