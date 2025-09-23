<?php
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/user-class.php';

class SensorLogger
{
    private $conn;
    private $defaultValues;
    private $excludedSensors;

    public function __construct()
    {
        // Create a new Database instance and establish the connection
        $database = new Database();
        $this->conn = $database->dbConnection();
        
        // Default sensor values (for initial fetch failure scenario)
        $this->defaultValues = [
            'wifi_status' => 'NO DEVICE FOUND',
            'pumpStatus' => 'OFF',
            'valve1Status' => 'CLOSED',
            'valve2Status' => 'CLOSED',
            'soilMoisture1' => 0,  // Soil moisture sensors excluded from logging
            'soilMoisture2' => 0,  // Soil moisture sensors excluded from logging
            'currentWaterAmount1' => 0,
            'currentWaterAmount2' => 0,
            'humidity' => 0,
            'temperature' => 0,
            'waterStatus' => 'WATER LEVEL IS LOW!',
            'alertMessage1' => '',
            'alertMessage2' => '',
            'alertMessageWater' => '',
        ];

        // Sensors to exclude from logging (e.g., soil moisture sensors)
        $this->excludedSensors = ['pumpStatus', 'valve1Status', 'valve2Status', 'soilMoisture1', 'soilMoisture2', 'currentWaterAmount1', 'currentWaterAmount2', 'humidity', 'temperature', 'timestamp'];
    }

    // Fetch data from proxy server
    public function fetchData()
    {
        $proxyServerUrl = 'https://servify.cloud/dashboard/admin/controller/data.php'; // Replace with your proxy server URL
        return file_get_contents($proxyServerUrl);
    }

    // Send notification email when a sensor status changes
    private function sendEmailNotification($sensor, $currentValue)
    {
        // Assuming you have a method to get SMTP details
        $user = new USER();
        $smtp_email = $user->smtpEmail();
        $smtp_password = $user->smtpPassword();
        $system_name = $user->systemName();
        
        // retrieve user data
        $stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
        $stmt->execute(array(":uid"=>$_SESSION['adminSession']));
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        $email = $user_data['email'];
        $subject = "Sensor Alert: $sensor Status Changed";

        $message = "
        <html>
        <head><title>Irrigation Status Update</title></head>
        <body>
            <p>Dear User,</p>
            <p>$currentValue</p>
            <p>Please take the necessary actions.</p>
            <p>Thank you,</p>
            <p>$system_name</p>
        </body>
        </html>";

        // Assuming send_mail is a method to handle sending emails
        $user->send_mail($email, $message, $subject, $smtp_email, $smtp_password, $system_name);
    }

    // Log sensor data changes into the database
    public function logSensorData($sensorData)
    {
        foreach ($sensorData as $sensor => $currentValue) {
            // Skip logging for excluded sensors (soil moisture)
            if (in_array($sensor, $this->excludedSensors)) {
                continue; // Skip logging for soil moisture sensors but still display
            }
    
            // Fetch the last logged value from the database for this sensor
            $stmt = $this->conn->prepare("SELECT status FROM sensor_logs WHERE sensor = :sensor ORDER BY id DESC LIMIT 1");
            $stmt->bindParam(":sensor", $sensor);
            $stmt->execute();
            $lastLoggedStatus = $stmt->fetchColumn();
    
            // Log the change only if the current value is different from the last logged value
            if ($currentValue != $lastLoggedStatus) {
                // Log the change in the database
                $stmt = $this->conn->prepare("INSERT INTO sensor_logs (sensor, status) VALUES (:sensor, :status)");
                $stmt->bindParam(":sensor", $sensor);
                $stmt->bindParam(":status", $currentValue);
                if ($stmt->execute()) {
                    error_log("Logged change for $sensor: $currentValue");
    
                    // Send email notification for every sensor that changes
                    $this->sendEmailNotification($sensor, $currentValue);
                } else {
                    error_log("Failed to log change for $sensor: " . $stmt->errorInfo()[2]);
                }
            }
        }
    }
    
    // Handle the process of fetching and logging sensor data
    public function processSensorData()
    {
        $response = $this->fetchData();

        if ($response !== false) {
            header('Content-Type: application/json');
            $sensorData = json_decode($response, true); // Decode the response into an associative array
            
            // Log sensor data changes
            $this->logSensorData($sensorData);

            // Send the fetched data as a response (including soil moisture sensor data)
            echo json_encode($sensorData);
        } else {
            // If data fetching fails, return default values
            header('Content-Type: application/json');
            echo json_encode($this->defaultValues);
            error_log("Failed to fetch data from proxy server.");
        }
    }
}

// Instantiate the SensorLogger class and process sensor data
$sensorLogger = new SensorLogger();
$sensorLogger->processSensorData();

?>
