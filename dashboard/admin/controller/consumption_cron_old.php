<?php

//old code
// include_once __DIR__ . '/../../../database/dbconfig.php';
// require_once __DIR__ . '/../authentication/admin-class.php';

// $user = new ADMIN();
// $proxyURL = $user->proxyUrl();

// // Get the current time
// $currentTime = date('H:i');
// $currentDate = date('Y-m-d');
// error_log("Script executed at: " . date('Y-m-d H:i:s'));
// define('START_TIME', '21:55'); // time to record kwh_start
// define('END_TIME',   '21:56'); // time to record kwh_end

// try {
//     // Use the Database class to establish a connection
//     $database = new Database();
//     $pdo = $database->dbConnection();

//     // Fetch all submeter_id values assigned to rooms
//     $stmt = $pdo->prepare("SELECT submeter_id FROM rooms WHERE submeter_id IS NOT NULL");
//     $stmt->execute();
//     $assignedSubmeters = $stmt->fetchAll(PDO::FETCH_COLUMN);
//     error_log("Assigned Submeters: " . json_encode($assignedSubmeters));

//     // Fetch submeter data from receive_data.php
//     $proxyServerUrl = $proxyURL;
//     $response = file_get_contents($proxyServerUrl);
//     $submeterData = json_decode($response, true);
//     error_log("Response from receive_data.php: " . $response);

//  if ($response !== false && is_array($submeterData)) {
//     foreach ($submeterData as $submeter) {
//             if (in_array($submeter['submeterId'], $assignedSubmeters)) {
//                 // Remove time condition for testing                crontab -e
//                 if ($currentTime >= START_TIME && $currentTime < date('H:i', strtotime(START_TIME . ' +1 minute'))) {
//                     // Insert kwh_start at midnight
//                     $stmt = $pdo->prepare("INSERT INTO consumption (submeter_id, consumption_date, kwh_start) 
//                                            VALUES (:submeter_id, :currentDate, :kwh_start)
//                                            ON DUPLICATE KEY UPDATE kwh_start = VALUES(kwh_start)");
//                     if ($stmt->execute([
//                         ':submeter_id' => $submeter['submeterId'],
//                         ':currentDate' => $currentDate,
//                         ':kwh_start' => $submeter['energyKWh']
//                     ])) {
//                         error_log("kwh_start inserted/updated for submeter_id: " . $submeter['submeterId']);
//                     } else {
//                         error_log("Failed to insert/update kwh_start: " . json_encode($stmt->errorInfo()));
//                     }
//                 }

//                 // Update kwh_end at the end of the day
//                 if ($currentTime >= END_TIME && $currentTime < date('H:i', strtotime(END_TIME . ' +1 minute'))) {
//                     $stmt = $pdo->prepare("UPDATE consumption 
//                                            SET kwh_end = :kwh_end
//                                            WHERE submeter_id = :submeter_id AND consumption_date = :currentDate");
//                     if ($stmt->execute([
//                         ':submeter_id' => $submeter['submeterId'],
//                         ':currentDate' => $currentDate,
//                         ':kwh_end' => $submeter['energyKWh']
//                     ])) {
//                         error_log("kwh_end updated for submeter_id: " . $submeter['submeterId']);
//                     } else {
//                         error_log("Failed to update kwh_end: " . json_encode($stmt->errorInfo()));
//                     }
//                 }
//             }
//         }
//     } else {
//         error_log("Failed to fetch or parse data from receive_data.php");
//     }

// } catch (PDOException $e) {
//     error_log("Database error: " . $e->getMessage());
// }
?>

<!-- How to access the cron job output:
1. Open your terminal.
2. Run the command: `/opt/homebrew/bin/php /Applications/XAMPP/xamppfiles/htdocs/SMART-ENERGY-MANAGEMENT/dashboard/admin/controller/consumption_cron.php`
3. Check the output directly in the terminal or look for error logs in your server's error log file (e.g., `error_log` in XAMPP).consumption_cron.php`
4. export EDITOR=nano then crontab -e
5. Add the following line to run the script every minute for testing:
    start at 10:56 - 56 10 * * * /opt/homebrew/bin/php /Applications/XAMPP/xamppfiles/htdocs/SMART-ENERGY-MANAGEMENT/dashboard/admin/controller/consumption_cron.php
    stop at 10:57 - 57 10 * * * /opt/homebrew/bin/php /Applications/XAMPP/xamppfiles/htdocs/SMART-ENERGY-MANAGEMENT/dashboard/admin/controller/consumption_cron.php
    Ctrl + O then Ctrl + X to save and exit -->

<!-- How to run the php file manually:
1. Open your terminal.
2. Run the command: `cd /Applications/XAMPP/xamppfiles/htdocs/SMART-ENERGY-MANAGEMENT/dashboard/admin/co
ntroller/
3. Then run: `php consumption_cron.php`
4. Check the output directly in the terminal or look for error logs in your server's error log file (e.g., `error_log` in XAMPP). -->


?>