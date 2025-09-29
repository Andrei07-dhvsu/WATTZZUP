<?php

include_once __DIR__ . '/../../../database/dbconfig.php';

// Sensor Class to fetch data from the sensor table
class Sensor
{
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($db)
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    // Method to fetch all sensor data with modified attributes
    public function getAllSensors()
    {
        // Fetch all sensors
        $query = 'SELECT * FROM sensors';
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($sensors)) {
                return ["error" => "No sensor data found."];
            }

            $modifiedSensors = [];
            foreach ($sensors as $sensor) {
                // Fetch plant name, dry threshold, and watered threshold using plant_id
                $plantQuery = 'SELECT plant_name, dry_threshold, watered_threshold FROM plants WHERE id = :plant_id';
                $stmtPlant = $this->conn->prepare($plantQuery);
                $stmtPlant->execute([':plant_id' => $sensor['plant_id']]);
                $plant = $stmtPlant->fetch(PDO::FETCH_ASSOC);
                
                $plantName = $plant ? $plant['plant_name'] : 'Unknown';
                $dryThreshold = isset($plant['dry_threshold']) ? $plant['dry_threshold'] : 'Unknown';
                $wateredThreshold = isset($plant['watered_threshold']) ? $plant['watered_threshold'] : 'Unknown';

                // Fetch all day names using selected_days
                $selectedDays = explode(',', $sensor['selected_days']);
                $dayNames = [];
                if (!empty($selectedDays)) {
                    $placeholders = implode(',', array_fill(0, count($selectedDays), '?'));
                    $dayQuery = "SELECT day FROM day WHERE id IN ($placeholders)";
                    $stmtDay = $this->conn->prepare($dayQuery);
                    $stmtDay->execute($selectedDays);
                    $dayResults = $stmtDay->fetchAll(PDO::FETCH_ASSOC);
                    $dayNames = array_column($dayResults, 'day');
                }
                $userPhone = 'SELECT * FROM system_config LIMIT 1';
                $stmt = $this->conn->prepare($userPhone);
                $stmt->execute(array());
                $system_config = $stmt->fetch(PDO::FETCH_ASSOC);

                // Prepare the modified sensor data
                $modifiedSensor = [
                    "sensor_id" => $sensor['sensor_id'],
                    "mode" => $sensor['mode'],
                    "plantName" => $plantName,
                    "dry_threshold" => $dryThreshold,
                    "watered_threshold" => $wateredThreshold,
                    "waterAmountAM" => $sensor['water_amount_am'],
                    "waterAmountPM" => $sensor['water_amount_pm'],
                    "startTimeAM" => $sensor['start_time_am'],
                    "startTimePM" => $sensor['start_time_pm'],
                    "selectedDays" => $dayNames,
                    "current_day" => date("l"),    // Current day
                    "current_time" => date("H:i:s"), // Current time
                    "phoneNumber" => '0' . $system_config['system_phone_number']
                    
                ];

                // Add to the result array
                $modifiedSensors[] = $modifiedSensor;
            }

            return $modifiedSensors;
        } else {
            return ["error" => "Failed to fetch sensor data."];
        }
    }
}

// Instantiate the Database and Sensor classes
try {
    $sensor = new Sensor($db); // Pass the connection to the Sensor class

    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    header("Content-Type: application/json");

    // Fetch all sensors and return as JSON
    $sensorData = $sensor->getAllSensors();
    echo json_encode($sensorData);
} catch (Exception $e) {
    // Handle any unexpected errors
    echo json_encode(["error" => $e->getMessage()]);
}
