<?php
include_once '../../../config/settings-configuration.php';
include_once __DIR__ . '/../../../database/dbconfig.php';
require_once __DIR__ . '/../authentication/superadmin-class.php';


class SystemSettings
{
    private $conn;
    private $superadmin;


    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
        $this->superadmin = new SUPERADMIN();
    }
    // update system
    public function updateSystem($system_name, $system_phone_number, $system_email, $system_copy_right)
    {
        // First, fetch the current settings
        $query = "SELECT system_name, system_phone_number, system_email, system_copy_right FROM system_config WHERE id = 1";
        $stmt = $this->runQuery($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Compare the current settings with the new settings
        if ($result['system_name'] === $system_name && $result['system_phone_number'] === $system_phone_number && $result['system_email'] === $system_email && $result['system_copy_right'] === $system_copy_right) {
            $_SESSION['status_title'] = 'Notice';
            $_SESSION['status'] = 'No changes were made to the system settings.';
            $_SESSION['status_code'] = 'info';
            $_SESSION['status_timer'] = 5000;
        } else {
            // Proceed with the update if changes were detected
            $stmt = $this->runQuery('UPDATE system_config SET system_name=:system_name, system_phone_number=:system_phone_number, system_email=:system_email, system_copy_right=:system_copy_right WHERE id =1');
            $exec = $stmt->execute(array(
                ":system_name"          => $system_name,
                ":system_phone_number"  => $system_phone_number,
                ":system_email"         => $system_email,
                ":system_copy_right"    => $system_copy_right
            ));

            if ($exec) {

                $activity = "System Settings has been updated";
                $user_id = $_SESSION['superadminSession'];
                $this->superadmin->logs($activity, $user_id);

                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'System settings successfully updated';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        }

        header('Location: ../settings');
    }

    //update logo
    public function updateLogo($system_logo)
    {
        $folder = "../../../src/img/" . basename($system_logo);
        chmod($folder, 0755);
        $stmt = $this->runQuery('UPDATE system_config SET system_logo=:system_logo WHERE id =1');
        $exec = $stmt->execute(array(
            ":system_logo"          => $system_logo,
        ));

        if ($exec && move_uploaded_file($_FILES['system_logo']['tmp_name'], $folder)) {
            $activity = "System Logo has been updated";
            $user_id = $_SESSION['superadminSession'];
            $this->superadmin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'System logo successfully updated';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../settings');
    }
    //update google recaptcha
    public function updateRecaptcha($site_key, $site_secret_key)
    {
        $stmt = $this->runQuery('UPDATE google_recaptcha_api SET site_key=:site_key, site_secret_key=:site_secret_key WHERE id =1');
        $exec = $stmt->execute(array(
            ":site_key"                => $site_key,
            ":site_secret_key"         => $site_secret_key,
        ));

        if ($exec) {
            $activity = "Google Recaptcha API has been updated";
            $user_id = $_SESSION['superadminSession'];
            $this->superadmin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'Google Recaptcha successfully updated';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../settings');
    }
    //update SMTP
    public function updateSMTP($email, $password)
    {
        $stmt = $this->runQuery('UPDATE email_config SET email=:email, password=:password WHERE id =1');
        $exec = $stmt->execute(array(
            ":email"                => $email,
            ":password"             => $password,
        ));

        if ($exec) {
            $activity = "System SMTP has been updated";
            $user_id = $_SESSION['superadminSession'];
            $this->superadmin->logs($activity, $user_id);

            $_SESSION['status_title'] = 'Success!';
            $_SESSION['status'] = 'System SMTP successfully updated';
            $_SESSION['status_code'] = 'success';
            $_SESSION['status_timer'] = 40000;
        } else {
            $_SESSION['status_title'] = 'Oops!';
            $_SESSION['status'] = 'Something went wrong, please try again!';
            $_SESSION['status_code'] = 'error';
            $_SESSION['status_timer'] = 100000;
        }

        header('Location: ../settings');
    }

    //add access token
    public function addAccessToken($access_key)
    {
        $admin_id = $_SESSION['superadminSession'];

        // Check if superadmin already has an access key
        $stmtCheck = $this->runQuery("SELECT id, access_key FROM admin_access_keys WHERE admin_id = :admin_id LIMIT 1");
        $stmtCheck->execute([":admin_id" => $admin_id]);
        $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $old_key = $existing['access_key'];

            // Update existing access key only if it changed
            if ($old_key !== $access_key) {
                // Start transaction for consistency
                $this->conn->beginTransaction();

                try {
                    // 1. Update the admin_access_keys table
                    $stmt = $this->runQuery("UPDATE admin_access_keys SET access_key = :access_key WHERE admin_id = :admin_id");
                    $stmt->execute([
                        ":access_key" => $access_key,
                        ":admin_id" => $admin_id
                    ]);

                    // 2. Update ALL users who are using the old access key
                    $stmtUsers = $this->runQuery("UPDATE users SET access_key = :new_key WHERE access_key = :old_key");
                    $stmtUsers->execute([
                        ":new_key" => $access_key,
                        ":old_key" => $old_key
                    ]);

                    // Commit transaction
                    $this->conn->commit();

                    $activity = "Access Token has been updated (propagated to users)";
                    $this->superadmin->logs($activity, $admin_id);

                    $_SESSION['status_title'] = 'Success!';
                    $_SESSION['status'] = 'Access Token successfully updated and synced with users';
                    $_SESSION['status_code'] = 'success';
                    $_SESSION['status_timer'] = 40000;
                } catch (Exception $e) {
                    $this->conn->rollBack();

                    $_SESSION['status_title'] = 'Oops!';
                    $_SESSION['status'] = 'Update failed: ' . $e->getMessage();
                    $_SESSION['status_code'] = 'error';
                    $_SESSION['status_timer'] = 100000;
                }
            } else {
                // No changes needed
                $_SESSION['status_title'] = 'Info';
                $_SESSION['status'] = 'Access Token is already up-to-date';
                $_SESSION['status_code'] = 'info';
                $_SESSION['status_timer'] = 20000;
            }
        } else {
            // Insert new access key
            $stmt = $this->runQuery("INSERT INTO admin_access_keys (access_key, admin_id) VALUES(:access_key, :admin_id)");
            $exec = $stmt->execute([
                ":access_key" => $access_key,
                ":admin_id" => $admin_id
            ]);

            if ($exec) {
                $activity = "Access Token has been added";
                $this->superadmin->logs($activity, $admin_id);

                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'Access Token successfully added';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            } else {
                $_SESSION['status_title'] = 'Oops!';
                $_SESSION['status'] = 'Something went wrong, please try again!';
                $_SESSION['status_code'] = 'error';
                $_SESSION['status_timer'] = 100000;
            }
        }

        header('Location: ../settings');
        exit();
    }

    public function addKwhCost($kwh_cost)
    {
        $admin_id = $_SESSION['superadminSession'];

        // Check if record already exists for this superadmin
        $stmt = $this->runQuery('SELECT cost FROM kwh_cost WHERE admin_id = :admin_id LIMIT 1');
        $stmt->execute([":admin_id" => $admin_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            // No record found → Insert new
            $stmt = $this->runQuery('INSERT INTO kwh_cost (cost, admin_id) VALUES(:cost, :admin_id)');
            $exec = $stmt->execute([":cost" => $kwh_cost, ":admin_id" => $admin_id]);

            if ($exec) {
                $this->superadmin->logs("KWH Cost has been added", $admin_id);
                $_SESSION['status_title'] = 'Success!';
                $_SESSION['status'] = 'KWH Cost successfully added';
                $_SESSION['status_code'] = 'success';
                $_SESSION['status_timer'] = 40000;
            }
        } else {
            // Record exists → Check if different
            if ($row['cost'] != $kwh_cost) {
                $stmt = $this->runQuery('UPDATE kwh_cost SET cost = :cost WHERE admin_id = :admin_id');
                $exec = $stmt->execute([":cost" => $kwh_cost, ":admin_id" => $admin_id]);

                if ($exec) {
                    $this->superadmin->logs("KWH Cost has been updated", $admin_id);
                    $_SESSION['status_title'] = 'Success!';
                    $_SESSION['status'] = 'KWH Cost successfully updated';
                    $_SESSION['status_code'] = 'success';
                    $_SESSION['status_timer'] = 40000;
                }
            } else {
                // No changes → Do nothing
                $_SESSION['status_title'] = 'No Changes';
                $_SESSION['status'] = 'KWH Cost remains the same';
                $_SESSION['status_code'] = 'info';
                $_SESSION['status_timer'] = 3000;
            }
        }

        header('Location: ../settings');
        exit;
    }
    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}

// Check if the form has been submitted for updating the system
if (isset($_POST['btn-update-system'])) {
    $system_name            = trim($_POST['system_name']);
    $system_phone_number    = trim($_POST['system_phone_number']);
    $system_email           = trim($_POST['system_email']);
    $system_copy_right      = trim($_POST['system_copy_right']);

    $systemSettings = new SystemSettings();
    $systemSettings->updateSystem($system_name, $system_phone_number, $system_email, $system_copy_right);
}

if (isset($_POST['btn-update-logo'])) {
    $system_logo = $_FILES['system_logo']['name'];

    $systemSettings = new SystemSettings();
    $systemSettings->updateLogo($system_logo);
}

if (isset($_POST['btn-update-recaptcha'])) {
    $site_key           = trim($_POST['site_key']);
    $site_secret_key    = trim($_POST['site_secret_key']);

    $systemSettings = new SystemSettings();
    $systemSettings->updateRecaptcha($site_key, $site_secret_key);
}

if (isset($_POST['btn-update-smtp'])) {
    $email       = trim($_POST['email']);
    $password    = trim($_POST['password']);

    $systemSettings = new SystemSettings();
    $systemSettings->updateSMTP($email, $password);
}

//access token
if (isset($_POST['btn-add-access-token'])) {
    $access_key = trim($_POST['access_key']);

    $systemSettings = new SystemSettings();
    $systemSettings->addAccessToken($access_key);
}

//kwh cost
if (isset($_POST['btn-add-kwh-cost'])) {
    $kwh_cost = trim($_POST['kwh_cost']);
    $systemSettings = new SystemSettings();
    $systemSettings->addKwhCost($kwh_cost);
}
