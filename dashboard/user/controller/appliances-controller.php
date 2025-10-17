<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once '../authentication/user-class.php';

class Appliances
{
    private $conn;
    private $user;

    public function __construct()
    {
        $this->user = new USER();
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    // 🔹 Add new appliance
    public function addAppliance($appliance_name, $switch_id, $status, $user_id, $room_id)
    {
        // 1️⃣ Check if the switch_id is already used
        $checkSwitch = $this->user->runQuery('
            SELECT * FROM appliances WHERE switch_id = :switch_id
        ');
        $checkSwitch->execute([":switch_id" => $switch_id]);

        if ($checkSwitch->rowCount() > 0) {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'This switch is already assigned to another appliance.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 4000;
            header('Location: ../smart-switch');
            exit;
        }

        // 2️⃣ Check duplicate appliance name
        $checkDuplicate = $this->user->runQuery('
            SELECT * FROM appliances 
            WHERE appliance_name = :appliance_name 
            AND room_id = :room_id 
            AND user_id = :user_id
        ');
        $checkDuplicate->execute([
            ":appliance_name" => $appliance_name,
            ":room_id"        => $room_id,
            ":user_id"        => $user_id
        ]);

        if ($checkDuplicate->rowCount() > 0) {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'This appliance name is already registered in this room.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 4000;
            header('Location: ../smart-switch');
            exit;
        }

        // 3️⃣ Insert new appliance
        $insert = $this->user->runQuery('
            INSERT INTO appliances (appliance_name, switch_id, status, user_id, room_id)
            VALUES (:appliance_name, :switch_id, :status, :user_id, :room_id)
        ');
        $exec = $insert->execute([
            ":appliance_name" => $appliance_name,
            ":switch_id"      => $switch_id,
            ":status"         => $status,
            ":user_id"        => $user_id,
            ":room_id"        => $room_id
        ]);

        if ($exec) {
            $activity = "Added new appliance: $appliance_name (Switch ID: $switch_id)";
            $this->user->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Appliance successfully added.';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 4000;
        } else {
            $_SESSION['status_title'] = 'Error!';
            $_SESSION['status'] = 'Failed to add appliance.';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 4000;
        }

        header('Location: ../smart-switch');
        exit;
    }

    // 🔹 Update Appliance Status (AJAX)
    public function updateApplianceStatus($id, $status)
    {
        $stmt = $this->user->runQuery("
            UPDATE appliances SET status = :status WHERE id = :id
        ");
        $exec = $stmt->execute([
            ":status" => $status,
            ":id" => $id
        ]);

        if ($exec) {
            // optional: log the action
            $this->user->logs("Appliance ID $id turned $status", $_SESSION['userSession']);
            return true;
        } else {
            return false;
        }
    }

    public function deleteAppliance($appliance_id)
    {
        $stmt = $this->user->runQuery("
            DELETE FROM appliances WHERE id = :id
        ");
        $exec = $stmt->execute([
            ":id" => $appliance_id
        ]);

        if ($exec) {
            $this->user->logs("Deleted appliance ID $appliance_id", $_SESSION['userSession']);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Appliance successfully deleted.';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 4000;
        } else {
            $_SESSION['status_title'] = 'Error!';
            $_SESSION['status'] = 'Failed to delete appliance.';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 4000;
        }

        header('Location: ../smart-switch');
        exit;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

# ==============================
# HANDLE FORM REQUESTS / AJAX
# ==============================

# 🟢 Add Appliance (Form Submit)
if (isset($_POST['btn-add-appliances'])) {
    $appliance_name = trim($_POST['appliance_name']);
    $switch_id      = trim($_POST['switch_id']);
    $status         = "OFF";
    $user_id        = trim($_POST['user_id']);
    $room_id        = trim($_POST['room_id']);

    $applianceData = new Appliances();
    $applianceData->addAppliance($appliance_name, $switch_id, $status, $user_id, $room_id);
}

if (isset($_GET['delete_appliance'])){
    $appliance_id = $_GET['id'];
    $applianceData = new Appliances();
    $applianceData->deleteAppliance($appliance_id);
}

# 🟡 Toggle Appliance (AJAX via fetch)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id'], $input['status'])) {
        $appliance = new Appliances();
        $success = $appliance->updateApplianceStatus($input['id'], $input['status']);
        echo json_encode(['success' => $success]);
        exit;
    }
}
