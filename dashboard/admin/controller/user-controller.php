<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/admin-class.php';

class UserManagement
{
    private $conn;
    private $admin;

    public function __construct()
    {
        $this->admin = new ADMIN();


        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

public function deleteUser($user_id)
{
    // 1. Disable the user account
    $status = "disabled";
    $stmt = $this->admin->runQuery('UPDATE users SET account_status = :account_status WHERE id = :id');
    $exec = $stmt->execute([
        ":account_status" => $status,
        ":id" => $user_id
    ]);

    if ($exec) {
        // 2. Remove the user from any assigned room(s)
        $stmtRoom = $this->admin->runQuery('UPDATE rooms SET user_id = NULL, status = :status WHERE user_id = :user_id');
        $stmtRoom->execute([
            ":status" => 'vacant',
            ":user_id" => $user_id
        ]);

        // 3. Log activity
        $activity = "User has been deleted and removed from assigned room(s)";
        $admin_id = $_SESSION['adminSession'];
        $this->admin->logs($activity, $admin_id);

        // 4. Success response
        $_SESSION['status_title'] = 'Success!';
        $_SESSION['status'] = 'User has been deleted and removed from assigned room(s)';
        $_SESSION['status_code'] = 'success';
        $_SESSION['status_timer'] = 40000;
    } else {
        $_SESSION['status_title'] = 'Oops!';
        $_SESSION['status'] = 'Something went wrong, please try again!';
        $_SESSION['status_code'] = 'error';
        $_SESSION['status_timer'] = 100000;
    }

    header('Location: ../user-management');
    exit;
}

}


if (isset($_GET['disabled_user'])) {
    $user_id = $_GET["user_id"];

    $deleteUser = new UserManagement();
    $deleteUser->deleteUser($user_id);
}