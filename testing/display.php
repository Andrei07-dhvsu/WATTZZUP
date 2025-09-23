<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Data Display</title>
    <style>
        /* Simple styles for the display */
        .data-display {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .data-display div {
            margin-bottom: 10px;
        }

        .data-display div span {
            font-weight: bold;
        }

        .hidden {
            display: none;
        }

        .form-section {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="data-display">
        <div><span>WiFi Status:</span> <span id="wifiStatus">Loading...</span></div>
        <div><span>Pump Status:</span> <span id="pumpStatus">Loading...</span></div>
        <div><span>Valve 1 Status:</span> <span id="valve1Status">Loading...</span></div>
        <div><span>Valve 2 Status:</span> <span id="valve2Status">Loading...</span></div>
        <div><span>Soil Moisture 1:</span> <span id="soilMoisture1">Loading...</span></div>
        <div><span>Soil Moisture 2:</span> <span id="soilMoisture2">Loading...</span></div>
        <div><span>Water Status:</span> <span id="waterStatus">Loading...</span></div>
    </div>

    <h1>Update Sensor Settings</h1>

    <form id="sensor1Form" method="POST" action="update_sensor.php">
        <input type="hidden" name="sensor" value="1">
        <div class="form-section">
            <h3>Sensor 1</h3>
            <label for="sensor1_mode">Mode:</label>
            <select id="sensor1_mode" name="sensor_mode" onchange="toggleMode('sensor1')">
                <option value="automatic">Automatic</option>
                <option value="schedule">Schedule</option>
            </select>
            <div id="sensor1_automatic" class="automatic-settings">
                <label for="sensor1_dry">Dry Threshold:</label>
                <input type="number" step="0.1" id="sensor1_dry" name="sensor_dry">
                <label for="sensor1_watered">Watered Threshold:</label>
                <input type="number" step="0.1" id="sensor1_watered" name="sensor_watered">
            </div>
            <div id="sensor1_schedule" class="schedule-settings hidden">
                <label for="sensor1_startDate">Start Date:</label>
                <input type="datetime-local" id="sensor1_startDate" name="sensor_startDate">
                <label for="sensor1_endDate">End Date:</label>
                <input type="datetime-local" id="sensor1_endDate" name="sensor_endDate">
            </div>
            <button type="submit">Submit Sensor 1</button>
        </div>
    </form>

    <form id="sensor2Form" method="POST" action="update_sensor.php">
        <input type="hidden" name="sensor" value="2">
        <div class="form-section">
            <h3>Sensor 2</h3>
            <label for="sensor2_mode">Mode:</label>
            <select id="sensor2_mode" name="sensor_mode" onchange="toggleMode('sensor2')">
                <option value="automatic">Automatic</option>
                <option value="schedule">Schedule</option>
            </select>
            <div id="sensor2_automatic" class="automatic-settings">
                <label for="sensor2_dry">Dry Threshold:</label>
                <input type="number" step="0.1" id="sensor2_dry" name="sensor_dry">
                <label for="sensor2_watered">Watered Threshold:</label>
                <input type="number" step="0.1" id="sensor2_watered" name="sensor_watered">
            </div>
            <div id="sensor2_schedule" class="schedule-settings hidden">
                <label for="sensor2_startDate">Start Date:</label>
                <input type="datetime-local" id="sensor2_startDate" name="sensor_startDate">
                <label for="sensor2_endDate">End Date:</label>
                <input type="datetime-local" id="sensor2_endDate" name="sensor_endDate">
            </div>
            <button type="submit">Submit Sensor 2</button>
        </div>
    </form>

    <script>
        // Fetch data every 5 seconds
        function fetchData() {
            var xhr = new XMLHttpRequest();

            // Monitor when request state changes
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);

                    // Update the HTML elements with the fetched data
                    document.getElementById('wifiStatus').textContent = data.wifi_status;
                    document.getElementById('pumpStatus').textContent = data.pumpStatus;
                    document.getElementById('valve1Status').textContent = data.valve1Status;
                    document.getElementById('valve2Status').textContent = data.valve2Status;
                    document.getElementById('soilMoisture1').textContent = data.soilMoisture1;
                    document.getElementById('soilMoisture2').textContent = data.soilMoisture2;
                    document.getElementById('waterStatus').textContent = data.waterStatus;
                }
            };

            // Prepare the POST request with optional data (if needed)
            var postData = JSON.stringify({}); // You can pass data here if needed
            xhr.open('POST', 'receive_data.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(postData); // Send the request with postData
        }

        // Fetch data every 5 seconds
        setInterval(fetchData, 2000);
        fetchData(); // Initial fetch

        // Function to toggle between automatic and schedule modes
        function toggleMode(sensor) {
            const mode = document.getElementById(sensor + '_mode').value;
            const automaticDiv = document.getElementById(sensor + '_automatic');
            const scheduleDiv = document.getElementById(sensor + '_schedule');

            if (mode === 'automatic') {
                automaticDiv.classList.remove('hidden');
                scheduleDiv.classList.add('hidden');
            } else if (mode === 'schedule') {
                automaticDiv.classList.add('hidden');
                scheduleDiv.classList.remove('hidden');
            }
        }

        // // Fetch existing threshold values when page loads
        // window.onload = function() {
        //     fetch('get_thresholds.php')
        //         .then(response => response.json())
        //         .then(data => {
        //             document.getElementById('sensor1_dry').value = data.sensor1.dry_threshold;
        //             document.getElementById('sensor1_watered').value = data.sensor1.watered_threshold;
        //             document.getElementById('sensor2_dry').value = data.sensor2.dry_threshold;
        //             document.getElementById('sensor2_watered').value = data.sensor2.watered_threshold;
        //         });
        // };

        window.onload = function() {
            fetch('get_thresholds.php')
                .then(response => response.json())
                .then(data => {
                    // Handle sensor 1 data
                    const sensor1 = data.find(sensor => sensor.sensor_id === "1");
                    if (sensor1) {
                        document.getElementById('sensor1_mode').value = sensor1.mode;
                        document.getElementById('sensor1_dry').value = sensor1.dry_threshold;
                        document.getElementById('sensor1_watered').value = sensor1.watered_threshold;
                        document.getElementById('sensor1_mode').value = sensor1.mode;
                        document.getElementById('sensor1_startDate').value = sensor1.start_date; // Fixed
                        document.getElementById('sensor1_endDate').value = sensor1.end_date; // Fixed
                    }

                    // Handle sensor 2 data
                    const sensor2 = data.find(sensor => sensor.sensor_id === "2");
                    if (sensor2) {
                        document.getElementById('sensor2_mode').value = sensor2.mode;
                        document.getElementById('sensor2_dry').value = sensor2.dry_threshold;
                        document.getElementById('sensor2_watered').value = sensor2.watered_threshold;
                        document.getElementById('sensor2_mode').value = sensor2.mode;
                        document.getElementById('sensor2_startDate').value = sensor2.start_date; // Fixed
                        document.getElementById('sensor2_endDate').value = sensor2.end_date; // Fixed
                    }
                })
                .catch(error => console.error('Error fetching sensor data:', error));
        };
    </script>

</body>

</html>