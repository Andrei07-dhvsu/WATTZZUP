<?php
include 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the sensor data from the form
    $sensor_id = (int)$_POST['sensor'];
    $sensor_mode = $_POST['sensor_mode'];
    $dry_threshold = $_POST['sensor_dry'];
    $watered_threshold = $_POST['sensor_watered'];
    $start_date = $_POST['sensor_startDate'];
    $end_date = $_POST['sensor_endDate'];

    // Debug: Print values to check if they are being set correctly
    // echo "Sensor ID: $sensor_id, Mode: $sensor_mode, Dry Threshold: $dry_threshold, Watered Threshold: $watered_threshold, Start Date: $start_date, End Date: $end_date";

    // Prepare the SQL statement to check if the sensor exists
    $checkQuery = "SELECT * FROM sensors WHERE sensor_id = ?";
    $stmt = $conn->prepare($checkQuery);
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }
    $stmt->bind_param('i', $sensor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing sensor
        $updateQuery = "UPDATE sensors SET mode = ?, dry_threshold = ?, watered_threshold = ?, start_date = ?, end_date = ? WHERE sensor_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if (!$updateStmt) {
            die("Preparation failed: " . $conn->error);
        }
        $updateStmt->bind_param('sddssi', $sensor_mode, $dry_threshold, $watered_threshold, $start_date, $end_date, $sensor_id);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            echo "Sensor data updated successfully.";
        } else {
            echo "No changes made to the sensor data.";
        }
    } else {
        // Insert new sensor
        $insertQuery = "INSERT INTO sensors (sensor_id, mode, dry_threshold, watered_threshold, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        if (!$insertStmt) {
            die("Preparation failed: " . $conn->error);
        }
        $insertStmt->bind_param('isssss', $sensor_id, $sensor_mode, $dry_threshold, $watered_threshold, $start_date, $end_date);
        $insertStmt->execute();

        if ($insertStmt->affected_rows > 0) {
            echo "Sensor data inserted successfully.";
        } else {
            echo "Failed to insert sensor data.";
        }
    }

    // Close the statements
    $stmt->close();
    if (isset($updateStmt)) {
        $updateStmt->close();
    }
    if (isset($insertStmt)) {
        $insertStmt->close();
    }
}

// Close the connection
$conn->close();
?>
