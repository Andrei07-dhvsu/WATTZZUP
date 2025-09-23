<?php
//Sign In Controller
include_once 'config/settings-configuration.php';

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
    <title>Verify OTP</title>
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
                            <h4 class="card-title">Verify OTP</h4>
                            <form action="dashboard/user/authentication/user-signup.php" method="POST" class="my-login-validation" novalidate="">
                                <!-- recaptcah token --> <input type="hidden" id="g-token" name="g-token">
                                <!-- csrf token --> <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <div class="form-group">
                                    <label for="otp">OTP</label>
                                    <input id="otp" type="number" inputmode="numeric" autocomplete="one-time-code"  autocapitalize="off" autocorrect="off" placeholder="Enter OTP" tabindex="1" maxlength="6" class="form-control"  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" name="otp" required autofocus>
                                    <div class="invalid-feedback">
                                        OTP is required!
                                    </div>
                                </div>

                                <div class="form-group m-0">
                                    <button type="submit" id="submit" class="btn btn-dark btn-block" name="btn-verify">
                                        Submit
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
    <script>
        // CAPTCHA
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $config->getSKey() ?>', {
                action: 'submit'
            }).then(function(token) {
                document.getElementById("g-token").value = token;
            });
        });

        //numbers only----------------------------------------------------------------------------------------------------->

    </script>
    <!-- SWEET ALERT -->
    <?php include_once 'config/sweetalert.php'; ?>
</body>

</html>