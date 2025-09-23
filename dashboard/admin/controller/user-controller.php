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

    public function deleteUser($user_id){
        $status = "disabled";
        $stmt = $this->admin->runQuery('UPDATE users SET account_status=:account_status WHERE id=:id');
        $exec = $stmt->execute(array(
            ":account_status" => $status,
            ":id" => $user_id
        ));

        if ($exec) {
            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'User has been delete';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        }
        else {
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