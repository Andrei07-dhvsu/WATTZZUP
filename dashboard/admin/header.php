<?php
require_once 'authentication/admin-class.php';
include_once '../../config/settings-configuration.php';
include_once '../../config/header.php';
include_once '../../config/footer.php';
require_once 'sidebar.php';

$currentPage = basename($_SERVER['PHP_SELF'], ".php"); // Gets the current page name without the extension
$sidebar = new SideBar($config, $currentPage);

$config = new SystemConfig();
$header_dashboard = new HeaderDashboard($config);
$footer_dashboard = new FooterDashboard();
$user = new ADMIN();

if (!$user->isUserLoggedIn()) {
    $user->redirect('../../');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid" => $_SESSION['adminSession']));
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// retrieve profile user and full name
$user_id                = $user_data['id'];
$user_profile           = $user_data['profile'];
$user_fname             = $user_data['first_name'];
$user_mname             = $user_data['middle_name'];
$user_lname             = $user_data['last_name'];
$user_fullname          = $user_data['last_name'] . ", " . $user_data['first_name'];
$user_sex               = $user_data['sex'];
$user_birth_date        = $user_data['date_of_birth'];
$user_age               = $user_data['age'];
$user_civil_status      = $user_data['civil_status'];
$user_phone_number      = $user_data['phone_number'];
$user_email             = $user_data['email'];
$user_last_update       = $user_data['updated_at'];
$user_type             = $user_data['user_type'];

// Get total tenants (users)
$userStmt = $user->runQuery("SELECT COUNT(*) as total_users FROM users WHERE user_type = 2 AND account_status = 'Active'");
$userStmt->execute();
$totalUsers = $userStmt->fetch(PDO::FETCH_ASSOC)['total_users'] ?? 0;

// Get total rooms
$roomStmt = $user->runQuery("SELECT COUNT(*) as total_rooms FROM rooms");
$roomStmt->execute();
$totalRooms = $roomStmt->fetch(PDO::FETCH_ASSOC)['total_rooms'] ?? 0;
