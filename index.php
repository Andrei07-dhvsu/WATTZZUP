<?php
//Sign In Controller
include_once 'dashboard/admin/authentication/admin-signin.php';
//configuration connection
include_once 'config/header.php';
include_once 'config/footer.php';

$config = new SystemConfig();
$header_signin = new HeaderSignin($config);
$footer_signin = new FooterSignin();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $header_signin->getHeaderSignin() ?>
    <title>Sign In</title>
</head>
<body class="my-login-page">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="brand">
						<img src="src/img/<?php echo $config->getSystemLogo() ?>" alt="logo">
					</div>
					<div class="card fat">
						<div class="card-body">
							<h4 class="card-title">Sign In</h4>
							<form action="dashboard/admin/authentication/admin-signin.php" method="POST" class="my-login-validation" novalidate="">
	<!-- recaptcah token -->	<input type="hidden" id="g-token" name="g-token">
	<!-- csrf token -->			<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

								<div class="form-group">
									<label for="email">E-mail</label>
									<input id="email" type="email" class="form-control" name="email" required autofocus>
									<div class="invalid-feedback">
										Email is required!
									</div>
								</div>

								<div class="form-group">
									<label for="password">Password
										<a href="forgot-password" class="float-right">
											Forgot Password?
										</a>
									</label>
									<input id="password" type="password" autocomplete="off" class="form-control" name="password" required data-eye>
								    <div class="invalid-feedback">
								    	Password is required!
							    	</div>
								</div>

								<div class="form-group m-0">
									<button type="submit"  id="submit" class="btn btn-primary btn-block" name="btn-signin">
										Sign In
									</button>
								</div>
							</form>
						</div>
						<h1 class="signup">Don't have an account?<a href="signup"> Click here to sign up!</a></h1>
					</div>
					<footer><?php echo $config->getSystemCopyright() ?></footer>
				</div>
			</div>
		</div>
	</section>
	<?php echo $footer_signin->getFooterSignin() ?>
	<script>
		// CAPTCHA
			grecaptcha.ready(function() {
			grecaptcha.execute('<?php echo $config->getSKey() ?>', {action: 'submit'}).then(function(token) {
				document.getElementById("g-token").value = token;
			});
			});
	</script>
	<!-- SWEET ALERT -->
    <?php include_once 'config/sweetalert.php'; ?>
</body>
</html>