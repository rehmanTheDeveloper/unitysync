<?php

header("Location: user.all.php");
exit();

session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php");                           #
$license_path = "license/".$_SESSION['license_username']."/license.json"; #
require("auth/license.validate.functions.php");                    #
require("temp/validate.license.temp.php");                         #
#################### Login & License Validation ####################

####################### Database Connection #######################
require("auth/config.php");                                       #
require("auth/functions.php");                                    #
$conn = conn("localhost", "root", "", "unitySync");             #
####################### Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user-role, view-user-role") != true) {
    header("Location: user.all.php?m=not_allowed");
    exit();
}
################################ Role Validation ################################

$query = "SELECT * FROM `roles` WHERE `project_id` = '".$_SESSION['project']."' AND `name` != 'super-admin' AND `name` != '".$_SESSION['role']."';";
$roles = fetch_Data($conn, $query);

$title = "User Roles";
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
                    <li class="breadcrumb-item"><a href="#">Configuration</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Roles</li>
                </ol>
            </nav>
            <div class="btn-group">
                <?php
                ################################ Role Validation ################################
                if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user-role") === true) { #
                ################################ Role Validation ################################
                ?>
                <a class="btn btn-outline-gray-600" href="user.role.add.php">Add User Role</a>
                <?php } ?>
                <a class="btn btn-outline-gray-600" href="user.all.php">View Users</a>
            </div>
        </div>

        <?php
        ################################ Role Validation ################################
        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user-role") === true) { #
        ################################ Role Validation ################################
        ?>
        <!-- Datatable -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-4 rounded" id="dummyTable">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0 rounded-start">#</th>
                                <th class="border-0">Roles</th>
                                <th class="border-0 rounded-end" style="text-align: end;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($roles)) {
                            foreach ($roles as $key => $role) { 
                                if ($_SESSION['role'] != $role['name']) { ?>
                            <tr>
                                <td><a class="text-primary fw-bold"><?=$key+1?></a></td>
                                <td>
                                    <?=$role['name']?>
                                </td>
                                <td style="gap: 20px;" class="d-flex flex-row justify-content-end">
                                <?php if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user-role, delete-user-role") == true) { ?>
                                    <?php if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user-role") == true) { ?>
                                    <a data-bs-toggle="tooltip" data-bs-original-title="Edit" aria-label="Edit"
                                        href="user.role.edit.php?i=<?=$role['id']?>">
                                        <svg class="icon icon-xs text-gray-700" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                            </path>
                                            <path fill-rule="evenodd"
                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <?php } ?>
                                    <?php if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user-role") == true) { ?>
                                    <div class="btn-group">
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
                                            <a class="dropdown-item d-flex align-items-center"
                                                href="comp/role.delete.php?i=<?=$role['id']?>">
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
                                    <?php } } else { ?>
                                        <span class="badge bg-danger">Not Allowed</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } } } else { ?>
                            <tr>
                                <td class="text-center fw-bold" colspan="3">No User Role has been Added ...</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="thead-light">
                            <tr>
                                <th class="border-0 rounded-start">#</th>
                                <th class="border-0">Roles</th>
                                <th class="border-0 rounded-end" style="text-align: end;">Actions</th>
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

    <?php require('temp/script.temp.php'); ?>
    <script>
    $(document).ready(function() {
        <?php if (isset($_GET['m'])) { ?>
        <?php if ($_GET['m'] == 'role_add_not_allow') { ?>
            notify("error", "You are currently not Allowed to Add role ...");
        <?php } elseif ($_GET['m'] == 'add_true') { ?>
            notify("success", "Role Created Successfully ...");
        <?php } elseif ($_GET['m'] == 'add_false') { ?>
            notify("error", "Something's Wrong, Report Error ...");
        <?php } elseif ($_GET['m'] == 'role_view_not_allow') { ?>
            notify("error", "You are currently not Allowed to View Roles ...");
        <?php } elseif ($_GET['m'] == 'role_edit_not_allow') { ?>
            notify("error", "You are currently not Allowed to Edit Role ...");
        <?php } elseif ($_GET['m'] == 'not_found') { ?>
            notify("error", "No Role Found ...");
        <?php } elseif ($_GET['m'] == 'edit_true') { ?>
            notify("success", "Role Has been Modified ...");
        <?php } elseif ($_GET['m'] == 'edit_false') { ?>
            notify("error", "Something's Wrong, Report Error ...");
        <?php } elseif ($_GET['m'] == 'role_delete_not_allow') { ?>
            notify("error", "You are currently not Allowed to Delete Role ...");
        <?php } elseif ($_GET['m'] == 'user_exist') { ?>
            notify("error", "Role Cannot be deleted, User Exist ...");
        <?php } elseif ($_GET['m'] == 'delete_true') { ?>
            notify("success", "Role Deleted Successfully ...");
            <?php } elseif ($_GET['m'] == 'delete_false') { ?>
            notify("error", "Something's Wrong, Report Error ...");
        <?php } ?>
    <?php } ?>
    });
    </script>
</body>

</html>