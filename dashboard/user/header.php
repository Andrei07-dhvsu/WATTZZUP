<?php
require_once 'authentication/user-class.php';
include_once '../../config/settings-configuration.php';
include_once '../../config/header.php';
include_once '../../config/footer.php';
require_once 'sidebar.php';

$currentPage = basename($_SERVER['PHP_SELF'], ".php"); // Gets the current page name without the extension
$sidebar = new SideBar($config, $currentPage);

$config = new SystemConfig();
$header_dashboard = new HeaderDashboard($config);
$footer_dashboard = new FooterDashboard();
$user = new USER();

if(!$user->isUserLoggedIn())
{
 $user->redirect('../../');
}

// retrieve user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
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


$currentUserId = $_SESSION['userSession'] ?? '';

// ✅ Get logged-in user’s data + associated room data
$stmt = $user->runQuery("
    SELECT 
        u.id AS user_id,
        u.profile,
        u.first_name,
        u.middle_name,
        u.last_name,
        u.sex,
        u.date_of_birth,
        u.age,
        u.civil_status,
        u.phone_number,
        u.email,
        r.id AS room_id,
		r.owner_id,
        r.room_number,
        r.submeter_id,
        r.kwh_limit,
        r.status AS room_status,
        r.updated_at AS room_last_update
    FROM users u
    INNER JOIN rooms r ON FIND_IN_SET(u.id, r.user_id)
    WHERE u.id = :user_id
      AND u.account_status = 'Active'
    LIMIT 1
");
$stmt->execute([':user_id' => $currentUserId]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Assign user data
$rooms_user_id           = $data['user_id'] ?? '';
$rooms_user_profile      = $data['profile'] ?? '';
$rooms_user_fname        = $data['first_name'] ?? '';
$rooms_user_mname        = $data['middle_name'] ?? '';
$rooms_user_lname        = $data['last_name'] ?? '';
$rooms_user_fullname     = trim(($rooms_user_lname ? $rooms_user_lname . ', ' : '') . $rooms_user_fname);
$rooms_user_sex          = $data['sex'] ?? '';
$rooms_user_birth_date   = $data['date_of_birth'] ?? '';
$rooms_user_age          = $data['age'] ?? '';
$rooms_user_civil_status = $data['civil_status'] ?? '';
$rooms_user_phone_number = $data['phone_number'] ?? '';
$rooms_user_email        = $data['email'] ?? '';

// ✅ Assign room data
$room_id           = $data['room_id'] ?? '';
$room_owner_id	 	= $data['owner_id'];	
$room_number       = $data['room_number'] ?? '';
$submeter_id       = $data['submeter_id'] ?? '';
$kwh_limit         = $data['kwh_limit'] ?? '';
$room_status       = $data['room_status'] ?? '';
$rooms_last_update = $data['room_last_update'] ?? '';

// $stmt = $user->runQuery("SELECT * FROM appliances WHERE user_id=:user_id");
// $stmt->execute(array(":user_id"=>$_SESSION['userSession']));
// $user_data = $stmt->fetch(PDO::FETCH_ASSOC);