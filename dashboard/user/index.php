<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Dashboard</title>
</head>

<body>

	<!-- Loader -->
	<div class="loader"></div>

	<!-- SIDEBAR -->
	<?php echo $sidebar->getSideBar(); ?> <!-- This will render the sidebar -->
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu'></i>
			<form action="#">
				<div class="form-input">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<div class="username">
				<span>Hello, <label for=""><?php echo $user_fname ?></label></span>
			</div>
			<a href="profile" class="profile" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Profile">
				<img src="../../src/img/<?php echo $user_profile ?>">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Dashboard</a>
						</li>
					</ul>
				</div>
			</div>

			</div>
			<ul class="dashboard_data">
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
					<!-- <div class="status">
						<div class="card arduino">
							<h1>SOIL SENSOR 1 STATUS</h1>
							<div class="sensor-data">
								<span id="soilMoisture1">Loading...</span>
							</div>
						</div>
						<div class="card arduino">
							<h1>SOIL SENSOR 2 STATUS</h1>
							<div class="sensor-data">
								<span id="soilMoisture2">Loading...</span>
							</div>
						</div>
					</div>
					<div class="status">
						<div class="card arduino">
							<h1>HUMIDITY</h1>
							<div class="sensor-data">
								<span id="humidity">Loading...</span>
							</div>
						</div>
						<div class="card arduino">
							<h1>TEMPERATURE</h1>
							<div class="sensor-data">
								<span id="temperature">Loading...</span>
							</div>
						</div>
					</div> -->

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
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script src="../../src/js/gauge.js"></script>
	<!-- <script>
		function fetchData() {
			var xhr = new XMLHttpRequest();

			// Monitor when request state changes
			xhr.onreadystatechange = function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						var data = JSON.parse(xhr.responseText);

						// Update WiFi status
						const wifiStatusElement = document.getElementById('wifiStatus');
						if (wifiStatusElement) {
							wifiStatusElement.textContent = data.wifi_status;
							// Change color based on WiFi status
							if (data.wifi_status.toLowerCase() === 'connected') {
								wifiStatusElement.style.color = 'green';
							} else if (data.wifi_status.toLowerCase() === 'no device found') {
								wifiStatusElement.style.color = 'red';
							} else {
								wifiStatusElement.style.color = 'black';
							}
						} else {
							console.error("Element 'wifiStatus' not found.");
						}
						const pumpStatusElement = document.getElementById('pumpStatus');
						if (pumpStatusElement) {
							pumpStatusElement.textContent = data.pumpStatus;
							// Change color based on pump status
							if (data.pumpStatus.toLowerCase() === 'on') {
								pumpStatusElement.style.color = 'green';
							} else if (data.pumpStatus.toLowerCase() === 'off' || data.pumpStatus.toLowerCase() === 'not connected') {
								pumpStatusElement.style.color = 'red';
							} else {
								pumpStatusElement.style.color = 'black';
							}
						} else {
							console.error("Element 'pumpStatus' not found.");
						}

						const valve1StatusElement = document.getElementById('valve1Status');
						if (valve1StatusElement) {
							valve1StatusElement.textContent = data.valve1Status;
							// Change color based on valve1 status
							if (data.valve1Status.toLowerCase() === 'open') {
								valve1StatusElement.style.color = 'green';
							} else if (data.valve1Status.toLowerCase() === 'closed' || data.valve1Status.toLowerCase() === 'not connected') {
								valve1StatusElement.style.color = 'red';
							} else {
								valve1StatusElement.style.color = 'black';
							}
						} else {
							console.error("Element 'valve1Status' not found.");
						}

						const valve2StatusElement = document.getElementById('valve2Status');
						if (valve2StatusElement) {
							valve2StatusElement.textContent = data.valve2Status;
							// Change color based on valve2 status
							if (data.valve2Status.toLowerCase() === 'open') {
								valve2StatusElement.style.color = 'green';
							} else if (data.valve2Status.toLowerCase() === 'closed' || data.valve2Status.toLowerCase() === 'not connected') {
								valve2StatusElement.style.color = 'red';
							} else {
								valve2StatusElement.style.color = 'black';
							}
						} else {
							console.error("Element 'valve2Status' not found.");
						}

						const soilMoisture1Element = document.getElementById('soilMoisture1');
						if (soilMoisture1Element) {
							soilMoisture1Element.textContent = data.soilMoisture1;
						} else {
							console.error("Element 'soilMoisture1' not found.");
						}

						const soilMoisture2Element = document.getElementById('soilMoisture2');
						if (soilMoisture2Element) {
							soilMoisture2Element.textContent = data.soilMoisture2;
						} else {
							console.error("Element 'soilMoisture2' not found.");
						}
						const humidityElement = document.getElementById('humidity');
						if (humidityElement) {
							humidityElement.textContent = `${data.humidity} %`;
						} else {
							console.error("Element 'humidity' not found.");
						}

						const temperatureElement = document.getElementById('temperature');
						if (temperatureElement) {
							temperatureElement.textContent = `${data.temperature} °C`;
						} else {
							console.error("Element 'temperature' not found.");
						}


						// Update Water status
						const waterStatusElement = document.getElementById('waterStatus');
						if (waterStatusElement) {
							waterStatusElement.textContent = data.waterStatus;
							// Change color based on water status
							if (data.waterStatus.toLowerCase() === 'water level is normal') {
								waterStatusElement.style.color = 'green';
							} else if (data.waterStatus.toLowerCase() === 'water level is low' || data.waterStatus.toLowerCase() === 'not connected' || data.waterStatus.toLowerCase() === 'no water') {
								waterStatusElement.style.color = 'red';
							} else {
								waterStatusElement.style.color = 'black';
							}
						} else {
							console.error("Element 'waterStatus' not found.");
						}

					} else {
						console.error("Failed to fetch data. Status: " + xhr.status);
					}
				}
			};

			// Prepare the POST request with optional data (if needed)
			var postData = JSON.stringify({});
			xhr.open('POST', 'controller/receive_data.php', true);
			xhr.setRequestHeader('Content-Type', 'application/json');
			xhr.send(postData);
		}

		// Fetch data every 2 seconds
		setInterval(fetchData, 2000);
		fetchData(); // Initial fetch
	</script> -->
</body>

</html>