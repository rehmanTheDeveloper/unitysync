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
$conn = conn("localhost", "root", "", "pine-valley");              #
######################## Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user") != true && validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user") != true) {
    header("Location: user.all.php?message=role_add_not_allow");
    exit();
}
################################ Role Validation ################################

$query = "SELECT * FROM `roles` WHERE `project_id` = '".$_SESSION['project']."' AND `name` != 'super-admin';";
$roles = fetch_Data($conn, $query);


$title = "Add User";
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
                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
                </ol>
            </nav>
            <a class="btn btn-outline-gray-600" href="user.all.php">Manage Users</a>
        </div>

        <?php 
        ################################ Role Validation ################################
        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user") == true) { #
        ################################ Role Validation ################################
        ?>
        <div class="card card-body border-0 shadow mb-4">
            <h2 class="h5 mb-4">General information</h2>
            <form method="POST" action="comp/user.insert.php" id="form-row" autocomplete="off">
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
                            <input class="form-control" id="fName" name="fName" type="text" required />
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-4">
                        <div class="mb-3">
                            <label for="lName">Last Name</label>
                            <input class="form-control" id="lName" name="lName" type="text" required />
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="text" required />
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="phoneNo">Contact No.</label>
                            <input type="tel" name="phoneNo" id="phoneNo" class="form-control" value="+92" />
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="altPhoneNo">Alternative Contact No.</label>
                            <input type="tel" name="altPhoneNo" id="altPhoneNo" class="form-control" />
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label for="">Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="status" id="status"
                                checked />
                            <label class="form-check-label" for="">
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
                            <?php if (!empty($roles)) { ?>
                            <select class="form-select" name="role" id="role" required>
                                <option value="" selected>Select</option>
                                <?php foreach ($roles as $key => $value) { ?>
                                <option value="<?=$value['id']?>"><?=$value['name']?></option>
                                <?php } ?>
                            </select>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="mb-3">
                            <label for="username">Username</label>
                            <input class="form-control" id="username" name="username" type="text" required />
                            <div id="username_feedback" class="form-text"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-3">
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input class="form-control" id="password" name="password" type="password" required />
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-3">
                        <div class="mb-3">
                            <label for="confirm_password">Confirm password</label>
                            <input class="form-control" id="confirm_password" name="confirm_password" type="password" />
                            <div id="confirm_password_feedback" class="form-text"></div>
                        </div>
                    </div>
                </div>
                <hr />
                <h2 class="h5 mb-4">Other Details</h2>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="dateOfBirth">Date of Birth</label>
                            <input type="date" name="dateOfBirth" id="dateOfBirth" class="form-control" />
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="gender">Gender</label>
                            <select class="form-select" name="gender" id="gender">
                                <option value="" selected>Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="not confirm">Not Confirm</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="martialStatus">Martial Status</label>
                            <select class="form-select" name="martialStatus" id="martialStatus">
                                <option value="" selected>Select</option>
                                <option value="married">Married</option>
                                <option value="engaged">Engaged</option>
                                <option value="single">Single</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label for="bloodGroup">Blood Group</label>
                            <input type="text" name="bloodGroup" id="bloodGroup" class="form-control" value="A+" />
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <input type="submit" name="submit" value="Add User" class="btn btn-outline-gray-600 my-3" />
                    </div>
                </div>
            </form>
        </div>
        <?php require('temp/footer.temp.php'); ?>
        <?php } else { ?>
        <div style="height: 80vh;" class="position-relative">
            <img id="accessDenied" class="position-absolute start-0 end-0 top-0 bottom-0 m-auto"
                src="assets/img/access-blocked.png" alt="" height="460" />
        </div>
        <?php } ?>

    </main>

    <?php require('temp/script.temp.php'); ?>
    <?php 
    ################################ Role Validation ################################
    if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user") == true) {             #
    ################################ Role Validation ################################
    ?>
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
            return;
        } else {
            $('#confirm_password_feedback').text('Password Matches.');
            $('#confirm_password_feedback').removeClass('text-danger');
            $('#confirm_password_feedback').addClass('text-success');
        }

        $.ajax({
            url: 'ajax/user.credentials.validate.php',
            type: 'POST',
            data: {
                username: username
            },
            success: function(response) {
                if (response === 'valid') {
                    // Registration is valid, handle form submission
                    $('#username_feedback').removeClass('text-danger');
                    $('#username_feedback').text('');
                    $('#form-row').off('submit').submit();
                } else if (response === "loggedIn") {
                    location.reload();
                } else {
                    $('#username_feedback').text('Username Already Registered.');
                    $('#username_feedback').addClass('text-danger');
                    console.log(response);
                }
            },
            error: function() {
                console.log('An error occurred. Please try again.');
            }
        });
    });
    </script>
    <?php } ?>
</body>

</html>