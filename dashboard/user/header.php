<?php
require_once 'authentication/user-class.php';
include_once '../../config/settings-configuration.php';
include_once '../../config/header.php';
include_once '../../config/footer.php';
require_once 'sidebar.php';

$currentPage = basename($_SERVER['PHP_SELF'], ".php"); // Gets the current page name without the extension
$sidebar = new SideBar($config, $currentPage);

$config = new SystemConfig();
$header_dashboard = new HeaderDashboard($config);
$footer_dashboard = new FooterDashboard();
$user = new USER();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname             = $user_data['first_name'];
$user_mname             = $user_data['middle_name'];
$user_lname             = $user_data['last_name'];
$user_fullname          = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_sex               = $user_data['sex'];
$user_birth_date        = $user_data['date_of_birth'];
$user_age               = $user_data['age'];
$user_civil_status      = $user_data['civil_status'];
$user_phone_number      = $user_data['phone_number'];
$user_email             = $user_data['email'];
$user_last_update       = $user_data['updated_at'];

// Retrieve sensor data
$stmt = $user->runQuery("SELECT * FROM sensors");
$stmt->execute();
$sensorData = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows

// Loop through the result set to assign data for sensors
foreach ($sensorData as $sensor) {
    // Fetch the plant data for the current sensor
    $stmt_plants = $user->runQuery("SELECT * FROM plants WHERE id=:id");
    $stmt_plants->execute(array(":id" => $sensor['plant_id']));
    $plantData = $stmt_plants->fetch(PDO::FETCH_ASSOC); // Fetch a single row
    
    if ($sensor['sensor_id'] == 1) {
        if ($plantData) {
            $plantId1 = $plantData['id'];
            $plantName1 = $plantData['plant_name'];
        }

        $selected_days1 = explode(',', $sensor['selected_days']);

        $stmt_all_days = $user->runQuery("SELECT * FROM day");
        $stmt_all_days->execute();
        $all_days1 = $stmt_all_days->fetchAll(PDO::FETCH_ASSOC);

        $sensorMode1 = $sensor['mode'];
        $waterAmountAM1 = $sensor['water_amount_am'];
        $waterAmountPM1 = $sensor['water_amount_pm'];
        $start_time_am1 = $sensor['start_time_am'];
        $start_time_pm1 = $sensor['start_time_pm'];

    } else if ($sensor['sensor_id'] == 2) {
        if ($plantData) {
            $plantId2 = $plantData['id'];
            $plantName2 = $plantData['plant_name'];
        }

        $selected_days2 = explode(',', $sensor['selected_days']);

        $stmt_all_days = $user->runQuery("SELECT * FROM day");
        $stmt_all_days->execute();
        $all_days2 = $stmt_all_days->fetchAll(PDO::FETCH_ASSOC);

        $sensorMode2 = $sensor['mode'];
        $waterAmountAM2 = $sensor['water_amount_am'];
        $waterAmountPM2 = $sensor['water_amount_pm'];
        $start_time_am2 = $sensor['start_time_am'];
        $start_time_pm2 = $sensor['start_time_pm'];
    }
}
