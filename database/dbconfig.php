<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        // Check if the script is running in CLI mode
        if (php_sapi_name() === 'cli' || !isset($_SERVER['SERVER_NAME'])) {
            // Default to localhost configuration for CLI
            $this->host = "127.0.0.1"; // Use 127.0.0.1 to force TCP connection
            $this->db_name = "smart_energy_management";
            $this->username = "root";
            $this->password = "";
        } else {
            // Check if the server is running on localhost
            if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_ADDR'] === '192.168.1.72') {
                // Localhost connection
                $this->host = "localhost";
                $this->db_name = "smart_energy_management";
                $this->username = "root";
                $this->password = "";
            } else {
                // Live server connection
                $this->host = "localhost";
                $this->db_name = "u607408406_smart2025";
                $this->username = "u607408406_smart2025";
                $this->password = "?|]1h1q5G";
            }
        }
    }

    public function dbConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Set the MySQL session time zone to Asia/Manila
            $this->conn->exec("SET time_zone = '+08:00'");

        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>