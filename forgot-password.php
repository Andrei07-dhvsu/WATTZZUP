<?php
include_once 'dashboard/admin/authentication/admin-forgot-password.php';
include_once 'config/settings-configuration.php';
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
    <title>Forgot Password?</title>
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
							<h4 class="card-title">Forgot Password</h4>
                            <a href="./" class="close"><img src="src/img/caret-right-fill.svg" alt="close-btn" width="24" height="24"></a>
							<form action="dashboard/admin/authentication/admin-forgot-password.php" enctype="multipart/form-data"  method="POST" class="my-login-validation" novalidate="">
         <!-- csrf token -->	<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                                <div class="form-group">
									<label for="email">E-Mail Address</label>
									<input id="email" type="email" class="form-control" name="email" value="" required autofocus>
									<div class="invalid-feedback">
										Email is required!
									</div>
									<div class="form-text text-muted">
										By clicking "Send" we will send a password reset link to your email.
									</div>
								</div>

								<div class="form-group m-0">
									<button type="submit" name="btn-forgot-password" class="btn btn-dark btn-block">
										Send
									</button>
								</div>

							</form>
						</div>
					</div>
					<footer><?php echo $config->getSystemCopyright() ?></footer>
				</div>
			</div>
		</div>
	</section>
    <?php echo $footer_signin->getFooterSignin() ?>
	<!-- SWEET ALERT -->
    <?php include_once 'config/sweetalert.php'; ?>
</body>
</html>