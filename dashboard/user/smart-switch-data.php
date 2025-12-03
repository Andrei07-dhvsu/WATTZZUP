<?php
include_once 'header.php';

$switchID = $_GET['id'] ?? '';   // ✅ fixed

// Fetch room data
$stmt = $user->runQuery("SELECT * FROM appliances WHERE id=:uid");
$stmt->execute([':uid' => $switchID]);
$switch_data = $stmt->fetch(PDO::FETCH_ASSOC);

$submeter_id = $switch_data['submeter_id'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Smart Switch Data</title>
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
					<h1>Smart Switch Data</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a class="active" href="smart-switch">Smart Switch</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Smart Switch Data</a>
						</li>
					</ul>
				</div>
			</div>
			</div>
			<ul class="dashboard_data">
				<div class="gauge_dashboard">
					<?php if ($submeter_id): ?>
						<div class="status">
							<div class="card arduino" style="background-color: #f2f7ffff;">
								<h1>⚡ Total Voltage</h1>
								<div class="sensor-data">
									<span id="voltage">Loading....</span>
								</div>
							</div>
							<div class="card arduino" style="background-color: #f2f7ffff;">
								<h1>🔌 Total Current</h1>
								<div class="sensor-data">
									<span id="current">Loading....</span>
								</div>
							</div>
							<div class="card arduino" style="background-color: #f2f7ffff;">
								<h1>🔌 Total Power</h1>
								<div class="sensor-data">
									<span id="power">Loading....</span>
								</div>
							</div>
							<div class="card arduino" style="background-color: #f2f7ffff;">
								<h1>🔋 Total Energy Consumption</h1>
								<div class="sensor-data">
									<span id="energyKWh">Loading....</span>
								</div>
							</div>
							<div class="card arduino" style="background-color: #f2f7ffff;">
								<h1>🎵 Total Frequency</h1>
								<div class="sensor-data">
									<span id="frequency">Loading....</span>
								</div>
							</div>
							<div class="card arduino" style="background-color: #f2f7ffff;">
								<h1>📐 Total Power Factor</h1>
								<div class="sensor-data">
									<span id="powerFactor">Loading....</span>
								</div>
							</div>
						</div>
						<div class="gauge">
							<div class="card gauge_card"  style="background-color: #f2f7ffff;">
								<div class="d-flex align-items-center gap-2 mb-3" style="margin-top: 20px;">
									<div class="d-flex align-items-center">
										<label for="yearSelectCost" class="me-2 mb-0">Year:</label>
										<select id="yearSelectCost" class="form-select form-select-sm"></select>
									</div>
								</div>

								<p class="card-title">Energy Cost Graph</p>
								<div id="S1"></div>
							</div>
							<div class="card gauge_card" style="background-color: #f2f7ffff;" >
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
					<?php else: ?>
						<div class="status submeter_status">
							<p>No submeter data available for this room.</p>
						</div>
					<?php endif; ?>
				</div>
			</ul>
		</main>

		<div id="chartContainer"
			data-roomsubmeter-id="<?= $submeter_id ?>">
		</div>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script type="module" src="../../src/js/smart_switch_submeter_data.js"></script>
	<script type="module" src="../../src/js/each_tenant_energy_usage_graph.js"></script>
	<script type="module" src="../../src/js/each_tenant_energy_cost_graph.js"></script>
</body>

</html>