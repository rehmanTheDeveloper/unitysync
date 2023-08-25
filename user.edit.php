<?php

session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php");                           #
$license_path = "licenses/".$_SESSION['license_username']."/license.json"; #
require("auth/license.validate.functions.php");                    #
require("temp/validate.license.temp.php");                         #
#################### Login & License Validation ####################

######################## Database Connection #######################
require("auth/config.php");                                        #
require("auth/functions.php");                                     #
$conn = conn("localhost", "root", "", "communiSync");              #
######################## Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user") != true) {
    header("Location: user.all.php?message=user_edit_not_allow");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['id'])) {
    header("Location: user.all.php?message=not_found");
    exit();
}

$query = "SELECT * FROM `roles` WHERE `project_id` = '".$_SESSION['project']."' AND `name` != 'super-admin';";
$roles = fetch_Data($conn, $query);

$query = "SELECT * FROM `users` WHERE `id` = '".$_GET['id']."' AND `project_id` = '".$_SESSION['project']."' AND `role` != 'super-admin';";
$user = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (empty($user)) {
    header("Location: user.all.php?message=no_user");
    exit();
}

$prefixes = array(
    "Mr.",
    "Mrs."
);
$genders = array(
    "male",
    "female",
    "not confirm"
);
$martial_status = array(
    "single",
    "married",
    "engaged"
);


$title = "Edit User - ".$user['f_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require('temp/head.temp.php'); ?>
</head>

<body>
    <?php require('temp/aside.temp.php'); ?>

    <main class="content">
        <?php require('temp/nav.temp.php'); ?>

        <div class="py-3 pb-2 d-flex justify-content-between align-items-center">
            <nav aria-label="breadcrumb" class="d-none d-md-flex align-items-center">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a>Configuration</a></li>
                    <li class="breadcrumb-item"><a href="user.all.php">User Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User - <?=$user['f_name']?></li>
                </ol>
            </nav>
            <a class="btn btn-outline-gray-600" href="user.all.php">Manage Users</a>
        </div>

        <div class="card card-body border-0 shadow mb-4">
            <h2 class="h5 mb-4">General information</h2>
            <form method="POST" action="comp/user.edit.php" id="form-row" autocomplete="off">
                <div class="row">
                    <div class="col-md-2 col-sm-4">
                        <div class="mb-3">
                            <label for="prefix">Prefix</label>
                            <select class="form-select" name="prefix" id="prefix">
                                <option value="Mr." selected>Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-4">
                        <div class="mb-3">
                            <label for="fName">First Name</label>
                            <input class="form-control" id="fName" name="fName" type="text" value="<?=$user['f_name']?>"
                                required />
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-4">
                        <div class="mb-3">
                            <label for="lName">Last Name</label>
                            <input class="form-control" id="lName" name="lName" type="text" value="<?=$user['s_name']?>"
                                required />
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="email" value="<?=$user['email']?>"
                                required />
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="phoneNo">Contact No.</label>
                            <input type="tel" name="phoneNo" id="phoneNo" class="form-control"
                                value="<?=$user['phone_no']?>" />
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label for="">Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1"
                                <?=($user['status'] == 1)?"checked":""?> name="status" id="status" />
                            <label class="form-check-label" for="status">
                                Is Active ?
                            </label>
                        </div>
                    </div>
                </div>
                <hr />
                <h2 class="h5 mb-4">Roles and Permission</h2>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="role">Role</label>
                            <select class="form-select" name="role" id="role" required>
                                <?php foreach ($roles as $role) { 
                                    if ($user['role'] == $role['id']) {?>
                                <option value="<?=$role['id']?>" selected><?=$role['name']?></option>
                                <?php } else { ?>
                                <option value="<?=$role['id']?>"><?=$role['name']?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="mb-3">
                            <label for="username">Username <span
                                    class="text-danger">(<b>Unchangeable</b>)</span></label>
                            <input class="form-control bg-white" type="text" value="<?=$user['username']?>" readonly />
                            <div id="username_feedback" class="form-text"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-3">
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input class="form-control" id="password" name="password" type="password"
                                value="<?=$user['password']?>" />
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-3">
                        <div class="mb-3">
                            <label for="confirm_password">Confirm password</label>
                            <input class="form-control" id="confirm_password" name="confirm_password" type="password"
                                value="<?=$user['password']?>" />
                            <div id="confirm_password_feedback" class="form-text"></div>
                        </div>
                    </div>
                </div>
                <hr />
                <h2 class="h5 mb-4">Other Details</h2>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="dateOfBirth">Birthday</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <svg class="icon icon-xs" fill="currentColor" viewbox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                <input data-datepicker class="form-control" id="dateOfBirth" name="dateOfBirth"
                                    type="text" placeholder="dd/mm/yyyy" value="<?=$user['date_of_birth']?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="gender">Gender</label>
                            <select class="form-select" name="gender" id="gender">
                                <?php foreach ($genders as $gender) { 
                                    if ($user['gender'] == $gender) {?>
                                <option value="<?=$gender?>" selected><?=$gender?></option>
                                <?php } else { ?>
                                <option value="<?=$gender?>"><?=$gender?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="martialStatus">Martial Status</label>
                            <select class="form-select" name="maritalStatus" id="maritalStatus">
                                <?php foreach ($martial_status as $status) { 
                                    if ($user['martial_status'] == $status) {?>
                                <option value="<?=$status?>" selected><?=$status?></option>
                                <?php } else { ?>
                                <option value="<?=$status?>"><?=$status?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="bloodGroup">Blood Group</label>
                            <input type="text" name="bloodGroup" id="bloodGroup" class="form-control"
                                value="<?=$user['blood_group']?>" />
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <input type="hidden" name="id" value="<?=$_GET['id']?>" />
                        <input type="submit" name="submit" value="Edit User" class="btn btn-outline-gray-600 my-3" />
                    </div>
                </div>
            </form>
        </div>
        <?php require('temp/footer.temp.php'); ?>

    </main>

    <?php require('temp/script.temp.php'); ?>
    <script>
    $('#form-row').submit(function(e) {
        e.preventDefault();

        var username = $('#username').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();

        // Validate if the passwords match
        if (password !== confirmPassword) {
            $('#confirm_password_feedback').text('Password do not match.');
            $('#confirm_password_feedback').removeClass('text-success');
            $('#confirm_password_feedback').addClass('text-danger');
            notify("error", "Password do not match.");
            return;
        } else {
            $('#confirm_password_feedback').text('Password Matches.');
            $('#confirm_password_feedback').removeClass('text-danger');
            $('#confirm_password_feedback').addClass('text-success');
            $('#form-row').off('submit').submit();
        }
    });
    <?php if (isset($_GET['message'])) { ?>

        <?php if ($_GET['message'] == 'pass_not_match') { ?>
            notify("error", "Password Doesn't Match ...");
        <?php } ?>

    <?php } ?>
    </script>
</body>

</html>