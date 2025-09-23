<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/user-class.php';


class ProfileSettings
{
    private $conn;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
        $this->user = new USER();

    }
    // Update user profile
    public function updateProfile($user_id, $first_name, $middle_name, $last_name, $sex, $date_of_birth, $age, $civil_status)
    {
        // Retrieve current user profile data
        $stmt = $this->runQuery('SELECT * FROM users WHERE id=:id');
        $stmt->execute(array(":id" => $user_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Compare the current user profile with the new data to check if changes have been made
        if (
            $row['first_name'] == $first_name &&
            $row['middle_name'] == $middle_name &&
            $row['last_name'] == $last_name &&
            $row['sex'] == $sex &&
            $row['date_of_birth'] == $date_of_birth &&
            $row['age'] == $age &&
            $row['civil_status'] == $civil_status
        ) {
            // No changes have been made
            $_SESSION['status_title'] = 'Oopss!';
            $_SESSION['status'] = 'No changes have been made to your profile.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;

            header('Location: ../profile');
            exit;
        }

        // Check if middle name is empty and set it to null if it is
        if (empty($middle_name)) {
            $middle_name = null;
        }
        if (empty($sex)) {
            $sex = null;
        }
        if (empty($date_of_birth)) {
            $date_of_birth = null;
        }
        if (empty($age)) {
            $age = null;
        }
        if (empty($civil_status)) {
            $civil_status = null;
        }

        // Update user profile data in the database
        $stmt = $this->runQuery('UPDATE users SET first_name=:first_name, middle_name=:middle_name, last_name=:last_name, sex=:sex, date_of_birth=:date_of_birth, age=:age, civil_status=:civil_status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"            => $user_id,
            ":first_name"   => $first_name,
            ":middle_name"  => $middle_name,
            ":last_name"    => $last_name,
            ":sex"          => $sex,
            ":date_of_birth" => $date_of_birth,
            ":age"          => $age,
            ":civil_status" => $civil_status
        ));

        // Set status message based on success or failure of the database update
        if ($exec) {
            // Log activity
            $activity = "Profile information is updated";
            $user_id = $_SESSION['userSession'];
            $this->user->logs($activity, $user_id);

            // Update successful
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Profile successfully updated';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {

            // Update failed
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        // Redirect to the user's profile page
        header('Location: ../profile');
        exit;
    }

    //update user avatar
    public function updateAvatar($user_id, $avatar)
    {
        $folder = "../../../src/img/" . basename($avatar);
        chmod($folder, 0755);
        $stmt = $this->runQuery('UPDATE users SET profile=:profile WHERE id=:id');
        $exec = $stmt->execute(array(
            ":id"        => $user_id,
            ":profile"  => $avatar,
        ));

        if ($exec && move_uploaded_file($_FILES['avatar']['tmp_name'], $folder)) {

            $activity = "Avatar is updated";
            $user_id = $_SESSION['userSession'];
            $this->user->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Avatar successfully updated';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../profile');
    }
    //update user password
    public function updatePassword($user_id, $old_password, $new_password, $confirm_password)
    {
        $new = md5($new_password);
        $old = md5($old_password);

        // Check if old password is correct
        $stmt = $this->runQuery("SELECT * FROM users WHERE id=:id");
        $stmt->bindParam(":id", $user_id);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_password = $user_data["password"];

        if ($current_password == $old) {

            // check if new password match with confirm pasword
            if ($new_password == $confirm_password) {
                $stmt = $this->runQuery('UPDATE users SET password=:password WHERE id=:id');
                $exec = $stmt->execute(array(
                    ":id"         => $user_id,
                    ":password"  => $new,
                ));

                if ($exec) {
                    $activity = "Password has been update";
                    $user_id = $_SESSION['userSession'];
                    $this->user->logs($activity, $user_id);
        
                    $_SESSION['status_title'] = 'Success!';
                    $_SESSION['status'] = 'Password successfully changed';
                    $_SESSION['status_code'] = 'success';
                    $_SESSION['status_timer'] = 40000;
                } else {
                    $_SESSION['status_title'] = 'Oops!';
                    $_SESSION['status'] = 'Something went wrong, please try again!';
                    $_SESSION['status_code'] = 'error';
                    $_SESSION['status_timer'] = 100000;
                }
                header('Location: ../profile');
            } else {
                $_SESSION['status_title'] = 'Sorry!';
                $_SESSION['status'] = 'New password and Confirm password did not match, Please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 1000000;
                header('Location: ../profile');
            }
        } else {
            $_SESSION['status_title'] = 'Sorry!';
            $_SESSION['status'] = 'Incorrect old password, Please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 1000000;
            header('Location: ../profile');
        }
    }

    //update avatar to default
    public function updateAvatarToDefault($user_id)
    {
        $stmt = $this->runQuery('UPDATE users SET profile=:profile WHERE id=:id');
        $exec = $stmt->execute(array(
            ":profile"  => "profile.png",
            ":id"        => $user_id
        ));

        if ($exec) {
            $activity = "Avatar updated to default";
            $user_id = $_SESSION['userSession'];
            $this->user->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Avatar successfully updated to default';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../profile');
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

// Check if the form has been submitted for updating the profile
if (isset($_POST['btn-update-profile'])) {
    $user_id        = $_GET["id"];
    $first_name     = trim($_POST['first_name']);
    $middle_name    = trim($_POST['middle_name']);
    $last_name      = trim($_POST['last_name']);
    $sex            = trim($_POST['sex']);
    $date_of_birth  = trim($_POST['date_of_birth']);
    $age  = trim($_POST['age']);
    $civil_status  = trim($_POST['civil_status']);

    $ProfileSettings = new ProfileSettings();
    $ProfileSettings->updateProfile($user_id, $first_name, $middle_name, $last_name, $sex, $date_of_birth, $age, $civil_status);
}

if (isset($_POST['btn-update-avatar'])) {
    $user_id    = $_GET["id"];
    $avatar     = $_FILES['avatar']['name'];

    $ProfileSettings = new ProfileSettings();
    $ProfileSettings->updateAvatar($user_id, $avatar);
}

if (isset($_POST['btn-update-password'])) {
    $user_id            = $_GET["id"];
    $old_password       = trim($_POST['old_password']);
    $new_password       = trim($_POST['new_password']);
    $confirm_password   = trim($_POST['confirm_password']);

    $ProfileSettings = new ProfileSettings();
    $ProfileSettings->updatePassword($user_id, $old_password, $new_password, $confirm_password);
}

if (isset($_GET['delete_avatar'])) {
    $user_id = $_GET["id"];

    $ProfileSettings = new ProfileSettings();
    $ProfileSettings->updateAvatarToDefault($user_id);
}
