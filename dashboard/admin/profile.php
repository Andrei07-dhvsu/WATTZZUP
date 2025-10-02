<?php
include_once 'header.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $header_dashboard->getHeaderDashboard() ?>

    <title>Profile</title>
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
                    <h1>Profile</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a class="active" href="./">Home</a>
                        </li>
                        <li>|</li>
                        <li>
                            <a href="#">Profile</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3><i class='bx bxs-user-account'></i> Profile Configuration</h3>
                    </div>
                    <!-- BODY -->
                    <section class="profile-form">
                        <div class="header"></div>
                        <div class="profile">
                            <div class="profile-img">
                                <img src="../../src/img/<?php echo $user_profile ?>" alt="logo">

                                <a href="controller/profile-controller.php?id=<?php echo $user_id ?>&delete_avatar=1" class="delete"><i class='bx bxs-trash'></i></a>
                                <button class="btn-dark change" onclick="edit()"><i class='bx bxs-edit'></i> Edit Profile</button>
                                <button class="btn-dark change" onclick="avatar()"><i class='bx bxs-user'></i> Change Avatar</button>
                                <button class="btn-dark change" onclick="password()"><i class='bx bxs-key'></i> Change Password</button>

                            </div>

                            <div id="Edit">
                                <form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">

                                        <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-edit'></i> Edit Profile<p>Last update:
                                                <?php echo $user_last_update  ?></p></label>

                                        <div class="col-md-6">
                                            <label for="name" class="form-label">First Name<span> *</span></label>
                                            <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="first_name" id="first_name" required value="<?php echo $user_fname  ?>">
                                            <div class="invalid-feedback">
                                                Please provide a First Name
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="middle_name" id="middle_name" value="<?php echo $user_mname  ?>">
                                            <div class="invalid-feedback">
                                                Please provide a Middle Name
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Last Name<span> *</span></label>
                                            <input type="text" class="form-control" autocapitalize="on" autocomplete="off" name="last_name" id="last_name" required value="<?php echo $user_lname  ?>">
                                            <div class="invalid-feedback">
                                                Please provide a Last Name
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="sex" class="form-label">Sex</label>
                                            <select class="form-select form-control" name="sex" maxlength="6" autocomplete="off" id="sex">
                                                <option selected value="<?php echo $user_sex ?>"><?php echo $user_sex ?></option>
                                                <option value="MALE">MALE</option>
                                                <option value="FEMALE ">FEMALE</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a valid Sex.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="birthdate" class="form-label">Birth Date</label>
                                            <input type="date" class="form-control" value="<?php echo $user_birth_date ?>" autocapitalize="off" autocomplete="off" name="date_of_birth" id="date_of_birth" maxlength="10" pattern="^[a-zA-Z0-9]+@gmail\.com$" placeholder="Ex: mm/dd/yyyy" onkeyup="getAgeVal(0)" onblur="getAgeVal(0);">
                                            <div class="invalid-feedback">
                                                Please provide a Birth Date.
                                            </div>
                                        </div>

                                        <div class="col-md-6" style="display: none;">
                                            <label for="age" class="form-label">Age<span style="font-size:9px; color:red;">( auto-generated )</span></label>
                                            <input type="number" class="form-control" value="<?php echo $user_age ?>" autocapitalize="off" autocomplete="off" name="age" id="age">
                                            <div class="invalid-feedback">
                                                Please provide your Age.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="CivilStatus" class="form-label">Civil Status</label>
                                            <select class="form-select form-control" name="civil_status" maxlength="6" autocomplete="off" id="civil_status">
                                                <option selected value="<?php echo $user_civil_status ?>"><?php echo $user_civil_status ?></option>
                                                <option value="SINGLE">SINGLE</option>
                                                <option value="MARRIED">MARRIED</option>
                                                <option value="SEPERATED">SEPERATED</option>
                                                <option value=">WIDOW/WIDOWER">WIDOW/WIDOWER</option>
                                                <option value="ANULLED">ANULLED</option>
                                                <option value="SOLO PARENT">SOLO PARENT</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a valid Civil Status.
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="email" class="form-label">Email<span> *</span></label>
                                            <input type="email" disabled class="form-control" autocapitalize="off" autocomplete="off" name="" id="" required value="<?php echo $user_email  ?>">
                                        </div>

                                    </div>

                                    <div class="addBtn">
                                        <button type="submit" class="warning" name="btn-update-profile" onclick="return IsEmpty(); sexEmpty();">Update</button>
                                    </div>
                                </form>
                            </div>

                            <div id="avatar" style="display: none;">
                                <form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" enctype="multipart/form-data" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">

                                        <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-user'></i> Change Avatar<p>Last update:
                                                <?php echo $user_last_update  ?></p></label>

                                        <div class="col-md-12">
                                            <label for="avatar" class="form-label">Update Avatar<span> *</span></label>
                                            <input type="file" class="form-control" name="avatar" id="avatar" style="height: 33px ;" required onchange="previewImage(event)">
                                            <div class="invalid-feedback">
                                                Please provide a Logo.
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="event_poster" class="form-label">Preview</label>
                                            <img id="poster-preview" style="max-width: 50%; margin-top: 10px; display: none;">
                                        </div>

                                        <div class="col-md-12" style="opacity: 0; padding-bottom: 1.3rem;">
                                            <label for="sname" class="form-label">Old Password<span> *</span></label>
                                            <input type="text" class="form-control">
                                            <div class="invalid-feedback">
                                                Please provide a Old Password.
                                            </div>
                                        </div>

                                    </div>

                                    <div class="addBtn">
                                        <button type="submit" class="btn-dark" name="btn-update-avatar" onclick="return IsEmpty(); sexEmpty();">Update</button>
                                    </div>
                                </form>
                            </div>


                            <div id="password" style="display: none;">
                                <form action="controller/profile-controller.php?id=<?php echo $user_id ?>" method="POST" class="row gx-5 needs-validation" name="form" onsubmit="return validate()" novalidate style="overflow: hidden;">
                                    <div class="row gx-5 needs-validation">

                                        <label class="form-label" style="text-align: left; padding-top: .5rem; padding-bottom: 1rem; font-size: 1rem; font-weight: bold;"><i class='bx bxs-key'></i> Change Password<p>Last update:
                                                <?php echo $user_last_update  ?></p></label>

                                        <div class="col-md-12">
                                            <label for="old_pass" class="form-label">Old Password<span> *</span></label>
                                            <input type="password" class="form-control" autocapitalize="on" autocomplete="off" name="old_password" id="old_pass" required>
                                            <div class="invalid-feedback">
                                                Please provide a Old Password.
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="new_pass" class="form-label">New Password<span> *</span></label>
                                            <input type="password" class="form-control" autocapitalize="on" autocomplete="off" name="new_password" id="new_pass" required>
                                            <div class="invalid-feedback">
                                                Please provide a New Password.
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="confirm_pass" class="form-label">Confirm Password<span> *</span></label>
                                            <input type="password" class="form-control" autocapitalize="on" autocomplete="off" name="confirm_password" id="confirm_pass" required>
                                            <div class="invalid-feedback">
                                                Please provide a Confirm Password.
                                            </div>
                                        </div>

                                    </div>

                                    <div class="addBtn">
                                        <button type="submit" class="btn-dark" name="btn-update-password" onclick="return IsEmpty(); sexEmpty();">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <?php echo $footer_dashboard->getFooterDashboard() ?>
    <?php include_once '../../config/sweetalert.php'; ?>
</body>

</html>