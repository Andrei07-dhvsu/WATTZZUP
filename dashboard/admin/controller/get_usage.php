<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../../../database/dbconfig.php'; // adjust path if needed

$database = new Database();
$conn = $database->dbConnection();

// Fetch parameters
$submeter_id = isset($_GET['submeter_id']) ? trim($_GET['submeter_id']) : null;
$type = isset($_GET['type']) ? trim($_GET['type']) : 'daily';
$year = isset($_GET['year']) && $_GET['year'] !== '' ? intval($_GET['year']) : null;
$month = isset($_GET['month']) && $_GET['month'] !== '' ? intval($_GET['month']) : null;

if (!$submeter_id) {
    echo json_encode(['error' => 'submeter_id is required']);
    exit;
}

// Helper: default year/month to current if not provided for daily/weekly
$nowY = intval(date('Y'));
$nowM = intval(date('n'));

try {
    if ($type === 'daily') {
        if (!$year) $year = $nowY;
        if (!$month) $month = $nowM;

        // Start & end of selected month
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end_day = date('t', strtotime($start));
        $end = sprintf('%04d-%02d-%02d', $year, $month, $end_day);

        $sql = "
            SELECT consumption_date AS date,
                   SUM(kwh_end - kwh_start) AS usage_value
            FROM consumption
            WHERE submeter_id = :submeter_id
              AND consumption_date BETWEEN :start AND :end
            GROUP BY consumption_date
            ORDER BY consumption_date ASC
            LIMIT 31
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':submeter_id', $submeter_id, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } elseif ($type === 'weekly') {
        if (!$year) $year = $nowY;
        if (!$month) $month = $nowM;

        // Start & end of selected month
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end_day = date('t', strtotime($start));
        $end = sprintf('%04d-%02d-%02d', $year, $month, $end_day);

        // Group by YEARWEEK (week starts Monday when using mode 1)
        $sql = "
            SELECT MIN(consumption_date) AS date,
                   SUM(kwh_end - kwh_start) AS usage_value
            FROM consumption
            WHERE submeter_id = :submeter_id
              AND consumption_date BETWEEN :start AND :end
            GROUP BY YEARWEEK(consumption_date, 1)
            ORDER BY date ASC
            LIMIT 5
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':submeter_id', $submeter_id, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } elseif ($type === 'monthly') {
        // Monthly view: use year (default to current)
        if (!$year) $year = $nowY;

        $sql = "
            SELECT DATE_FORMAT(consumption_date, '%Y-%m-01') AS date,
                   SUM(kwh_end - kwh_start) AS usage_value
            FROM consumption
            WHERE submeter_id = :submeter_id
              AND YEAR(consumption_date) = :year
            GROUP BY YEAR(consumption_date), MONTH(consumption_date)
            ORDER BY date ASC
            LIMIT 12
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':submeter_id', $submeter_id, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        echo json_encode(['error' => 'Invalid type']);
        exit;
    }

    // Normalize output to { date, usage } (usage as float)
    $output = [];
    if ($rows && is_array($rows)) {
        foreach ($rows as $r) {
            $output[] = [
                'date'  => $r['date'],
                'usage' => $r['usage_value'] === null ? 0.0 : floatval($r['usage_value'])
            ];
        }
    }

    echo json_encode($output ?: []);
    exit;

} catch (PDOException $e) {
    // Return JSON error for easier debugging
    echo json_encode(['error' => 'DB error', 'message' => $e->getMessage()]);
    exit;
}

//add if theres no power (because thers no data will fetch in proxyServerUrL) what will be stores?