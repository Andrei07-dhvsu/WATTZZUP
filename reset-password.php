<?php
require_once 'dashboard/admin/authentication/admin-class.php';
include_once 'config/settings-configuration.php';
include_once 'config/header.php';
include_once 'config/footer.php';

$config = new SystemConfig();
$header_signin = new HeaderSignin($config);
$footer_signin = new FooterSignin();

$user = new ADMIN();
$main_url = $user->mainUrl();

if (isset($_GET['id']) && isset($_GET['code'])) {
    $id = base64_decode($_GET['id']);
    $code = $_GET['code'];

    $stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid AND tokencode=:token");
    $stmt->execute(array(":uid" => $id, ":token" => $code));
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() == 1) {
        if (isset($_POST['btn-update-password'])) {
            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $_SESSION['status_title'] = "Error!";
                $_SESSION['status'] = "Invalid token";
                $_SESSION['status_code'] = "error";
                header("Location: ./");
                exit;
            }

            $new_password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            $code 				= md5(uniqid(rand()));
            $new_hash_password 	= md5($new_password);

            if ($new_password != $confirm_password) {
                $_SESSION['status_title'] = "Oops!";
                $_SESSION['status'] = "Passwords do not match. Please try again.";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_timer'] = 100000;
                header("Location: " . $_SERVER['REQUEST_URI']); // Redirect back to the same page
                exit();
            }

            $stmt = $user->runQuery("UPDATE users SET password=:password, tokencode=:token WHERE id=:uid");
            $stmt->execute(array(":token"=>$code,":password"=>$new_hash_password,":uid"=>$rows['id']));
            
            $_SESSION['status_title'] = "Success !";
            $_SESSION['status'] = "Password is updated. Redirecting to Sign in.";
            $_SESSION['status_code'] = "success";
            header("refresh:4;./");
        }
    } else {
        $_SESSION['status_title'] = "Oops!";
        $_SESSION['status'] = "Your token is expired.";
        $_SESSION['status_code'] = "error";
        header("Location: ./");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $header_signin->getHeaderSignin() ?>
    <title>Reset Password?</title>
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
                            <h4 class="card-title">Reset Password</h4>
                            <a href="./" class="close"><img src="src/img/caret-right-fill.svg" alt="close-btn" width="24" height="24"></a>
                            <form action="" enctype="multipart/form-data" method="POST" class="my-login-validation" novalidate="">
                                <!-- csrf token -->
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

                                <div class="form-group">
                                    <label for="email">Password</label>
                                    <input id="password" type="password" class="form-control" name="password" autocapitalize="on" autocorrect="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Enter your password" required autofocus data-eye>
                                    <div class="invalid-feedback">
                                        Password is required
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="new-password">Confirm Password</label>
                                    <input id="confirm_password" type="password" class="form-control" name="confirm_password" autocapitalize="on" autocorrect="off" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="Confirm your password" required autofocus data-eye>
                                    <div class="invalid-feedback">
                                        Password is required
                                    </div>
                                    <div class="form-text text-muted">
                                        Make sure your password contains a capital letter, a number, and has a minimum of 8 characters.
                                    </div>
                                </div>

                                <div class="form-group m-0">
                                    <button type="submit" name="btn-update-password" class="btn btn-primary btn-block">
                                        Reset Password
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
