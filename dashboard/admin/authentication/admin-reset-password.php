<?php
require_once 'admin-class.php';

$user = new ADMIN();
$main_url = $user->mainUrl();

if (empty($_GET['id']) || empty($_GET['code'])) {
    $user->redirect('./');
    exit;
}

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
                header("Location: ../../../");
                exit;
            }

            $new_password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            $code = md5(uniqid(rand())); // This will replace the used token
            $new_hash_password = password_hash($new_password, PASSWORD_DEFAULT); // Use password_hash

            if ($new_password != $confirm_password) {
                $_SESSION['status_title'] = "Oops!";
                $_SESSION['status'] = "Passwords do not match. Please try again.";
                $_SESSION['status_code'] = "error";
                $_SESSION['status_timer'] = 100000;
                exit();
            }

            $stmt = $user->runQuery("UPDATE users SET password=:password, tokencode=:token WHERE id=:uid");
            if ($stmt->execute(array(":token" => $code, ":password" => $new_hash_password, ":uid" => $rows['id']))) {
                // Log the reset password
                $activity = "Password reset";
                $user_id = $row['id'];
                $user->logs($activity, $user_id);

                $_SESSION['status_title'] = "Success!";
                $_SESSION['status'] = "Password is updated. Redirecting to Sign in.";
                $_SESSION['status_code'] = "success";
                header("refresh:4;../../../");
                exit;
            } else {
                $_SESSION['status_title'] = "Error!";
                $_SESSION['status'] = "Failed to update the password. Please try again.";
                $_SESSION['status_code'] = "error";
                header("Location: ../../../reset-password");
                exit;
            }
        }
    } else {
        $_SESSION['status_title'] = "Oops!";
        $_SESSION['status'] = "Your token is expired.";
        $_SESSION['status_code'] = "error";
        exit;
    }
}
?>
