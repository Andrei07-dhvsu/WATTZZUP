<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>
	<link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
	<title>Energy Monitoring</title>
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
					<h1>Energy Monitoring</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Energy Monitoring</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="modal-button">
				<button type="button" data-bs-toggle="modal" data-bs-target="#roomsModal" class="btn-dark"><i class='bx bxs-plus-circle'></i> Add Room's</button>
			</div>

			<ul class="dashboard_data">
				<div class="gauge_dashboard">
					<div class="room_status">
						<?php
						$stmt = $user->runQuery("SELECT * FROM rooms WHERE owner_id=:owner_id ORDER BY id ASC");
						$stmt->execute([':owner_id' => $_SESSION['superadminSession']]);

						if ($stmt->rowCount() >= 1) {
							while ($rooms_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
								extract($rooms_data);

								// Fetch tenant name from users table using user_id
								$tenant_name = 'N/A';
								if (!empty($user_id)) {
									$user_stmt = $user->runQuery("SELECT first_name FROM users WHERE id = :id LIMIT 1");
									$user_stmt->execute([':id' => $user_id]);
									if ($user_stmt->rowCount() == 1) {
										$user_row = $user_stmt->fetch(PDO::FETCH_ASSOC);
										$tenant_name = $user_row['first_name'];
									}
								}
						?>
								<div class="card2 arduino">
									<h1><?php echo htmlspecialchars($room_number); ?></h1>
									<div class="sensor-data">
										<span class="tenant_name">
											Tenant: <?php echo htmlspecialchars($tenant_name); ?>
										</span>
										<span class="room_status <?php echo ($status == 'Occupied') ? 'occupied' : (($status == 'Vacant') ? 'vacant' : 'unknown'); ?>">
											Status: <?php echo !empty($status) ? htmlspecialchars($status) : 'Unknown'; ?>
										</span>
									</div>
									<div class="more-info">
										<button type="button" onclick="setSessionValues(<?php echo $rooms_data['id']; ?>)" class="btn-dark">More Info <i class='bx bx-right-arrow-alt'></i></button>
									</div>
								</div>
							<?php
							}
						} else {
							?>
							<div class="card arduino">
								<h1>No Rooms Found</h1>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</ul>

			<div class="class-modal">
				<div class="modal fade" id="roomsModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
					<div class="modal-dialog modal-dialog-centered modal-lg">
						<div class="modal-content">
							<div class="header"></div>
							<div class="modal-header">
								<h5 class="modal-title" id="classModalLabel"><i class='bx bxs-building'></i> Add Room</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
							</div>
							<div class="modal-body">
								<section class="data-form-modals">
									<div class="registration">
										<form action="controller/room-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
											<div class="row gx-5 needs-validation">
												<div class="col-md-12">
													<label for="room_number" class="form-label">Room Number<span> *</span></label>
													<input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="room_number" id="room_number" required pattern="^RM\d{3}$" title="Format: RM followed by 3 digits, e.g., RM104">
													<div class="invalid-feedback">
														Please provide a Room Number in the format RM followed by 3 digits (e.g., RM104).
													</div>
												</div>

											</div>
											<div class="addBtn">
												<button type="submit" class="btn-dark" name="btn-add-room" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
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
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script src="../../src/js/gauge.js"></script>
	<script>
		function setSessionValues(roomId) {
			fetch('room-details.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: 'room_id=' + encodeURIComponent(roomId),
				})
				.then(response => {
					window.location.href = 'room-details';
				})
				.catch(error => {
					console.error('Error:', error);
				});
		}
	</script>
</body>

</html>