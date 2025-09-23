<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/user-class.php';

class Sensor
{
    private $conn;
    private $user;

    public function __construct()
    {
        $this->user = new USER();


        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }


    public function sensorThresholds($sensorId, $sensorMode, $plant_id, $water_amount_am, $water_amount_pm,  $start_time_am, $start_time_pm, $selected_days){

        $stmt = $this->user->runQuery('SELECT * FROM sensors WHERE sensor_id=:sensor_id');
        $stmt->execute(array(":sensor_id" => $sensorId));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(
            $row["plant_id"] == $plant_id &&
            $row["mode"] == $sensorMode &&
            $row["water_amount_am"] == $water_amount_am &&
            $row["water_amount_pm"] == $water_amount_pm &&
            $row["start_time_am"] == $start_time_am &&
            $row["start_time_pm"] == $start_time_pm &&
            $row["selected_days"] == $selected_days
        )
        {
            if($sensorId == 1){
                $_SESSION['status_title'] = 'Oopss!';
                $_SESSION['status'] = 'No changes have been made to your Sensor 1 thresholds.';
                $_SESSION['status_code'] = 'info';
                $_SESSION['status_timer'] = 40000;    
            }
            else if ($sensorId == 2){
                $_SESSION['status_title'] = 'Oopss!';
                $_SESSION['status'] = 'No changes have been made to your Sensor 2 thresholds.';
                $_SESSION['status_code'] = 'info';
                $_SESSION['status_timer'] = 40000;    
            }
            header('Location: ../thresholds');
            exit;
        }

        $stmt = $this->user->runQuery('UPDATE sensors SET plant_id=:plant_id, mode=:mode, water_amount_am=:water_amount_am, water_amount_pm=:water_amount_pm, start_time_am=:start_time_am, start_time_pm=:start_time_pm, selected_days=:selected_days WHERE sensor_id=:sensor_id');
        $exec = $stmt->execute(array(

            ":sensor_id"            => $sensorId,
            ":plant_id"             => $plant_id,
            ":mode"                 => $sensorMode,
            ":water_amount_am"      => $water_amount_am,
            ":water_amount_pm"      => $water_amount_pm,
            ":start_time_am"        => $start_time_am,
            ":start_time_pm"        => $start_time_pm,
            ":selected_days"        => $selected_days,
        ));
        
        if ($exec) {
            if($sensorId == 1){
                // Log activity
                $activity = "Sensor 1 Thresholds successfully updated";
                $user_id = $_SESSION['userSession'];
                $this->user->logs($activity, $user_id);

                $_SESSION['status_title'] = "Success!";
                $_SESSION['status'] = "Sensor 1 Thresholds successfully updated";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_timer'] = 40000;
            }
            else if ($sensorId == 2){
                $_SESSION['status_title'] = "Success!";
                $_SESSION['status'] = "Sensor 2 Thresholds successfully updated";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_timer'] = 40000;

                // Log activity
                $activity = "Sensor 2 Thresholds successfully updated";
                $user_id = $_SESSION['userSession'];
                $this->user->logs($activity, $user_id);
            }
        }

        header('Location: ../thresholds');
        exit;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

if(isset($_POST['btn-update-thresholds'])){
    $sensorId = trim($_POST['sensorId']);
    $sensorMode = trim($_POST['mode']);
    $plant_id = trim($_POST['plant_name']);
    $water_amount_am = trim($_POST['water_amount_am']);
    $water_amount_pm = trim($_POST['water_amount_pm']);
    $start_time_am = trim($_POST['start_time_am']);
    $start_time_pm = trim($_POST['start_time_pm']);
    //days
    if (isset($_POST['days']) && is_array($_POST['days'])) {
        $selected_days = implode(', ', $_POST['days']);
        // Now $selected_days contains a serialized string of selected days
    }



    $sensorData = new Sensor();
    $sensorData->sensorThresholds($sensorId, $sensorMode, $plant_id, $water_amount_am, $water_amount_pm,  $start_time_am, $start_time_pm, $selected_days);

}
?>