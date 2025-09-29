<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $header_dashboard->getHeaderDashboard() ?>

	<title>Audit Trail</title>
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
					<h1>Audit Trail</h1>
					<ul class="breadcrumb">
						<li>
							<a class="active" href="./">Home</a>
						</li>
						<li>|</li>
						<li>
							<a href="">Audit Trail</a>
						</li>
					</ul>
				</div>
			</div>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3><i class='bx bxl-blogger'></i> Audit Trail</h3>
					</div>

					<!-- BODY -->
					<section class="data-table">
						<div class="searchBx">
							<input type="input" placeholder="Logs . . ." class="search" name="search_box" id="search_box"><button class="searchBtn"><i class="bx bx-search icon"></i></button>
						</div>

						<div class="table">
							<div id="dynamic_content">
							</div>

					</section>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<?php echo $footer_dashboard->getFooterDashboard() ?>
	<?php include_once '../../config/sweetalert.php'; ?>
	<script>
		//live search---------------------------------------------------------------------------------------//
		$(document).ready(function() {

			load_data(1);

			function load_data(page, query = '') {
				$.ajax({
					url: "tables/logs-table.php",
					method: "POST",
					data: {
						page: page,
						query: query
					},
					success: function(data) {
						$('#dynamic_content').html(data);
					}
				});
			}

			$(document).on('click', '.page-link', function() {
				var page = $(this).data('page_number');
				var query = $('#search_box').val();
				load_data(page, query);
			});

			$('#search_box').keyup(function() {
				var query = $('#search_box').val();
				load_data(1, query);
			});

		});
	</script>
</body>

</html>