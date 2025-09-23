<?php
require_once 'user-class.php';


class UserController
{
    private $user;
    private $main_url;
    private $smtp_email;
    private $smtp_password;
    private $system_name;
    private $conn;


    public function __construct()
    {
        $this->user = new USER();
        $this->main_url = $this->user->mainUrl();
        $this->smtp_email = $this->user->smtpEmail();
        $this->smtp_password = $this->user->smtpPassword();
        $this->system_name = $this->user->systemName();

        $database = new Database();
        $this->conn = $database->dbConnection();
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function userVerifiy($otp, $email, $csrf_token){

        if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)){
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = "Invalidd CSRF Token.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
            header('Location: ../../../signup');
            exit;
        }

        unset($_SESSION['csrf_token']);
        $stmt = $this->user->runQuery("SELECT * FROM users WHERE email=:email");
        $stmt->execute(array(":email"=>$email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = "Email already taken. Please try another one.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
            header('Location: ../../../signup');
            exit();
        }else{

           $_SESSION['OTP'] = $otp;

            $subject = "OTP VERIFICATION";
            $message = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>OTP Verification</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        margin: 0;
                        padding: 0;
                    }
                    
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 30px;
                        background-color: #ffffff;
                        border-radius: 4px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    
                    h1 {
                        color: #333333;
                        font-size: 24px;
                        margin-bottom: 20px;
                    }
                    
                    p {
                        color: #666666;
                        font-size: 16px;
                        margin-bottom: 10px;
                    }
                    
                    .button {
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: #0088cc;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 4px;
                        font-size: 16px;
                        margin-top: 20px;
                    }
                    
                    .logo {
                        display: block;
                        text-align: center;
                        margin-bottom: 30px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='logo'>
                        <img src='cid:logo' alt='Logo' width='150'>
                    </div>
                    <h1>OTP Verification</h1>
                    <p>Hello, $email</p>
                    <p>Your OTP is: $otp</p>
                    <p>If you didn't request an OTP, please ignore this email.</p>
                    <p>Thank you!</p>
                </div>
            </body>
            </html>";

            $this->user->send_mail($email, $message, $subject, $this->smtp_email, $this->smtp_password, $this->system_name);
            $_SESSION['status_title'] = "Success!";
            $_SESSION['status'] = "Please check the Email to verify the account.";
            $_SESSION['status_code'] = "success";
            $_SESSION['status_timer'] = 40000;
            header('Location: ../../../verify-otp');
            exit();
        }
    }

    public function verifyOtp($first_name, $middle_name, $last_name, $email, $password, $tokencode, $user_type, $otp, $csrf_token) {
        // Check if OTP session is set
        if (!isset($_SESSION['OTP'])) {
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = "OTP session has expired. Please try again.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
            header('Location: ../../../signup');
            exit;
        }
    
        // Verify OTP
        if ($otp == $_SESSION['OTP']) {
            // Clear OTP session
            unset($_SESSION['OTP']);
    
            // Email content and security enhancement
            $subject = "VERIFICATION SUCCESS";
            $message = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Verification SUCCESS</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        margin: 0;
                        padding: 0;
                    }
                    
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 30px;
                        background-color: #ffffff;
                        border-radius: 4px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    
                    h1 {
                        color: #333333;
                        font-size: 24px;
                        margin-bottom: 20px;
                    }
                    
                    p {
                        color: #666666;
                        font-size: 16px;
                        margin-bottom: 10px;
                    }
                    
                    .button {
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: #0088cc;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 4px;
                        font-size: 16px;
                        margin-top: 20px;
                    }
                    
                    .logo {
                        display: block;
                        text-align: center;
                        margin-bottom: 30px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='logo'>
                    <img src='cid:logo' alt='Logo' width='150'>
                    </div>
                    <h1>Welcome</h1>
                    <p>Hello, <strong>$email</strong></p>
                    <p>Welcome to $this->system_name</p>
                    Email:<br />$email <br />
                    Password:<br />$password
                    <p>If you did not sign up for an account, you can safely ignore this email.</p>
                    <p>Thank you!</p>
                </div>
            </body>
            </html>";
    
            // Send verification success email
            $this->user->send_mail($email, $message, $subject, $this->smtp_email, $this->smtp_password, $this->system_name);
            // Proceed with user registration
            $this->userRegistration($first_name, $middle_name, $last_name, $email, $password, $tokencode, $user_type, $csrf_token);
            // Clear session variables for non-verified user data
            unset($_SESSION['not_verify_first_name'], $_SESSION['not_verify_middle_name'], $_SESSION['not_verify_last_name'], 
                  $_SESSION['not_verify_email'], $_SESSION['not_verify_password']);
    
        } else {
            // Invalid OTP case
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = "Invalid OTP, please try again!";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
            header('Location: ../../../signup');
            exit;
        }
    }
    

    public function userRegistration($first_name, $middle_name, $last_name, $email, $password, $tokencode, $user_type, $csrf_token)
    {
        if (!isset($csrf_token) || !hash_equals($_SESSION['csrf_token'], $csrf_token)){
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = "Invalidd CSRF Token.";
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
            header('Location: ../../../signup');
            exit;
        }
        unset($_SESSION['csrf_token']);

        $hash_password = md5($password);

        $stmt = $this->runQuery('INSERT INTO users(first_name, middle_name, last_name, email, password, tokencode, user_type) VALUES(:first_name, :middle_name, :last_name, :email, :password, :tokencode, :user_type)');
        $exec = $stmt->execute(array(
            ":first_name" => $first_name,
            ":middle_name" => $middle_name,
            ":last_name" => $last_name,
            ":email" => $email,
            ":password" => $hash_password,
            ":tokencode" => $tokencode,
            ":user_type" => $user_type,
        ));
        if($exec){
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Successfully Registration';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../../../');
            exit;
        }
        else{
            $_SESSION['status_title'] = "Oops!";
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = "error";
            $_SESSION['status_timer'] = 100000;
            header('Location: ../../../verify-otp');
            exit;
        }
    }
}
if (isset($_POST['btn-signup'])) {

    $csrf_token = trim($_POST['csrf_token']);
    $_SESSION['not_verify_first_name'] = trim($_POST['first_name']);
    $_SESSION['not_verify_middle_name'] = trim($_POST['middle_name']);
    $_SESSION['not_verify_last_name'] = trim($_POST['last_name']);
    $_SESSION['not_verify_email'] = trim($_POST['email']);

    $email = trim($_POST['email']);
    $otp = rand(100000, 999999);


    $verify_user = new UserController();
    $verify_user->userVerifiy($otp, $email, $csrf_token);
}

if (isset($_POST['btn-verify'])) {
    $csrf_token = trim($_POST['csrf_token']);
    $first_name         = $_SESSION['not_verify_first_name'];
    $middle_name        =  $_SESSION['not_verify_middle_name'];
    $last_name          =  $_SESSION['not_verify_last_name'];
    $email              = $_SESSION['not_verify_email'];
    $tokencode          = md5(uniqid(rand()));
    $user_type          = 2;
    $otp                = trim($_POST['otp']);


    $varchar            = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $shuffle            = str_shuffle($varchar);
    $password      = substr($shuffle, 0, 8);

    $verify_otp = new UserController();
    $verify_otp->verifyOtp($first_name, $middle_name, $last_name, $email, $password, $tokencode, $user_type, $otp, $csrf_token);
}