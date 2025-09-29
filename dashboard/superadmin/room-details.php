<?php
include_once 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and store room_id from POST
    $_SESSION['room_id'] = $_POST['room_id'] ?? '';
}

// Get room_id from session
$roomId = $_SESSION['room_id'] ?? '';

// Fetch room data
$stmt = $user->runQuery("SELECT * FROM rooms WHERE id=:uid AND owner_id=:owner_id");
$stmt->execute([':uid' => $roomId, ':owner_id' => $_SESSION['superadminSession']]);
$rooms_data = $stmt->fetch(PDO::FETCH_ASSOC);

$tenant_user_id = $rooms_data['user_id'] ?? '';
$room_id = $rooms_data['id'] ?? '';
$room_number  = $rooms_data['room_number'] ?? '';
$rooms_last_update  = $rooms_data['updated_at'] ?? '';
$submeter_id  = $rooms_data['submeter_id'] ?? '';

// Fetch user data
$stmt = $user->runQuery("SELECT * FROM users WHERE id=:uid AND account_status = 'Active'");
$stmt->execute([':uid' => $tenant_user_id]);
$rooms_user_data = $stmt->fetch(PDO::FETCH_ASSOC);

// Assign user data variables
$rooms_user_id           = $rooms_user_data['id'] ?? '';
$rooms_user_profile      = $rooms_user_data['profile'] ?? '';
$rooms_user_fname        = $rooms_user_data['first_name'] ?? '';
$rooms_user_mname        = $rooms_user_data['middle_name'] ?? '';
$rooms_user_lname        = $rooms_user_data['last_name'] ?? '';
$rooms_user_fullname     = trim(($rooms_user_lname ? $rooms_user_lname . ', ' : '') . $rooms_user_fname);
$rooms_user_sex          = $rooms_user_data['sex'] ?? '';
$rooms_user_birth_date   = $rooms_user_data['date_of_birth'] ?? '';
$rooms_user_age          = $rooms_user_data['age'] ?? '';
$rooms_user_civil_status = $rooms_user_data['civil_status'] ?? '';
$rooms_user_phone_number = $rooms_user_data['phone_number'] ?? '';
$rooms_user_email        = $rooms_user_data['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $header_dashboard->getHeaderDashboard() ?>
    <link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>
    <title>Room Details</title>
</head>

<body>

    <!-- Loader -->
    <div class="loader"></div>

    <!-- SIDEBAR -->
    <?php echo $sidebar->getSideBar(); ?> <!-- This will render the sidebar -->
    <!-- SIDEBAR -->



    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <div class="username">
                <span>Hello, <label for=""><?php echo $user_fname ?></label></span>
            </div>
            <a href="profile" class="profile" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Profile">
                <img src="../../src/img/<?php echo $user_profile ?>">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Room Details</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="./">Home</a>
                        </li>
                        <li>|</li>
                        <li>
                            <a class="active" href="energy-monitoring">Energy Monitoring</a>
                        </li>
                        <li>|</li>
                        <li>
                            <a href="">Room Details</a>
                        </li>
                    </ul>
                </div>
            </div>
            </div>
            <ul class="dashboard_data">
                <div class="gauge_dashboard">
                    <div class="gauge">
                        <section class="profile-form">
                            <div class="header"></div>
                            <div class="profile">
                                <div class="profile-img">
                                    <img src="../../src/img/<?php echo $rooms_user_profile ?>" alt="profile" onerror="this.onerror=null; this.src='../../src/img/profile.png';">

                                    <a href="controller/room-controller.php?id=<?php echo $room_id ?>&delete_tenant=1" class="delete_tenant"><i class='bx bxs-trash'></i></a>
                                    <button class="btn-dark change" onclick="tenant_profie()"><i class='bx bxs-user'></i> Tenant Profile</button>
                                    <button class="btn-dark change" onclick="submeter()"><i class='bx bxs-thunder'></i> Submeter</button>

                                </div>

                                <?php if ($rooms_user_id): ?>
                                    <div id="tenant_profile">
                                        <form action="" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                            <div class="row gx-5 needs-validation">

                                                <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;">
                                                    <i class='bx bxs-user'></i> <?php echo $room_number ?> Tenant Profile
                                                    <p>Last update: <?php echo $rooms_last_update  ?></p>
                                                </label>

                                                <div class="col-md-6">
                                                    <label for="name" class="form-label">First Name</label>
                                                    <input type="text" disabled class="form-control" autocapitalize="on" autocomplete="off" name="first_name" id="first_name" required value="<?php echo $rooms_user_fname  ?>">
                                                    <div class="invalid-feedback">
                                                        Please provide a First Name
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="name" class="form-label">Middle Name</label>
                                                    <input type="text" disabled class="form-control" autocapitalize="on" autocomplete="off" name="middle_name" id="middle_name" value="<?php echo $rooms_user_mname  ?>">
                                                    <div class="invalid-feedback">
                                                        Please provide a Middle Name
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="name" class="form-label">Last Name</label>
                                                    <input type="text" disabled class="form-control" autocapitalize="on" autocomplete="off" name="last_name" id="last_name" required value="<?php echo $rooms_user_lname  ?>">
                                                    <div class="invalid-feedback">
                                                        Please provide a Last Name
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" disabled class="form-control" autocapitalize="off" autocomplete="off" name="" id="" required value="<?php echo $rooms_user_email  ?>">
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div id="tenant_profile" class="no_tenant">
                                        <p>No tenant assigned to this room.</p>
                                        <div class="addBtn2">
                                            <button type="submit" class="btn-dark" data-bs-toggle="modal" data-bs-target="#tenantModal"><i class='bx bx-user-plus'></i> Add Tenant</button>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($submeter_id): ?>
                                    <div id="submeter" style="display: none;">
                                        <form action="controller/room-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                            <div class="row gx-5 needs-validation">
                                                <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;">
                                                    <i class='bx bxs-key'></i> <?php echo $room_number ?> Submeter ID
                                                    <p>Last update: <?php echo $rooms_last_update  ?></p>
                                                </label>
                                                <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                                                <div class="col-md-12">
                                                    <label for="submeter_id" class="form-label">Submeter ID<span> *</span></label>
                                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="submeter_id" id="submeter_id" required value="<?php echo $submeter_id; ?>">
                                                    <div class="invalid-feedback">
                                                        Please provide a Submeter ID.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addBtn">
                                                <button type="submit" class="btn-dark" name="btn-add-submeterId" id="btn-update" onclick="return IsEmpty(); sexEmpty();">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <div id="submeter" class="no_submeter" style="display: none;">
                                        <p>No submeter assigned to this room.</p>
                                        <div class="addBtn2">
                                            <button type="submit" class="btn-dark" data-bs-toggle="modal" data-bs-target="#submeterModal"><i class='bx bx-user-plus'></i> Add Submeter</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                    <?php if ($submeter_id): ?>
                        <div class="status">
                            <div class="card arduino">
                                <h1>⚡ Total Voltage</h1>
                                <div class="sensor-data">
                                    <span id="voltage">Loading....</span>
                                </div>
                            </div>
                            <div class="card arduino">
                                <h1>🔌 Total Current</h1>
                                <div class="sensor-data">
                                    <span id="current">Loading....</span>
                                </div>
                            </div>
                            <div class="card arduino">
                                <h1>🔌 Total Power</h1>
                                <div class="sensor-data">
                                    <span id="power">Loading....</span>
                                </div>
                            </div>
                            <div class="card arduino">
                                <h1>🔋 Total Energy Consumption</h1>
                                <div class="sensor-data">
                                    <span id="energyKWh">Loading....</span>
                                </div>
                            </div>
                            <div class="card arduino">
                                <h1>🎵 Total Frequency</h1>
                                <div class="sensor-data">
                                    <span id="frequency">Loading....</span>
                                </div>
                            </div>
                            <div class="card arduino">
                                <h1>📐 Total Power Factor</h1>
                                <div class="sensor-data">
                                    <span id="powerFactor">Loading....</span>
                                </div>
                            </div>
                        </div>
                        <div class="gauge">
                            <div class="card gauge_card">
                                <p class="card-title">Energy Cost Graph</p>
                                <div id="S1"></div>
                            </div>
                            <div class="card gauge_card">
                                <p class="card-title">Usage Estimate</p>
                                <div class="d-flex align-items-center gap-2 mb-3" style="margin-top: 20px;">
                                    <button id="dailyBtn" class="chart-btn">Daily</button>
                                    <button id="weeklyBtn" class="chart-btn">Weekly</button>
                                    <button id="monthlyBtn" class="chart-btn">Monthly</button>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-3" style="margin-top: 20px;">
                                    <div class="d-flex align-items-center">
                                        <label for="monthSelect" class="me-2 mb-0">Month:</label>
                                        <select id="monthSelect" class="form-select form-select-sm"></select>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <label for="yearSelect" class="me-2 mb-0">Year:</label>
                                        <select id="yearSelect" class="form-select form-select-sm"></select>
                                    </div>
                                </div>
                                <div id="usage_estimate_tenant"></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="status submeter_status">
                            <p>No submeter data available for this room.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </ul>

            <div class="class-modal">
                <div class="modal fade" id="tenantModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="header"></div>
                            <div class="modal-header">
                                <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-user'></i> Add Tenant</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
                            </div>
                            <div class="modal-body">
                                <section class="data-form-modals">
                                    <div class="registration">
                                        <form action="controller/room-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                            <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                                            <div class="col-md-12">
                                                <label for="tenant_id" class="form-label">Select Tenant<span> *</span></label>
                                                <select class="form-select form-control" name="tenant_id" autocomplete="off" id="tenant_id" required>
                                                    <option disabled selected value="">Please Select Tenant</option>
                                                    <?php
                                                    // Current room ID
                                                    $current_room_id = $room_id ?? 0;

                                                    // Select active tenants who are NOT assigned to any room (or this room)
                                                    $stmt = $user->runQuery("
                                                        SELECT * FROM users
                                                        WHERE account_status = :account_status
                                                        AND user_type = :user_type
                                                        AND access_key = :access_key AND id NOT IN (
                                                            SELECT user_id FROM rooms WHERE user_id IS NOT NULL
                                                        )
                                                    ");

                                                    $stmt->execute([
                                                        ":account_status" => "active",
                                                        ":user_type" => 2, // tenant
                                                        ":access_key" => $access_key, // tenant
                                                    ]);

                                                    while ($tenant = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        $fullname = trim(
                                                            ($tenant['last_name'] ? $tenant['last_name'] . ', ' : '') .
                                                                $tenant['first_name'] .
                                                                ($tenant['middle_name'] ? ' ' . $tenant['middle_name'] : '')
                                                        );
                                                    ?>
                                                        <option value="<?= $tenant['id'] ?>"><?= $fullname ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a tenant.
                                                </div>
                                            </div>
                                            <div class="addBtn">
                                                <button type="submit" class="btn-dark" name="btn-add-tenant" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="class-modal">
                <div class="modal fade" id="submeterModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="header"></div>
                            <div class="modal-header">
                                <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-key'></i> Add Submeter</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
                            </div>
                            <div class="modal-body">
                                <section class="data-form-modals">
                                    <div class="registration">
                                        <form action="controller/room-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                            <div class="row gx-5 needs-validation">
                                                <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                                                <div class="col-md-12">
                                                    <label for="submeter_id" class="form-label">Submeter ID<span> *</span></label>
                                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="submeter_id" id="submeter_id" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a Submeter ID.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addBtn">
                                                <button type="submit" class="btn-dark" name="btn-add-submeterId" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div id="chartContainer"
            data-roomsubmeter-id="<?= $submeter_id ?>">
        </div>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <?php echo $footer_dashboard->getFooterDashboard() ?>
    <?php include_once '../../config/sweetalert.php'; ?>
    <script src="../../src/js/gauge.js"></script>
    <script type="module" src="../../src/js/submeter_data.js"></script>
    <script type="module" src="../../src/js/tenant_energy_usage_graph.js"></script>
</body>

</html>