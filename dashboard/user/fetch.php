<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php echo $header_dashboard->getHeaderDashboard() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <ul class="dashboard_data" style="display:none;">
        <div class="gauge_dashboard">
            <div class="status">
                <div class="card arduino">
                    <h1>DEVICE STATUS</h1>
                    <div class="sensor-data">
                        <span id="wifi_status">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="status">
                <div class="card arduino">
                    <h1>WATER SENSOR STATUS</h1>
                    <div class="sensor-data">
                        <span id="waterStatus">Loading...</span>
                    </div>
                </div>
                <div class="card arduino">
                    <h1>PUMP SENSOR STATUS</h1>
                    <div class="sensor-data">
                        <span id="pumpStatus">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="status">
                <div class="card arduino">
                    <h1>VALVE SENSOR 1 STATUS</h1>
                    <div class="sensor-data">
                        <span id="valve1Status">Loading...</span>
                    </div>
                </div>
                <div class="card arduino">
                    <h1>VALVE SENSOR 2 STATUS</h1>
                    <div class="sensor-data">
                        <span id="valve2Status">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="gauge">
                <div class="card gauge_card">
                    <p class="card-title">SOIL SENSOR 1</p>
                    <div id="SoilSensor1"></div>
                </div>
                <div class="card gauge_card">
                    <p class="card-title">SOIL SENSOR 2</p>
                    <div id="SoilSensor2"></div>
                </div>
                <div class="card gauge_card">
                    <p class="card-title">HUMIDITY %</p>
                    <div id="humidity"></div>
                </div>
                <div class="card gauge_card">
                    <p class="card-title">TEMPERATURE °C</p>
                    <div id="temperature"></div>
                </div>
            </div>
        </div>
    </ul>
    <script src="../../src/js/gauge.js"></script>
</body>

</html>