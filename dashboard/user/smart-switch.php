<?php
include_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $header_dashboard->getHeaderDashboard() ?>
    <link href='https://fonts.googleapis.com/css?family=Antonio' rel='stylesheet'>

    <title>Smart Switch</title>
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
                    <h1>Smart Switch</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="./">Home</a>
                        </li>
                        <li>|</li>
                        <li>
                            <a href="">Smart Switch</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="modal-button">
                <button type="button" data-bs-toggle="modal" data-bs-target="#appliancesModal" class="btn-dark"><i class='bx bxs-plus-circle'></i> Add Appliances</button>
            </div>

            <ul class="dashboard_data">
                <div class="gauge_dashboard">
                    <div class="room_status">
                        <?php

                        $stmt = $user->runQuery("SELECT * FROM appliances WHERE user_id=:user_id ORDER BY id ASC");
                        $stmt->execute(params: [':user_id' => $_SESSION['userSession']]);

                        if ($stmt->rowCount() >= 1) {
                            while ($appliance_data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($appliance_data);
                        ?>
                                <div class="card2 arduino appliance" id="appliance-<?php echo $appliance_data['id']; ?>">
                                    <h1><?php echo htmlspecialchars($appliance_data['appliance_name']); ?></h1>

                                    <div class="sensor-data">
                                        <span class="tenant_name">
                                            Switch ID: <?php echo htmlspecialchars($appliance_data['switch_id']); ?>
                                        </span>
                                        <span class="room_status">
                                            Status: <strong id="status-<?php echo $appliance_data['id']; ?>">
                                                <?php echo htmlspecialchars($appliance_data['status']); ?>
                                            </strong>
                                        </span>
                                    </div>

                                    <div class="more-info">
                                        <button
                                            type="button"
                                            class="btn-toggle <?php echo ($appliance_data['status'] === 'ON') ? 'btn-on' : 'btn-off'; ?>"
                                            onclick="toggleAppliance(<?php echo $appliance_data['id']; ?>)">
                                            <?php echo ($appliance_data['status'] === 'ON') ? 'Turn OFF' : 'Turn ON'; ?>
                                        </button>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <div class="card arduino">
                                <h1 class="no_room">No Appliance Found</h1>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </ul>

            <div class="class-modal">
                <div class="modal fade" id="appliancesModal" tabindex="-1" aria-labelledby="classModalLabel" aria-hidden="true" data-bs-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="header"></div>
                            <div class="modal-header">
                                <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-bulb'></i> Add Appliances</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeButton"></button>
                            </div>
                            <div class="modal-body">
                                <section class="data-form-modals">
                                    <div class="registration">
                                        <form action="controller/appliances-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                            <div class="row gx-5 needs-validation">
                                                <div class="col-md-12">
                                                    <label for="appliance_name" class="form-label">Appliance Name<span> *</span></label>
                                                    <select class="form-select form-control" name="appliance_name" maxlength="6" autocomplete="off" id="appliance_name">
                                                        <option value="" selected disabled>Select Appliance</option>
                                                        <option value="Ceiling Light">Ceiling Light</option>
                                                        <option value="Wall Fan">Wall Fan</option>
                                                        <option value="Electric Fan">Electric Fan</option>
                                                        <option value="Air Conditioner">Air Conditioner</option>
                                                        <option value="Laptop Charger">Laptop Charger</option>
                                                        <option value="Rice Cooker">Rice Cooker</option>
                                                        <option value="Electric Kettle">Electric Kettle</option>
                                                        <option value="Phone Charger">Phone Charger</option>
                                                        <option value="Desk Lamp">Desk Lamp</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Please select a Appliance Name.
                                                    </div>
                                                </div>

                                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
                                                <input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id; ?>">

                                                <div class="col-md-12">
                                                    <label for="switch_id" class="form-label">Switch ID<span> *</span></label>
                                                    <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="switch_id" id="switch_id" required>
                                                    <div class="invalid-feedback">
                                                        Please provide a Switch ID
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addBtn">
                                                <button type="submit" class="btn-dark" name="btn-add-appliances" onclick="return IsEmpty(); sexEmpty();">Add</button>
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
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <?php echo $footer_dashboard->getFooterDashboard() ?>
    <?php include_once '../../config/sweetalert.php'; ?>

    <script>
        //live search---------------------------------------------------------------------------------------//
        $(document).ready(function() {

            load_data(1);

            function load_data(page, query = '') {
                $.ajax({
                    url: "tables/sensor-logs-table.php",
                    method: "POST",
                    data: {
                        page: page,
                        query: query
                    },
                    success: function(data) {
                        $('#dynamic_content').html(data);
                    }
                });
            }

            $(document).on('click', '.page-link', function() {
                var page = $(this).data('page_number');
                var query = $('#search_box').val();
                load_data(page, query);
            });

            $('#search_box').keyup(function() {
                var query = $('#search_box').val();
                load_data(1, query);
            });

        });

        async function toggleAppliance(applianceId) {
            const statusElement = document.getElementById(`status-${applianceId}`);
            const button = document.querySelector(`#appliance-${applianceId} .btn-toggle`);

            // Determine the new status
            const currentStatus = statusElement.textContent.trim();
            const newStatus = currentStatus === 'ON' ? 'OFF' : 'ON';

            // Update UI immediately (no reload)
            statusElement.textContent = newStatus;
            button.textContent = newStatus === 'ON' ? 'Turn OFF' : 'Turn ON';
            button.classList.toggle('btn-on', newStatus === 'ON');
            button.classList.toggle('btn-off', newStatus === 'OFF');

            // Send update to server (no loading)
            try {
                await fetch('controller/appliances-controller.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: applianceId, status: newStatus })
                });
            } catch (err) {
                console.error('Error updating status:', err);
            }
        }

</script>
</body>

</html>