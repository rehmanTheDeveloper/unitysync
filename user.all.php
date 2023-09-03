<?php

session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php"); #
$license_path = "licenses/" . $_SESSION['license_username'] . "/license.json"; #
require("auth/license.validate.functions.php"); #
require("temp/validate.license.temp.php"); #
#################### Login & License Validation ####################

####################### Database Connection #######################
require("auth/config.php"); #
require("auth/functions.php"); #
$conn = conn("localhost", "root", "", "communiSync");             #
####################### Database Connection #######################

$query = "SELECT * FROM `users` WHERE `project_id` = '" . $_SESSION['project'] . "' AND `role` != 'super-admin' AND `u_id` != '".$_SESSION['id']."';";
$users = fetch_Data($conn, $query);

$title = "All Users";
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
                    <li class="breadcrumb-item"><a href="#">User Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
            <div class="btn-group">
                <?php
                ################################ Role Validation ################################
                if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user") === true) {
                ################################ Role Validation ################################
                ?>
                <a class="btn btn-outline-gray-600" href="user.config.php">Add User</a>
                <?php } ?>
                <?php
                ################################ Role Validation ################################
                if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user-role, view-user-role") === true) {
                ################################ Role Validation ################################
                ?>
                <!-- <a class="btn btn-outline-gray-600" href="user.role.all.php">View User Roles</a> -->
                <?php } ?>
            </div>
        </div>

        <?php
        ################################ Role Validation ################################
        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user") === true) {
        ################################ Role Validation ################################
        ?>
        <!-- Datatable -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table user-table table-hover align-items-center" id="dummyTable">
                        <thead>
                            <tr>
                                <th class="border-bottom">
                                    #
                                </th>
                                <th class="border-bottom">Name</th>
                                <th class="border-bottom">Date Created</th>
                                <!-- <th class="border-bottom">Role</th> -->
                                <th class="border-bottom">Status</th>
                                <th class="border-bottom">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)) {
                                    foreach ($users as $key => $user) {?>
                            <tr>
                                <td>
                                    <?= $key + 1 ?>
                                </td>
                                <td>
                                    <a href="#" class="d-flex align-items-center">
                                        <img src="uploads/profiles/<?=$user['img']?>" class="avatar rounded-circle me-3"
                                            alt="Avatar">
                                        <div class="d-block">
                                            <span class="fw-bold">
                                                <?= $user['f_name'] . " " . $user['s_name'] ?>
                                            </span>
                                            <div class="small text-gray">
                                                <?= $user['email'] ?>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <span class="fw-normal">
                                        <?= date("d M Y", strtotime($user['created_date'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['status'] == 1) { ?>
                                    <span class="fw-normal d-flex align-items-center">
                                        <svg class="icon icon-xs text-success me-1" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Active
                                    </span>
                                    <?php } elseif ($user['status'] == 0) { ?>
                                    <span class="fw-normal d-flex align-items-center">
                                        <svg class="icon icon-xs text-danger me-1" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Inactive
                                    </span>
                                    <?php } elseif ($user['status'] == 2) { ?>
                                    <span class="fw-normal d-flex align-items-center">
                                        <svg class="icon icon-xs text-danger me-1" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11 6a3 3 0 11-6 0 3 3 0 016 0zM14 17a6 6 0 00-12 0h12zM13 8a1 1 0 100 2h4a1 1 0 100-2h-4z">
                                            </path>
                                        </svg>
                                        Suspended
                                    </span>
                                    <?php } ?>
                                </td>
                                <td>
                                <?php
                                ################################ Role Validation ################################
                                if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user, edit-user, delete-user") === true) {
                                ################################ Role Validation ################################
                                ?>
                                    <div class="btn-group dropstart">
                                        <button
                                            class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                                                </path>
                                            </svg>
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                            style="">
                                            <?php
                                            ################################ Role Validation ################################
                                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user") === true) {
                                            ################################ Role Validation ################################
                                            ?>
                                            <?php if ($user['status'] == 0) { ?>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="comp/user.edit.php?status=1&id=<?= $user['u_id'] ?>">
                                                <svg class="dropdown-icon text-success me-2 fs-5" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Active
                                            </a>
                                            <?php } else { ?>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="comp/user.edit.php?status=0&id=<?= $user['u_id'] ?>">
                                                <svg class="dropdown-icon text-danger me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Inactive
                                            </a>
                                            <?php } ?>
                                            <?php } ?>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="user.view.php?i=<?= $user['u_id'] ?>">
                                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                View Details
                                            </a>
                                            <?php
                                            ################################ Role Validation ################################
                                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user") === true) {
                                            ################################ Role Validation ################################
                                            ?>
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="user.edit.php?i=<?= $user['u_id'] ?>">
                                                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                    ################################ Role Validation ################################
                                    if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") === true) {
                                    ################################ Role Validation ################################
                                    ?>
                                    <div class="btn-group dropstart">
                                        <button
                                            class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            title="Delete">
                                            <svg class="icon icon-xs text-danger" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                            style="">
                                            <a class="dropdown-item d-flex align-items-center deleteBtn"
                                                data-id="<?= $user['u_id'] ?>" data-name="<?= $user['f_name'] ?>">
                                                <svg class="dropdown-icon text-success me-2 fs-5" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Yes
                                            </a>
                                            <a class="dropdown-item d-flex align-items-center"
                                                data-bs-dismiss="dropdown">
                                                <svg class="dropdown-icon text-danger me-2 fs-5" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                                    data-bs-original-title="Delete" aria-label="Delete">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                No
                                            </a>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php } else { ?>
                                    <span class="badge bg-danger">Not Allowed</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php }
                                } else { ?>
                            <tr>
                                <td class="fw-bold text-center" colspan="6">No User has been Added ...</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="border-bottom">
                                    #
                                </th>
                                <th class="border-bottom">Name</th>
                                <th class="border-bottom">Date Created</th>
                                <!-- <th class="border-bottom">Role</th> -->
                                <th class="border-bottom">Status</th>
                                <th class="border-bottom">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <?php require('temp/footer.temp.php'); ?>
        <?php } else { ?>
        <div style="height: 80vh;" class="position-relative">
            <img id="accessDenied" class="position-absolute start-0 end-0 top-0 bottom-0 m-auto"
                src="assets/img/access-blocked.png" alt="" height="460" />
        </div>
        <?php } ?>
    </main>

    <?php if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") === true) { ?>
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

    <?php if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") === true) { ?>
    <script>
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
                window.location.href = 'comp/user.delete.php?i=' + userId;
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
    <?php if (isset($_GET['m'])) { ?>
        <?php if ($_GET['m'] == 'user_add_not_allow') { ?>
            notify("error", "You are currently not Allowed to Add user ...");
        <?php } elseif ($_GET['m'] == 'add_true') { ?>
            notify("success", "User Created Successfully ...");
        <?php } elseif ($_GET['m'] == 'add_false') { ?>
            notify("error", "Something's Wrong, Report Error ...");
        <?php } elseif ($_GET['m'] == 'not_found') { ?>
            notify("error", "No User Found ...");
        <?php } elseif ($_GET['m'] == 'user_view_not_allow') { ?>
            notify("error", "You are currently not Allowed to View Roles ...");
        <?php } elseif ($_GET['m'] == 'user_edit_not_allow') { ?>
            notify("error", "You are currently not Allowed to Edit Role ...");
        <?php } elseif ($_GET['m'] == 'edit_true') { ?>
            notify("success", "User Has been Modified ...");
        <?php } elseif ($_GET['m'] == 'edit_false') { ?>
            notify("error", "Something's Wrong, Report Error ...");
        <?php } elseif ($_GET['m'] == 'user_delete_not_allow') { ?>
            notify("error", "You are currently not Allowed to Delete Role ...");
        <?php } elseif ($_GET['m'] == 'user_exist') { ?>
            notify("error", "Role Cannot be deleted, User Exist ...");
        <?php } elseif ($_GET['m'] == 'delete_true') { ?>
            notify("success", "Role Deleted Successfully ...");
        <?php } elseif ($_GET['m'] == 'delete_false') { ?>
            notify("error", "Something's Wrong, Report Error ...");
        <?php } elseif ($_GET['m'] == 'status_updated_true') { ?>
            notify("success", "Status Updated ...");
        <?php } ?>
    <?php } ?>
    </script>
    <?php } ?>
</body>

</html>