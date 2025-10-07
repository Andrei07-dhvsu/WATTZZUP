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
							<h1>⚡ Total Energy Consumption</h1>
							<div class="sensor-data">
								<span id="energyKWh">0.0</span>
							</div>
						</div>
						<div class="card arduino">
							<h1>🔌 Total Current</h1>
							<div class="sensor-data">
								<span id="current">0.0</span>
							</div>
						</div>
						<div class="card arduino">
							<h1>📐 Total Power Factor</h1>
							<div class="sensor-data">
								<span id="powerFactor">0.0</span>
							</div>
						</div>
						<div class="card arduino">
							<h1>👭 Total Tenants</h1>
							<div class="sensor-data">
								<span id="totalTenants"><?php echo $totalUsers; ?></span>
							</div>
						</div>

						<div class="card arduino">
							<h1>🏠 Total Rooms</h1>
							<div class="sensor-data">
								<span id="totalRooms"><?php echo $totalRooms; ?></span>
							</div>
						</div>
					</div>
                        <div class="gauge">
                            <div class="card gauge_card">
                                <div class="d-flex align-items-center gap-2 mb-3" style="margin-top: 20px;">
                                    <div class="d-flex align-items-center">
                                        <label for="yearSelectCost" class="me-2 mb-0">Year:</label>
                                        <select id="yearSelectCost" class="form-select form-select-sm"></select>
                                    </div>
                                </div>

                                <p class="card-title">Energy Cost Graph</p>
                                <div id="S1"></div>
                            </div>
                            <div class="card gauge_card">
                                <p class="card-title">Usage Estimate</p>
                                <div class="d-flex align-items-center gap-2 mb-3" style="margin-top: 20px;">
                                    <button id="dailyBtn" class="chart-btn">Daily</button>
                                    <button id="weeklyBtn" class="chart-btn">Weekly</button>
                                    <button id="monthlyBtn" class="chart-btn">Monthly</button>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3" style="margin-top: 20px;">
                                    <div class="d-flex align-items-center">
                                        <label for="monthSelect" class="me-2 mb-0">Month:</label>
                                        <select id="monthSelect" class="form-select form-select-sm"></select>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <label for="yearSelect" class="me-2 mb-0">Year:</label>
                                        <select id="yearSelect" class="form-select form-select-sm"></select>
                                    </div>
                                </div>
                                <div id="usage_estimate_tenant"></div>
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
	<script type="module" src="../../src/js/energy_cost_graph.js"></script>
	<script type="module" src="../../src/js/energy_usage_graph.js"></script>

	<script>
		async function updateTotals() {
			try {
				const response = await fetch("controller/submeter_receive_data.php"); // fetch data
				const data = await response.json();

				let totalKWh = 0;
				let totalCurrent = 0;
				let totalFrequency = 0;
				let totalPowerFactor = 0;
				let count = 0;

				// Loop through each submeter object
				data.forEach(submeter => {
					totalKWh += parseFloat(submeter.energyKWh) || 0;
					totalCurrent += parseFloat(submeter.current) || 0;
					totalFrequency += parseFloat(submeter.frequency) || 0;
					totalPowerFactor += parseFloat(submeter.powerFactor) || 0;
					count++;
				});

				// Averages for frequency and power factor
				let avgFrequency = count > 0 ? (totalFrequency / count) : 0;
				let avgPowerFactor = count > 0 ? (totalPowerFactor / count) : 0;

				// Update DOM
				document.getElementById("energyKWh").innerText = totalKWh.toFixed(2) + " kWh";
				document.getElementById("current").innerText = totalCurrent.toFixed(2) + " A";
				document.getElementById("frequency").innerText = avgFrequency.toFixed(2) + " Hz";
				document.getElementById("powerFactor").innerText = avgPowerFactor.toFixed(2);

			} catch (error) {
				console.error("Error fetching data:", error);
			}
		}

		// Refresh every 5 seconds
		setInterval(updateTotals, 5000);
		updateTotals();
	</script>
</body>

</html>