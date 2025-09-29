<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/superadmin-class.php';


class Room
{
    private $conn;
    private $superadmin;

    public function __construct()
    {
        $this->superadmin = new SUPERADMIN();


        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function addRoomNumber($RoomNumber)
    {
        // Check if room number already exists
        $stmt = $this->superadmin->runQuery('SELECT COUNT(*) FROM rooms WHERE room_number = :room_number');
        $stmt->execute(array(":room_number" => $RoomNumber));
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Room number already exists!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../energy-monitoring');
            exit;
        }

        $stmt = $this->superadmin->runQuery('INSERT INTO rooms (room_number, owner_id) VALUES (:room_number, :owner_id)');
        $exec = $stmt->execute(array(
            ":room_number" => $RoomNumber,
            ":owner_id" => $_SESSION['superadminSession'],
        ));

        if ($exec) {
            $activity = "New Room has been added ($RoomNumber)";
            $user_id = $_SESSION['superadminSession'];
            $this->superadmin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'New Room has been added';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../energy-monitoring');
        exit;
    }

    public function addTenantInRoom($room_id, $tenant_id)
    {
        // Check if the tenant is already assigned to another room
        $stmt = $this->runQuery('SELECT id FROM rooms WHERE user_id = :user_id AND id != :room_id');
        $stmt->execute(array(
            ":user_id" => $tenant_id,
            ":room_id" => $room_id
        ));
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $_SESSION['status_title'] = 'Duplicate!';
            $_SESSION['status'] = 'This tenant is already assigned to another room.';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../room-details');
            exit;
        }

        $stmt = $this->runQuery('UPDATE rooms SET user_id = :user_id , status = :status WHERE id = :id');
        $exec = $stmt->execute(array(
            ":user_id" => $tenant_id,
            ":id" => $room_id,
            ":status" => 'occupied'
        ));

        if ($exec) {
            $activity = "New Tenant has been added to room (ID: $room_id)";
            $user_id = $_SESSION['superadminSession'];
            $this->superadmin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'New Tenant has been added to the room';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../room-details');
        exit;
    }

    public function addSubmeterId($room_id, $submeter_id){
        // Check if the submeter_id is already assigned to another room
        $stmt = $this->runQuery('SELECT id FROM rooms WHERE submeter_id = :submeter_id AND id != :room_id');
        $stmt->execute(array(
            ":submeter_id" => $submeter_id,
            ":room_id" => $room_id
        ));
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $_SESSION['status_title'] = 'Duplicate!';
            $_SESSION['status'] = 'This Submeter ID is already assigned to another room.';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../room-details');
            exit;
        }

        // Fetch current submeter_id for the room
        $stmt = $this->runQuery('SELECT submeter_id FROM rooms WHERE id = :id');
        $stmt->execute(array(":id" => $room_id));
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($current && $current['submeter_id'] == $submeter_id) {
            $_SESSION['status_title'] = 'No Change!';
            $_SESSION['status'] = 'The Submeter ID is already assigned to this room.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../room-details');
            exit;
        }

        $stmt = $this->runQuery('UPDATE rooms SET submeter_id = :submeter_id WHERE id = :id');
        $exec = $stmt->execute(array(
            ":submeter_id" => $submeter_id,
            ":id" => $room_id
        ));

        if ($exec) {
            $activity = "New Submeter ID has been added to room (ID: $room_id)";
            $user_id = $_SESSION['superadminSession'];
            $this->superadmin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'New Submeter ID has been added to the room';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../room-details');
        exit;
    }
    public function deleteTenant($room_id)
    {
        // Remove the user from the room by setting owner_id to NULL
        $stmt = $this->runQuery('UPDATE rooms SET user_id = NULL, status = :status WHERE id = :id');
        $exec = $stmt->execute(array(
            ":id" => $room_id,
            ":status" => 'vacant'
        ));

        if ($exec) {
            $activity = "User has been removed from room (ID: $room_id)";
            $user_id = $_SESSION['superadminSession'];
            $this->superadmin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'User has been removed from the room';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../energy-monitoring');
        exit;
    }
}


if (isset($_POST['btn-add-room'])) {
    $RoomNumber = trim($_POST['room_number']);


    $addRoomNumber = new Room();
    $addRoomNumber->addRoomNumber($RoomNumber);
}

if (isset($_GET['delete_tenant'])) {
    $room_id = $_GET["id"];

    $deleteTenant = new Room();
    $deleteTenant->deleteTenant($room_id);
}

if (isset($_POST['btn-add-tenant'])) {
    $room_id = trim($_POST['room_id']);
    $tenant_id = trim($_POST['tenant_id']);

    $addTenantInRoom = new Room();
    $addTenantInRoom->addTenantInRoom($room_id, $tenant_id);
}

if (isset($_POST['btn-add-submeterId'])) {
    $room_id = trim($_POST['room_id']);
    $submeter_id = trim($_POST['submeter_id']);

    $addSubmeterId = new Room();
    $addSubmeterId->addSubmeterId($room_id, $submeter_id);
}
