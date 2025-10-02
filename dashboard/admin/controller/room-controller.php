<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/admin-class.php';


class Room
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

    public function addRoomNumber($RoomNumber)
    {
        // Check if room number already exists
        $stmt = $this->admin->runQuery('SELECT COUNT(*) FROM rooms WHERE room_number = :room_number and owner_id = :owner_id');
        $stmt->execute(array(":room_number" => $RoomNumber, ":owner_id" => $_SESSION['adminSession']));
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Room number already exists!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../energy-monitoring');
            exit;
        }

        $stmt = $this->admin->runQuery('INSERT INTO rooms (room_number, owner_id) VALUES (:room_number, :owner_id)');
        $exec = $stmt->execute(array(
            ":room_number" => $RoomNumber,
            ":owner_id" => $_SESSION['adminSession'],
        ));

        if ($exec) {
            $activity = "New Room has been added ($RoomNumber)";
            $user_id = $_SESSION['adminSession'];
            $this->admin->logs($activity, $user_id);

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

    public function addTenantInRoom($room_id, $tenant_id, $all_tenants)
    {
        // Check if the main tenant is already assigned to another room
        $stmt = $this->runQuery('SELECT id FROM rooms WHERE FIND_IN_SET(:user_id, user_id) AND id != :room_id');
        $stmt->execute([
            ":user_id" => $tenant_id,
            ":room_id" => $room_id
        ]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $_SESSION['status_title'] = 'Duplicate!';
            $_SESSION['status'] = 'This tenant is already assigned to another room.';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../room-details');
            exit;
        }

                // 🔹 Get the room_name first
        $stmt = $this->runQuery('SELECT room_number FROM rooms WHERE id = :id');
        $stmt->execute([":id" => $room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        $room_name = $room ? $room['room_number'] : "Unknown Room";

        // ✅ Update room with ALL tenants (main + old + new subtenants)
        $stmt = $this->runQuery('UPDATE rooms SET user_id = :user_id, status = :status WHERE id = :id');
        $exec = $stmt->execute([
            ":user_id" => $all_tenants,
            ":id"      => $room_id,
            ":status"  => 'occupied'
        ]);

        if ($exec) {
            $activity = "Tenant(s) updated in room $room_name";
            $user_id  = $_SESSION['adminSession'];
            $this->admin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Tenant(s) have been added to the room';
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

    public function addSubmeterId($room_id, $submeter_id, $kwh_limit = null)
    {
        // Check if the submeter_id is already assigned to another room
        $stmt = $this->runQuery('SELECT id FROM rooms WHERE submeter_id = :submeter_id AND id != :room_id');
        $stmt->execute(array(
            ":submeter_id" => $submeter_id,
            ":room_id"     => $room_id
        ));
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $_SESSION['status_title'] = 'Duplicate!';
            $_SESSION['status'] = 'This Electric Meter ID is already assigned to another room.';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../room-details');
            exit;
        }

        // Fetch current values for this room
        $stmt = $this->runQuery('SELECT submeter_id, kwh_limit FROM rooms WHERE id = :id');
        $stmt->execute(array(":id" => $room_id));
        $current = $stmt->fetch(PDO::FETCH_ASSOC);

        // 🛑 Check if no change (submeter and kwh_limit same as before)
        if (
            $current && $current['submeter_id'] == $submeter_id &&
            ($kwh_limit === null || $current['kwh_limit'] == $kwh_limit)
        ) {

            $_SESSION['status_title'] = 'No Change!';
            $_SESSION['status'] = 'The Electric Meter ID and kWh limit are already assigned to this room.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 40000;
            header('Location: ../room-details');
            exit;
        }

        // ✅ Build query dynamically
        if ($kwh_limit !== null && $kwh_limit !== '') {
            $stmt = $this->runQuery('UPDATE rooms SET submeter_id = :submeter_id, kwh_limit = :kwh_limit WHERE id = :id');
            $exec = $stmt->execute(array(
                ":submeter_id" => $submeter_id,
                ":kwh_limit"   => $kwh_limit,
                ":id"          => $room_id
            ));
            $logMsg = "New Submeter ID and kWh limit have been added to room.";
        } else {
            $stmt = $this->runQuery('UPDATE rooms SET submeter_id = :submeter_id WHERE id = :id');
            $exec = $stmt->execute(array(
                ":submeter_id" => $submeter_id,
                ":id"          => $room_id
            ));
            $logMsg = "New Submeter ID has been added to room.";
        }

        if ($exec) {
            $user_id = $_SESSION['adminSession'];
            $this->admin->logs(activity: $logMsg, user_id: $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = $logMsg;
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
        // 🔹 Get the room_name first
        $stmt = $this->runQuery('SELECT room_number FROM rooms WHERE id = :id');
        $stmt->execute([":id" => $room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        $room_name = $room ? $room['room_number'] : "Unknown Room";

        // 🔹 Update room status and clear user_id
        $stmt = $this->runQuery('UPDATE rooms SET user_id = NULL, status = :status WHERE id = :id');
        $exec = $stmt->execute([
            ":id" => $room_id,
            ":status" => 'vacant'
        ]);

        if ($exec) {
            $activity = "User has been removed from room: $room_name";
            $user_id = $_SESSION['adminSession'];
            $this->admin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = "User has been removed from $room_name";
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

    public function deleteSubTenant($room_id, $subtenant_id)
    {
        // Get current tenants
        $stmt = $this->runQuery("SELECT user_id FROM rooms WHERE id = :room_id");
        $stmt->execute([":room_id" => $room_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $_SESSION['status_title'] = 'Error!';
            $_SESSION['status'] = 'Room not found';
            $_SESSION['status_code'] = 'error';
            header("Location: ../room-details?id=$room_id");
            exit;
        }

        $user_ids = explode(",", $row['user_id']);
        $user_ids = array_map('trim', $user_ids);

        // Remove the target subtenant
        $new_ids = array_diff($user_ids, [$subtenant_id]);

        // Rebuild string
        $updated_user_ids = implode(",", $new_ids);

        $stmt = $this->runQuery('SELECT room_number FROM rooms WHERE id = :id');
        $stmt->execute([":id" => $room_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);
        $room_name = $room ? $room['room_number'] : "Unknown Room";

        // Update DB
        $stmt = $this->runQuery("UPDATE rooms SET user_id = :user_id WHERE id = :room_id");
        $exec = $stmt->execute([
            ":user_id" => $updated_user_ids,
            ":room_id" => $room_id
        ]);

        if ($exec) {
            $activity = "Removed tenant from room $room_name";
            $user_id  = $_SESSION['adminSession'];
            $this->admin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'SubTenant removed successfully';
            $_SESSION['status_code'] = 'success';
        } else {
            $_SESSION['status_title'] = 'Error!';
            $_SESSION['status'] = 'Failed to remove tenant';
            $_SESSION['status_code'] = 'error';
        }

        header("Location: ../room-details?id=$room_id");
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

if (isset($_GET['delete_sub_tenant'])) {
    $room_id = $_GET["room_id"];
    $subtenant_id = $_GET["subtenant_id"];

    $deleteSubTenant = new Room();
    $deleteSubTenant->deleteSubTenant($room_id, $subtenant_id);
}

if (isset($_POST['btn-add-tenant'])) {
    $room_id   = trim($_POST['room_id']);
    $tenant_id = trim($_POST['tenant_id']); // main tenant

    // Existing users (main tenant + old subtenants)
    $existing_ids = $_POST['existing_user_ids'] ?? [];

    // New subtenants selected in form
    $new_ids = $_POST['sub_tenant_ids'] ?? [];

    // Merge all together
    $all_ids = array_unique(array_merge($existing_ids, $new_ids));

    // Make sure the main tenant stays at the front
    if (!in_array($tenant_id, $all_ids)) {
        array_unshift($all_ids, $tenant_id);
    }

    // Convert to CSV string
    $all_tenants = implode(",", $all_ids);

    $addTenantInRoom = new Room();
    $addTenantInRoom->addTenantInRoom($room_id, $tenant_id, $all_tenants);
}

if (isset($_POST['btn-add-submeterId'])) {
    $room_id = trim($_POST['room_id']);
    $submeter_id = trim($_POST['submeter_id']);
    $kwh_limit = trim($_POST['kwh_limit']);

    $addSubmeterId = new Room();
    $addSubmeterId->addSubmeterId($room_id, $submeter_id, $kwh_limit);
}
