<?php
include_once 'header.php';
require_once 'fetch.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Threshold</title>
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
					<h1>Thresholds</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Thresholds</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxs-cog'></i> Configuration of Thresholds</h3>
					</div>
					<!-- BODY -->
					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
							<form action="controller/sensor-controller.php" method="POST" class="row gx-5 needs-validation" name="form" novalidate style="overflow: hidden;" id="sensorForm1">
								<div class="row gx-5 needs-validation">
									<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;">
										<i class='bx bxs-cog'></i> Sensor 1 Configuration
									</label>
									<input type="hidden" name="sensorId" value="1">

									<div class="col-md-6">
										<label for="mode" class="form-label">Mode<span> *</span></label>
										<select class="form-select form-control" name="mode" maxlength="6" autocomplete="off" id="mode" required>
											<option selected value="<?php echo $sensorMode1 ?>"><?php echo $sensorMode1 ?></option>
											<option value="AUTOMATIC">AUTOMATIC</option>
											<option value="SCHEDULE">SCHEDULE</option>
											<option value="OFF">OFF</option>
										</select>
										<div class="invalid-feedback">
											Please select Mode.
										</div>
									</div>

									<div class="col-md-6">
										<label for="plant_name" class="form-label">Plant Name<span> *</span></label>
										<select class="form-select form-control" name="plant_name" autocomplete="off" id="plant_name" required>
											<option selected value="<?php echo$plantId1 ?>"><?php echo$plantName1 ?></option>
											<?php
											$stmt = $user->runQuery("SELECT * FROM plants WHERE status = :status");
											$stmt->execute(array(":status" => "available"));
											while ($plant_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
											?>
												<option value="<?php echo $plant_data['id']; ?>"><?php echo $plant_data['plant_name']; ?></option>
											<?php
											}
											?>
										</select>
										<div class="invalid-feedback">
											Please select a plant.
										</div>
									</div>

									<div class="col-md-6">
										<label for="water_amount_am" class="form-label">Water Amount (mm)<span> (for scheduled mode in AM)*</span></label>
										<select class="form-select form-control" name="water_amount_am" id="water_amount_am" required>
											<option selected value="<?php echo$waterAmountAM1 ?>"><?php echo$waterAmountAM1 ?> mL</option>
											<!-- Generating options from 5 mm to 200 mm in increments of 5 mm -->
											<?php
											for ($i = 5; $i <= 2000; $i += 5) {
												echo "<option value='$i'>{$i} mL</option>";
											}
											?>
										</select>
										<div class="invalid-feedback">
											Please select the amount of water for AM schedule.
										</div>
									</div>

									<div class="col-md-6">
										<label for="start_time_am" class="form-label">Select Time for AM <span> (for scheduled mode  in AM)*</span></label>
										<input type="time" step="1" class="form-control" name="start_time_am" id="start_time_am" value="<?php echo $start_time_am1 ?>" required>
										<div class="invalid-feedback">
											Please select a time for AM.
										</div>
									</div>

									<div class="col-md-6">
										<label for="water_amount_pm" class="form-label">Water Amount (mm)<span> (for scheduled mode in PM)*</span></label>
										<select class="form-select form-control" name="water_amount_pm" id="water_amount_pm" required>
											<option selected value="<?php echo$waterAmountPM1 ?>"><?php echo$waterAmountPM1 ?> mL</option>
											<!-- Generating options from 5 mm to 200 mm in increments of 5 mm -->
											<?php
											for ($i = 5; $i <= 2000; $i += 5) {
												echo "<option value='$i'>{$i} mL</option>";
											}
											?>
										</select>
										<div class="invalid-feedback">
											Please select the amount of water for PM schedule.
										</div>
									</div>

									<div class="col-md-6">
										<label for="start_time_pm" class="form-label">Select Time for PM <span> (for scheduled mode  in PM)*</span></label>
										<input type="time" step="1" class="form-control" name="start_time_pm" id="start_time_pm" value="<?php echo $start_time_pm1 ?>" required>
										<div class="invalid-feedback">
											Please select a time for PM.
										</div>
									</div>

									<label for="start_time" class="form-label">Select Day to Water the Plant <span> (for scheduled mode)*</span></label>

                                    <ul class="other-option clearfix">
                                        <?php
                                        // Loop through all amenities and create checkboxes
                                        foreach ($all_days1 as $days) {
                                            $daysID = $days['id'];
                                            $days_names = $days['day'];
                                            $isChecked = in_array($daysID, $selected_days1) ? 'checked' : '';
                                        ?>
                                            <li>
                                                <div class="radio-box">
                                                    <input type="checkbox" name="days[]" value="<?php echo $daysID; ?>" id="days1<?php echo $daysID; ?>" <?php echo $isChecked; ?>>
                                                    <label for="days1<?php echo $daysID; ?>"><?php echo $days_names; ?></label>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
									<div class="invalid-feedback" id="checkboxError1" style="color:red; display:none;">
										Please select at least one day.
									</div>
								</div>

								<div class="addBtn">
									<button type="submit" class="btn-success" name="btn-update-thresholds" id="btn-update">Set</button>
								</div>
							</form>
						</div>
					</section>

					<!-- System Logo  -->

					<section class="data-form">
						<div class="header"></div>
						<div class="registration">
						<form action="controller/sensor-controller.php" method="POST" class="row gx-5 needs-validation" name="form" novalidate style="overflow: hidden;" id="sensorForm2">
								<div class="row gx-5 needs-validation">
									<label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 2rem; font-size: 1rem; font-weight: bold;">
										<i class='bx bxs-cog'></i> Sensor 2 Configuration
									</label>
									<input type="hidden" name="sensorId" value="2">

									<div class="col-md-6">
										<label for="mode" class="form-label">Mode<span> *</span></label>
										<select class="form-select form-control" name="mode" maxlength="6" autocomplete="off" id="mode" required>
											<option selected value="<?php echo $sensorMode2 ?>"><?php echo $sensorMode2 ?></option>
											<option value="AUTOMATIC">AUTOMATIC</option>
											<option value="SCHEDULE">SCHEDULE</option>
											<option value="OFF">OFF</option>
										</select>
										<div class="invalid-feedback">
											Please select Mode.
										</div>
									</div>

									<div class="col-md-6">
										<label for="plant_name" class="form-label">Plant Name<span> *</span></label>
										<select class="form-select form-control" name="plant_name" autocomplete="off" id="plant_name" values="" required>
											<option selected value="<?php echo$plantId2 ?>"><?php echo$plantName2 ?></option>
											<?php
											$stmt = $user->runQuery("SELECT * FROM plants WHERE status = :status");
											$stmt->execute(array(":status" => "available"));
											while ($plant_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
											?>
											
												<option value="<?php echo $plant_data['id']; ?>"><?php echo $plant_data['plant_name']; ?></option>
											<?php
											}
											?>
										</select>
										<div class="invalid-feedback">
											Please select a plant.
										</div>
									</div>

									<div class="col-md-6">
										<label for="water_amount_am" class="form-label">Water Amount (mm)<span> (for scheduled mode in AM)*</span></label>
										<select class="form-select form-control" name="water_amount_am" id="water_amount_am" required>
											<option selected value="<?php echo$waterAmountAM2 ?>"><?php echo$waterAmountAM2 ?> mL</option>
											<!-- Generating options from 5 mm to 200 mm in increments of 5 mm -->
											<?php
											for ($i = 5; $i <= 2000; $i += 5) {
												echo "<option value='$i'>{$i} mL</option>";
											}
											?>
										</select>
										<div class="invalid-feedback">
											Please select the amount of water for AM schedule.
										</div>
									</div>

									<div class="col-md-6">
										<label for="start_time_am" class="form-label">Select Time for AM <span> (for scheduled mode  in AM)*</span></label>
										<input type="time" step="1" class="form-control" name="start_time_am" id="start_time_am" value="<?php echo $start_time_am2 ?>" required>
										<div class="invalid-feedback">
											Please select a time for AM.
										</div>
									</div>

									<div class="col-md-6">
										<label for="water_amount_pm" class="form-label">Water Amount (mm)<span> (for scheduled mode in PM)*</span></label>
										<select class="form-select form-control" name="water_amount_pm" id="water_amount_pm" required>
											<option selected value="<?php echo$waterAmountPM2 ?>"><?php echo$waterAmountPM2 ?> mL</option>
											<!-- Generating options from 5 mm to 200 mm in increments of 5 mm -->
											<?php
											for ($i = 5; $i <= 2000; $i += 5) {
												echo "<option value='$i'>{$i} mL</option>";
											}
											?>
										</select>
										<div class="invalid-feedback">
											Please select the amount of water for PM schedule.
										</div>
									</div>

									<div class="col-md-6">
										<label for="start_time_pm" class="form-label">Select Time for PM <span> (for scheduled mode  in PM)*</span></label>
										<input type="time" step="1" class="form-control" name="start_time_pm" id="start_time_pm" value="<?php echo $start_time_pm2 ?>" required>
										<div class="invalid-feedback">
											Please select a time for PM.
										</div>
									</div>

									<label for="start_time" class="form-label">Select Day to Water the Plant <span> (for scheduled mode)*</span></label>

                                    <ul class="other-option clearfix">
                                        <?php
                                        // Loop through all amenities and create checkboxes
                                        foreach ($all_days2 as $days) {
                                            $daysID = $days['id'];
                                            $days_names = $days['day'];
                                            $isChecked = in_array($daysID, $selected_days2) ? 'checked' : '';
                                        ?>
                                            <li>
                                                <div class="radio-box">
                                                    <input type="checkbox" name="days[]" value="<?php echo $daysID; ?>" id="days2<?php echo $daysID; ?>" <?php echo $isChecked; ?>>
                                                    <label for="days2<?php echo $daysID; ?>"><?php echo $days_names; ?></label>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
									<div class="invalid-feedback" id="checkboxError2" style="color:red; display:none;">
										Please select at least one day.
									</div>
								</div>

								<div class="addBtn">
									<button type="submit" class="btn-success" name="btn-update-thresholds" id="btn-update">Set</button>
								</div>
							</form>
						</div>
					</section>
				</div>
			</div>
			</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script>
		document.getElementById('sensorForm1').addEventListener('submit', function(event) {
			// Check if at least one checkbox is checked
			const checkboxes = document.querySelectorAll('input[name="days[]"]');
			const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

			if (!isChecked) {
				// Prevent form submission
				event.preventDefault();
				// Show error message
				document.getElementById('checkboxError1').style.display = 'block';
			} else {
				// Hide error message
				document.getElementById('checkboxError1').style.display = 'none';
			}
		});

		document.getElementById('sensorForm2').addEventListener('submit', function(event) {
			// Check if at least one checkbox is checked
			const checkboxes = document.querySelectorAll('input[name="days[]"]');
			const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

			if (!isChecked) {
				// Prevent form submission
				event.preventDefault();
				// Show error message
				document.getElementById('checkboxError2').style.display = 'block';
			} else {
				// Hide error message
				document.getElementById('checkboxError2').style.display = 'none';
			}
		});
	</script>

</body>

</html>