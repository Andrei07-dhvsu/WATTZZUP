<?php
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/admin-class.php';

$database = new Database();
$pdo = $database->dbConnection();

$user = new ADMIN();

// 📌 Today's date
$today = date("Y-m-d");

// 📌 Fetch all rooms with submeters & limits
$stmt = $pdo->query("
    SELECT r.id, r.room_number, r.submeter_id, r.kwh_limit, r.user_id, u.email, u.name
    FROM rooms r
    JOIN users u ON r.user_id = u.id
    WHERE r.submeter_id IS NOT NULL 
      AND r.kwh_limit IS NOT NULL
");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rooms as $room) {
    // ✅ Get today's daily log for this submeter
    $logStmt = $pdo->prepare("
        SELECT kwh_used 
        FROM daily_logs
        WHERE submeter_id = :submeter_id 
          AND consumption_date = :today
    ");
    $logStmt->execute([
        ":submeter_id" => $room['submeter_id'],
        ":today"       => $today
    ]);
    $log = $logStmt->fetch(PDO::FETCH_ASSOC);

    if ($log && $log['kwh_used'] > $room['kwh_limit']) {
        // 📧 Send alert email
        $email = $room['email'];
        $roomNumber = $room['room_number'];
        $used = $log['kwh_used'];
        $limit = $room['kwh_limit'];

        $message = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Energy Usage Alert</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        margin: 0;
                        padding: 0;
                    }
                    
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 30px;
                        background-color: #ffffff;
                        border-radius: 4px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    
                    h1 {
                        color: #cc0000;
                        font-size: 22px;
                        margin-bottom: 20px;
                    }
                    
                    p {
                        color: #333333;
                        font-size: 16px;
                        margin-bottom: 10px;
                    }
                    
                    .highlight {
                        font-weight: bold;
                        color: #cc0000;
                    }
                    
                    .footer {
                        margin-top: 20px;
                        font-size: 13px;
                        color: #777777;
                    }
                    
                    .logo {
                        text-align: center;
                        margin-bottom: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='logo'>
                        <img src='cid:logo' alt='Logo' width='120'>
                    </div>
                    <h1>⚡ Daily Consumption Alert</h1>
                    <p>Hello, {$room['name']} ({$email}),</p>
                    <p>Your assigned room <b>{$roomNumber}</b> has consumed 
                        <span class='highlight'>{$used} kWh</span> today, which is above your daily limit of 
                        <span class='highlight'>{$limit} kWh</span>.
                    </p>
                    <p>Please monitor and reduce your energy usage to avoid additional charges.</p>
                    <p class='footer'>This is an automated message from the Energy Monitoring System.</p>
                </div>
            </body>
            </html>
        ";

        $subject = "⚡ Alert: Room {$roomNumber} Exceeded Daily Limit";
        $user->send_mail($email, $message, $subject, $smtp_email, $smtp_password, $system_name);
    }
}