<?php
include_once 'header.php';
require_once 'fetch.php';


$plant_id = $_GET['id'];

$stmt = $user->runQuery("SELECT * FROM plants WHERE id=:id");
$stmt->execute(array(":id" => $plant_id));
$plant_data = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $header_dashboard->getHeaderDashboard() ?>

    <title>Edi Product Data</title>
</head>

<body>

    <div class="class-modal">
        <div class="modal fade" id="editModal" aria-labelledby="classModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="header"></div>
                    <div class="modal-header">
                        <h5 class="modal-title" id="classModalLabel"><i class='bx bxs-edit'></i> Edit Plant Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="history.back()"></button>
                    </div>
                    <div class="modal-body">
                        <section class="data-form-modals">
                            <div class="registration">
                                <form action="controller/plants-controller.php" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">
                                        <input type="hidden" name="plant_id" value="<?php echo $plant_data['id']?>">
                                        <div class="col-md-6">
                                            <label for="plant_name" class="form-label">Plant Name<span> *</span></label>
                                            <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="plant_name" id="plant_name" value="<?php echo $plant_data['plant_name']?>" required>
                                            <div class="invalid-feedback">
                                                Please provide a Plant Name.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="dry_threshold" class="form-label">Dry Threshold<span> (for automatic mode)*</span></label>
                                            <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="dry_threshold" id="dry_threshold" value="<?php echo $plant_data['dry_threshold']?>" required>
                                            <div class="invalid-feedback">
                                                Please provide a Dry Threshold.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="watered_threshold" class="form-label">Watered Threshold<span> (for automatic mode)*</span></label>
                                            <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="watered_threshold" id="watered_threshold" value="<?php echo $plant_data['watered_threshold']?>" required>
                                            <div class="invalid-feedback">
                                                Please provide a Watered Threshold.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="addBtn">
                                        <button type="submit" class="btn-dark" name="btn-edit-plants" id="btn-add" onclick="return IsEmpty(); sexEmpty();">Update</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $footer_dashboard->getFooterDashboard() ?>
    <?php include_once '../../config/sweetalert.php'; ?>
    <script>
        //Load Modal
        $(window).on('load', function() {
            $('#editModal').modal('show');
        });
    </script>
</body>

</html>