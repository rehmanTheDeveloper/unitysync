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
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user") != true) {
    header("Location: user.all.php?m=not_allowed");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['i'])) {
    header("Location: user.all.php?m=empty");
    exit();
}

$query = "SELECT * FROM `users` WHERE `u_id` = '".$_GET['i']."' AND `project_id` = '".$_SESSION['project']."' AND `role` != 'super-admin';";
$user = mysqli_fetch_assoc(mysqli_query($conn, $query));
if (empty($user)) {
    header("Location: user.all.php?m=no user");
    exit();
}

$query = "SELECT * FROM `activity` WHERE `UI` = '".$user['id']."' ORDER BY `id` DESC;";
$activities = fetch_Data($conn, $query);

// echo "<pre>";
// print_r($user);
// exit();


$title = "User - ".$user['f_name'];
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

        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3">
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
                    <li class="breadcrumb-item active" aria-current="page">User - <?=$user['f_name']?></li>
                </ol>
            </nav>
            <div class="btn-group">
                <a class="btn btn-outline-gray-800" href="user.all.php">Manage Users</a>
                <button class="btn btn-outline-gray-800 dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
                        </path>
                    </svg>
                    Other Options
                    <svg class="icon icon-xs ms-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1" style="">
                
                    <a class="dropdown-item d-flex align-items-center justify-content-center" data-bs-toggle="modal"
                        data-bs-target="#activity">
                        Activity
                    </a>
                    <?php
                    ################################ Role Validation ################################
                    if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user") === true) {
                    ################################ Role Validation ################################
                    ?>
                    <a class="dropdown-item d-flex align-items-center justify-content-center fw-bold text-success"
                        href="user.edit.php?i=<?=$user['id']?>">
                        Edit User
                    </a>
                    <?php } ?>
                    <?php
                    ################################ Role Validation ################################
                    if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") === true) {
                    ################################ Role Validation ################################
                    ?>
                    <div role="separator" class="dropdown-divider my-1"></div>
                    <a class="dropdown-item d-flex align-items-center justify-content-center fw-bold text-danger deleteBtn"
                        data-id="<?=$user['id']?>" data-name="<?=$user['f_name']?>">
                        Delete User
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row h-100">
                    <div class="col-12 col-md-4 mb-4">
                        <div class="card shadow border-0 text-center p-0 h-100">
                            <div class="profile-cover rounded-top"
                                data-background="https://images.hdqwalls.com/wallpapers/city-rain-blur-bokeh-effect-7w.jpg">
                            </div>
                            <div class="card-body pb-5">
                                <img src="uploads/profiles/<?=$user['img']?>"
                                    class="avatar-xl rounded-circle mx-auto mt-n7 mb-4 bg-primary"
                                    alt="<?=$user['f_name']?> Portrait">
                                <h4 class="h3"><?=$user['f_name']." ".$user['s_name']?></h4>
                                <h5 class="fw-normal text-capitalize">
                                    <?=$user['role']?>
                                </h5>
                                <p class="text-gray mb-4 text-capitalize"><?=$user['country']?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 mb-4">
                        <div class="card shadow border-0 p-0">
                            <div class="card-body pb-5">
                                <h4 class="h3 text-center">User Details</h4>

                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Label</th>
                                            <th class="border-0 rounded-end text-end">Details
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td class="fw-bold text-end"><?=$user['f_name']." ".$user['s_name']?></td>
                                        </tr>
                                        <tr>
                                            <td>Role</td>
                                            <td class="fw-bolder text-capitalize text-end">
                                                <?php echo $user['role']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td class="fw-bold text-end">
                                                <?=$user['email']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td class="fw-bold text-end">
                                                <?="+92 ".phone_no_format($user['phone_no'])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td class="fw-bold text-end">
                                                <?php if ($user['status'] == 1) { ?>
                                                <span class="fw-normal text-success">Active</span>
                                                <?php } elseif ($user['status'] == 0) { ?>
                                                <span class="fw-normal text-warning">Inactive</span>
                                                <?php } elseif ($user['status'] == 2) { ?>
                                                <span class="fw-normal text-danger">Suspended</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Date of Birth</td>
                                            <td class="fw-bold text-end">
                                                <?=(!empty($user['date_of_birth']))?$user['date_of_birth']:"--- NOT PROVIDED ---"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Gender</td>
                                            <td class="fw-bold text-end">
                                                <?=(!empty($user['gender']))?$user['gender']:"--- NOT PROVIDED ---"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Martial Status</td>
                                            <td class="fw-bold text-end">
                                                <?=(!empty($user['martial_status']))?$user['martial_status']:"--- NOT PROVIDED ---"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Blood Group</td>
                                            <td class="fw-bold text-end">
                                                <?=(!empty($user['blood_group']))?$user['blood_group']:"--- NOT PROVIDED ---"?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require('temp/footer.temp.php'); ?>
    </main>

    <div class="modal fade" id="activity" tabindex="-1" role="dialog" aria-labelledby="modal-default"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">All Activities</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-centered table-hover table-nowrap mb-0 rounded" id="datatable">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0 rounded-start">#</th>
                                <th class="border-0">Date & Time</th>
                                <th class="border-0">Activity</th>
                                <th class="border-0 text-end rounded-end">Moment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($activities)){
                                foreach ($activities as $key => $activity) { ?>
                            <tr>
                                <td class="fw-bolder">
                                    <?=$key+1?>
                                </td>
                                <td><?=date("d M, Y h:i a", strtotime($activity['created_date']))?></td>
                                <td><?=$activity['message']?></td>
                                <td class="fw-bold text-end">
                                    <?php 
                                        $activity_time = timeInSeconds($activity['created_date']);
                                        $activity_seconds = time_since($activity_time);
                                        echo $activity_seconds;
                                    ?> ago
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td class="fw-bold text-center" colspan="4">No Activity.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link text-gray ms-auto" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php if( !empty($user) ){ ?>
    <!-- Modal Content -->
    <div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="delete user"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">Delete User</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete User ( <span id="selectedUser"></span> ) ?</p>
                    <input type="hidden" id="userID" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">No</button>
                    <button type="button" id="confirmDeleteBtn" data-target="#confirmationCode"
                        class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmationCode" tabindex="-1" role="dialog" aria-labelledby="delete user"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">Confirmation Code</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please enter the sum of the following numbers:</p>
                    <p><span class="number1"></span> + <span class="number2"></span> =</p>
                    <div class="mb-3">
                        <input class="form-control" type="text" id="confirmation-input" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="confirmCodeAns" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Modal Content -->
    <?php } ?>

    <?php require('temp/script.temp.php'); ?>
    <script>
    <?php if (!empty($activities)) { ?>
    var dataTableEl = document.getElementById('datatable');
    if (dataTableEl) {
        const dataTable = new simpleDatatables.DataTable(dataTableEl);
    }
    <?php } ?>

    $(document).ready(function() {

        // Randomly generate two numbers
        var number1 = Math.floor(Math.random() * 10) + 1;
        var number2 = Math.floor(Math.random() * 10) + 1;
        $('.number1').text(number1);
        $('.number2').text(number2);

        // Show confirmation dialog on delete button click
        $('.deleteBtn').click(function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');

            // Set user name in delete confirmation modal
            $('#selectedUser').text(userName);
            $('#userID').val(userId);

            console.log(userName);
            console.log(userId);

            $('#deleteUser').modal('show');
        });

        // Show confirmation code dialog on confirm delete button click
        $('#confirmDeleteBtn').click(function() {
            $('#deleteUser').modal('hide');
            $('#confirmationCode').modal('show');
        });

        // Handle confirmation code submission
        $('#confirmCodeAns').click(function() {
            // Get user input and calculate expected result
            var input = $('#confirmation-input').val();
            var expected = number1 + number2;

            // Check if input is correct
            if (input == expected) {
                var userId = $('#userID').val();
                window.location.href = 'comp/user.delete.php?id=' + userId;
            } else {
                // Show error message and generate new numbers
                notify("error", "The sum is incorrect. Please try again.");
                number1 = Math.floor(Math.random() * 10) + 1;
                number2 = Math.floor(Math.random() * 10) + 1;
                $('.number1').text(number1);
                $('.number2').text(number2);
                $('#confirmation-input').val('');
            }
        });
    });
    </script>
</body>

</html>