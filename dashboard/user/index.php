<?php
include_once 'header.php';

$currentUserId = $_SESSION['userSession'] ?? '';

// ✅ Get logged-in user’s data + associated room data
$stmt = $user->runQuery("
    SELECT 
        u.id AS user_id,
        u.profile,
        u.first_name,
        u.middle_name,
        u.last_name,
        u.sex,
        u.date_of_birth,
        u.age,
        u.civil_status,
        u.phone_number,
        u.email,
        r.id AS room_id,
		r.owner_id,
        r.room_number,
        r.submeter_id,
        r.kwh_limit,
        r.status AS room_status,
        r.updated_at AS room_last_update
    FROM users u
    INNER JOIN rooms r ON FIND_IN_SET(u.id, r.user_id)
    WHERE u.id = :user_id
      AND u.account_status = 'Active'
    LIMIT 1
");
$stmt->execute([':user_id' => $currentUserId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Assign user data
$rooms_user_id           = $data['user_id'] ?? '';
$rooms_user_profile      = $data['profile'] ?? '';
$rooms_user_fname        = $data['first_name'] ?? '';
$rooms_user_mname        = $data['middle_name'] ?? '';
$rooms_user_lname        = $data['last_name'] ?? '';
$rooms_user_fullname     = trim(($rooms_user_lname ? $rooms_user_lname . ', ' : '') . $rooms_user_fname);
$rooms_user_sex          = $data['sex'] ?? '';
$rooms_user_birth_date   = $data['date_of_birth'] ?? '';
$rooms_user_age          = $data['age'] ?? '';
$rooms_user_civil_status = $data['civil_status'] ?? '';
$rooms_user_phone_number = $data['phone_number'] ?? '';
$rooms_user_email        = $data['email'] ?? '';

// ✅ Assign room data
$room_id           = $data['room_id'] ?? '';
$room_owner_id	 	= $data['owner_id'];	
$room_number       = $data['room_number'] ?? '';
$submeter_id       = $data['submeter_id'] ?? '';
$kwh_limit         = $data['kwh_limit'] ?? '';
$room_status       = $data['room_status'] ?? '';
$rooms_last_update = $data['room_last_update'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Room Details</title>
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
							<a href=""><?php echo $room_owner_id?>
</a>
						</li>
					</ul>
				</div>
			</div>
			</div>
			<ul class="dashboard_data">
				<div class="gauge_dashboard">
					<?php if ($submeter_id): ?>
						<div class="status">
							<div class="card arduino">
								<h1>⚡ Total Voltage</h1>
								<div class="sensor-data">
									<span id="voltage">Loading....</span>
								</div>
							</div>
							<div class="card arduino">
								<h1>🔌 Total Current</h1>
								<div class="sensor-data">
									<span id="current">Loading....</span>
								</div>
							</div>
							<div class="card arduino">
								<h1>🔌 Total Power</h1>
								<div class="sensor-data">
									<span id="power">Loading....</span>
								</div>
							</div>
							<div class="card arduino">
								<h1>🔋 Total Energy Consumption</h1>
								<div class="sensor-data">
									<span id="energyKWh">Loading....</span>
								</div>
							</div>
							<div class="card arduino">
								<h1>🎵 Total Frequency</h1>
								<div class="sensor-data">
									<span id="frequency">Loading....</span>
								</div>
							</div>
							<div class="card arduino">
								<h1>📐 Total Power Factor</h1>
								<div class="sensor-data">
									<span id="powerFactor">Loading....</span>
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
					<?php else: ?>
						<div class="status submeter_status">
							<p>No submeter data available for this room.</p>
						</div>
					<?php endif; ?>
				</div>
			</ul>

			<div class="class-modal">
				<div class="modal fade" id="tenantModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg">
						<div class="modal-content">
							<div class="header"></div>
							<div class="modal-header">
								<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-user'></i> Add Tenant</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
							</div>
							<div class="modal-body">
								<section class="data-form-modals">
									<div class="registration">
										<form action="controller/room-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
											<input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
											<div class="col-md-12">
												<label for="tenant_id" class="form-label">Select Tenant<span> *</span></label>
												<select class="form-select form-control" name="tenant_id" autocomplete="off" id="tenant_id" required>
													<option disabled selected value="">Please Select Tenant</option>
													<?php
													// Current room ID
													$current_room_id = $room_id ?? 0;

													// Select active tenants who are NOT assigned to any room (or this room)
													$stmt = $user->runQuery("
                                                        SELECT * FROM users u
                                                        WHERE u.account_status = :account_status
                                                        AND u.user_type = :user_type
                                                        AND u.access_key = :access_key
                                                        AND NOT EXISTS (
                                                            SELECT 1 FROM rooms r
                                                            WHERE r.user_id IS NOT NULL
                                                            AND FIND_IN_SET(u.id, r.user_id) > 0
                                                        )
                                                    ");

													$stmt->execute([
														":account_status" => "active",
														":user_type" => 2, // tenant
														":access_key" => $access_key, // tenant
													]);

													while ($tenant = $stmt->fetch(PDO::FETCH_ASSOC)) {
														$fullname = trim(
															($tenant['last_name'] ? $tenant['last_name'] . ', ' : '') .
																$tenant['first_name'] .
																($tenant['middle_name'] ? ' ' . $tenant['middle_name'] : '')
														);
													?>
														<option value="<?= $tenant['id'] ?>"><?= $fullname ?></option>
													<?php
													}
													?>
												</select>
												<div class="invalid-feedback">
													Please select a tenant.
												</div>
											</div>
											<div class="addBtn">
												<button type="submit" class="btn-dark" name="btn-add-tenant" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
											</div>
										</form>
									</div>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="class-modal">
				<div class="modal fade" id="submeterModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg">
						<div class="modal-content">
							<div class="header"></div>
							<div class="modal-header">
								<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-key'></i> Add Submeter</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
							</div>
							<div class="modal-body">
								<section class="data-form-modals">
									<div class="registration">
										<form action="controller/room-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
											<div class="row gx-5 needs-validation">
												<input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
												<div class="col-md-12">
													<label for="submeter_id" class="form-label">Submeter ID<span> *</span></label>
													<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="submeter_id" id="submeter_id" required>
													<div class="invalid-feedback">
														Please provide a Submeter ID.
													</div>
												</div>
											</div>
											<div class="addBtn">
												<button type="submit" class="btn-dark" name="btn-add-submeterId" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
											</div>
										</form>
									</div>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="class-modal">
				<div class="modal fade" id="addSubTenant" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg">
						<div class="modal-content">
							<div class="header"></div>
							<div class="modal-header">
								<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-user-plus'></i> Add SubTenant</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
							</div>
							<div class="modal-body">
								<section class="data-form-modals">
									<div class="registration">
										<form action="controller/room-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
											<div class="row gx-5 needs-validation">
												<input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
												<input type="hidden" name="tenant_id" value="<?php echo $rooms_user_id; ?>">
												<?php foreach ($ids as $id): ?>
													<input type="hidden" name="existing_user_ids[]" value="<?= $id ?>">
												<?php endforeach; ?>

												<div id="tenant_wrapper" style="padding: 20px;">
													<div class="tenant_row d-flex mb-2">
														<select class="form-select form-control me-2" name="sub_tenant_ids[]">
															<option disabled selected value="">Please Select Tenant</option>
															<?php
															// Current room ID
															$current_room_id = $room_id ?? 0;

															// Select active tenants who are NOT assigned to any room (or this room)
															$stmt = $user->runQuery("
                                                            SELECT * FROM users u
                                                            WHERE u.account_status = :account_status
                                                            AND u.user_type = :user_type
                                                            AND u.access_key = :access_key
                                                            AND NOT EXISTS (
                                                                SELECT 1 FROM rooms r
                                                                WHERE r.user_id IS NOT NULL
                                                                AND FIND_IN_SET(u.id, r.user_id) > 0
                                                            )
                                                        ");
															$stmt->execute([
																":account_status" => "active",
																":user_type" => 2, // tenant
																":access_key" => $access_key, // tenant
															]);

															while ($tenant = $stmt->fetch(PDO::FETCH_ASSOC)) {
																$fullname = trim(
																	($tenant['last_name'] ? $tenant['last_name'] . ', ' : '') .
																		$tenant['first_name'] .
																		($tenant['middle_name'] ? ' ' . $tenant['middle_name'] : '')
																);
															?>
																<option value="<?= $tenant['id'] ?>"><?= $fullname ?></option>
															<?php
															}
															?>
														</select>
														<button type="button" class="btn btn-danger remove_tenant">-</button>
													</div>
												</div>

												<!-- Hidden Template -->
												<div id="tenant_template" class="d-none" style="padding: 20px;">
													<div class="tenant_row d-flex mb-2">
														<select class="form-select form-control me-2" name="sub_tenant_ids[]">
															<option disabled selected value="">Please Select Tenant</option>
															<!-- regenerate same tenant options via PHP -->
															<?php
															// Current room ID
															$current_room_id = $room_id ?? 0;

															// Select active tenants who are NOT assigned to any room (or this room)
															$stmt = $user->runQuery("
                                                            SELECT * FROM users u
                                                            WHERE u.account_status = :account_status
                                                            AND u.user_type = :user_type
                                                            AND u.access_key = :access_key
                                                            AND NOT EXISTS (
                                                                SELECT 1 FROM rooms r
                                                                WHERE r.user_id IS NOT NULL
                                                                AND FIND_IN_SET(u.id, r.user_id) > 0
                                                            )
                                                        ");

															$stmt->execute([
																":account_status" => "active",
																":user_type" => 2, // tenant
																":access_key" => $access_key, // tenant
															]);

															while ($tenant = $stmt->fetch(PDO::FETCH_ASSOC)) {
																$fullname = trim(
																	($tenant['last_name'] ? $tenant['last_name'] . ', ' : '') .
																		$tenant['first_name'] .
																		($tenant['middle_name'] ? ' ' . $tenant['middle_name'] : '')
																);
															?>
																<option value="<?= $tenant['id'] ?>"><?= $fullname ?></option>
															<?php
															}
															?>
														</select>
														<button type="button" class="btn btn-danger remove_tenant">-</button>
													</div>
												</div>
												<div class="addTenantBtn">
													<button type="button" class="btn-success" id="add_tenant" onclick="return IsEmpty(); sexEmpty();"><i class='bx bx-user-plus'></i> Add Field</button>
												</div>

											</div>
											<div class="addBtn">
												<button type="submit" class="btn-dark" name="btn-add-tenant" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add Subtenant</button>
											</div>
										</form>
									</div>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>

		<div id="chartContainer"
			data-roomsubmeter-id="<?= $submeter_id ?>">
		</div>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script type="module" src="../../src/js/submeter_data.js"></script>
	<script type="module" src="../../src/js/tenant_energy_usage_graph.js"></script>
	<script type="module" src="../../src/js/tenant_energy_cost_graph.js"></script>
</body>

</html>