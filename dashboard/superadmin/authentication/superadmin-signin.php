<?php
require_once 'superadmin-class.php';
require_once __DIR__ . '/../../user/authentication/user-class.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$superadmin = new SUPERADMIN();

$site_secret_key = $superadmin->siteSecretKey();

if ($superadmin->isUserLoggedIn() != "") {
    $superadmin->redirect('');
}

if (isset($_POST['btn-signin'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $_SESSION['status_title'] = "Error!";
        $_SESSION['status'] = "Invalid token";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_timer'] = 40000;
        header("Location: ../../../");
        exit;
    }
    unset($_SESSION['csrf_token']);

    // Validate Google reCAPTCHA
    $response = $_POST['g-token'];
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$site_secret_key&response=$response&remoteip=$remoteip";
    $data = file_get_contents($url);
    $row = json_decode($data, true);

    if ($row['success'] == "true") {
        $email = trim($_POST['email']);
        $upass = trim($_POST['password']);

        $stmt = $superadmin->runQuery('SELECT * FROM users WHERE email = :email');
        $stmt->execute(array(
            ":email" => $email,
        ));

        $rowCount = $stmt->rowCount();

        if ($rowCount == 1) {
            $existingData = $stmt->fetch();

            if ($superadmin->login($email, $upass)) {
                $_SESSION['status_title'] = "Hey !";
                $_SESSION['status'] = "Welcome to WattzUp! ";
                $_SESSION['status_code'] = "success";
                $_SESSION['status_timer'] = 10000;
                header("Location: ../private/superadmin/");
                exit();
            } else {
                $_SESSION['status_titlek'] = "Sorry !";
                $_SESSION['status'] = "No account found";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_timer'] = 10000000;
                header("Location: ../../../private/superadmin/");
                exit();
            }
        } else {
            $_SESSION['status_title'] = "Sorry !";
            $_SESSION['status'] = "No account found or your account has been removed!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 10000000;
            header("Location: ../../../private/superadmin/");
            exit();
        }
    } else {
        // Handle invalid reCAPTCHA
        $_SESSION['status_title'] = "Error!";
        $_SESSION['status'] = "Invalid captcha, please try again!";
        $_SESSION['status_code'] = "error";
        $_SESSION['status_timer'] = 40000;
        header("Location: ../../../");
        exit;
    }
}
