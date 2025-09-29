<?php
//Sign In Controller
include_once 'dashboard/user/authentication/user-signin.php';
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
    <title>Sign Up</title>
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
                            <h4 class="card-title">Sign Up</h4>
                            <form action="dashboard/user/authentication/user-signup.php" method="POST" class="my-login-validation" novalidate="">
                                <!-- recaptcah token --> <input type="hidden" id="g-token" name="g-token">
                                <!-- csrf token --> <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input id="first_name" type="text" class="form-control" name="first_name" required autofocus>
                                    <div class="invalid-feedback">
                                        First Name is required!
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="middle_name">Middle Name</label>
                                    <input id="middle_name" type="text" class="form-control" name="middle_name" autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input id="last_name" type="text" class="form-control" name="last_name" required autofocus>
                                    <div class="invalid-feedback">
                                        Last Name is required!
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input id="email" type="email" class="form-control" name="email" required autofocus>
                                    <div class="invalid-feedback">
                                        Email is required!
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="access_key">Access Key</label>
                                    <input id="access_key" type="text" class="form-control" name="access_key" required autofocus>
                                    <div class="invalid-feedback">
                                        Access Key is required (please obtain the access key from the landlord)! 
                                    </div>
                                </div>
                                <div class="form-group m-0">
                                    <button type="submit" id="submit" class="btn btn-dark btn-block" name="btn-signup">
                                        Sign Up
                                    </button>
                                </div>
                            </form>
                        </div>
                        <h1 class="signup">Already have an account?<a href="./"> Click here to sign in!</a></h1>
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
            grecaptcha.execute('<?php echo $config->getSKey() ?>', {
                action: 'submit'
            }).then(function(token) {
                document.getElementById("g-token").value = token;
            });
        });
    </script>
    <!-- SWEET ALERT -->
    <?php include_once 'config/sweetalert.php'; ?>
</body>

</html>