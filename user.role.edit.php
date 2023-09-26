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
// ! Validation of viewing required page, If you can't view it then how can you edit it .. ??
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user-role") != true) {
    header("Location: user.role.all.php?m=role_view_not_allow");
    exit();
}
################################ Role Validation ################################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user-role") != true) {
    header("Location: user.role.all.php?m=role_edit_not_allow");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['i'])) {
    header("Location: user.role.all.php?m=not_found");
    exit();
}

$query = "SELECT * FROM `roles` WHERE `id` = '".$_GET['i']."' AND `project_id` = '".$_SESSION['project']."';";
$role = mysqli_fetch_assoc(mysqli_query($conn, $query));

if ($role['name'] == 'super-admin' || empty($role)) {
    header("Location: user.role.all.php");
    exit();
}

$query = "SELECT * FROM `role_permissions` WHERE `role` = '".$role['id']."' AND `project_id` = '".$_SESSION['project']."';";
$role_permissions = fetch_Data($conn, $query);


$title = "Edit Role";

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
                    <li class="breadcrumb-item active" aria-current="page">User Role - <?=$role['name']?></li>
                </ol>
            </nav>
            <a class="btn btn-outline-gray-600" href="user.role.all.php">View User Roles</a>
        </div>

        <div class="card card-body border-0 shadow mb-4">
            <h2 class="h5 mb-4">General information</h2>
            <form method="POST" action="comp/role.edit.php" autocomplete="off" id="formRole">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="roleName">Role Name</label>
                            <input class="form-control" id="roleName" name="roleName" type="text"
                                value="<?=$role['name']?>" required />
                            <input type="hidden" name="id" value="<?=$_GET['i']?>" />
                            <div class="form-text text-danger" id="roleNameFeedback"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr />
                        <h5>Permissions</h5>
                        <hr />
                        <div class="row gy-4">
                            <div class="col-xxl-3 col-xl-4 col-sm-6 col-12" id="role-administrator">
                                <div class="card shadow h-100">
                                    <div class="card-body">
                                        <h4>Administration</h4>
                                        <div class="form-check ms-2">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="select-all-administrator" />
                                            <label class="form-check-label fw-bolder" for="select-all-administrator">
                                                Select All
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-user-management"
                                                name="role[]" id="view-user-management" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-user-management') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-user-management">
                                                View User Management
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-user"
                                                name="role[]" id="add-user" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-user') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-user">
                                                Add User
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-user"
                                                name="role[]" id="view-user" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-user') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-user">
                                                View User
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-user"
                                                name="role[]" id="edit-user" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'edit-user') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="edit-user">
                                                Edit User
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-user"
                                                name="role[]" id="delete-user" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'delete-user') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="delete-user">
                                                Delete User
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-user-role"
                                                name="role[]" id="add-user-role" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-user-role') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-user-role">
                                                Add User Roles
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-user-role"
                                                name="role[]" id="view-user-role" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-user-role') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-user-role">
                                                View User Roles
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-user-role"
                                                name="role[]" id="edit-user-role" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'edit-user-role') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="edit-user-role">
                                                Edit User Roles
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-user-role"
                                                name="role[]" id="delete-user-role" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'delete-user-role') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="delete-user-role">
                                                Delete User Roles
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6 col-12" id="role-configuration">
                                <div class="card shadow h-100">
                                    <div class="card-body">
                                        <h4>Configuration</h4>
                                        <div class="form-check ms-2">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="select-all-configuration" />
                                            <label class="form-check-label fw-bolder" for="select-all-configuration">
                                                Select All
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-project"
                                                name="role[]" id="view-project" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-project') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-project">
                                                View Project
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-project"
                                                name="role[]" id="edit-project" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'edit-project') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="edit-project">
                                                Edit Project
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6 col-12" id="role-master-entry">
                                <div class="card shadow h-100">
                                    <div class="card-body">
                                        <h4>Master Entry</h4>
                                        <div class="form-check ms-2">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="select-all-master-entry" />
                                            <label class="form-check-label fw-bolder" for="select-all-master-entry">
                                                Select All
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-account"
                                                name="role[]" id="add-account" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-account') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-account">
                                                Add Account
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-account"
                                                name="role[]" id="view-account" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-account') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-account">
                                                View Account
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-account"
                                                name="role[]" id="edit-account" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'edit-account') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="edit-account">
                                                Edit Account
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-account"
                                                name="role[]" id="delete-account" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'delete-account') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="delete-account">
                                                Delete Account
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-property"
                                                name="role[]" id="add-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-property">
                                                Add Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-property"
                                                name="role[]" id="edit-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'edit-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="edit-property">
                                                Edit Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-property"
                                                name="role[]" id="view-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-property">
                                                View Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-property"
                                                name="role[]" id="delete-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'delete-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="delete-property">
                                                Delete Property
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6 col-12" id="others">
                                <div class="card shadow h-100">
                                    <div class="card-body">
                                        <h4>Others</h4>
                                        <div class="form-check ms-2">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="select-all-others" />
                                            <label class="form-check-label fw-bolder" for="select-all-others">
                                                Select All
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="dashboard"
                                                name="role[]" id="dashboard" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'dashboard') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="dashboard">
                                                Dashboard
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="print" name="role[]"
                                                id="print" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'print') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="print">
                                                Print/Export
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-ledger"
                                                name="role[]" id="view-ledger" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-ledger') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-ledger">
                                                View Ledger
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-activity"
                                                name="role[]" id="view-activity" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-activity') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-activity">
                                                View Activity
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div class="col-xxl-3 col-xl-4 col-sm-6 col-12" id="role-property-transactions">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h4>Property Transactions</h4>
                                        <div class="form-check ms-2">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="select-all-property-transactions" />
                                            <label class="form-check-label fw-bolder"
                                                for="select-all-property-transactions">
                                                Select All
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-sale-property"
                                                name="role[]" id="add-sale-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-sale-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-sale-property">
                                                Add Sale Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-sale-property"
                                                name="role[]" id="view-sale-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-sale-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-sale-property">
                                                View Sale Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-sale-property"
                                                name="role[]" id="edit-sale-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'edit-sale-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="edit-sale-property">
                                                Edit Sale Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-sale-property"
                                                name="role[]" id="delete-sale-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'delete-sale-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="delete-sale-property">
                                                Delete Sale Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="add-transfer-property" name="role[]" id="add-transfer-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-transfer-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-transfer-property">
                                                Add Transfer Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="view-transfer-property" name="role[]" id="view-transfer-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-transfer-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-transfer-property">
                                                View Transfer Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-return-property"
                                                name="role[]" id="add-return-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-return-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-return-property">
                                                Add Return Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-return-property"
                                                name="role[]" id="view-return-property" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-return-property') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-return-property">
                                                View Return Property
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6 col-12" id="role-payment-transactions">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h4>Payment Transactions</h4>
                                        <div class="form-check ms-2">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="select-all-payment-transactions" />
                                            <label class="form-check-label fw-bolder"
                                                for="select-all-payment-transactions">
                                                Select All
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-payment-pay"
                                                name="role[]" id="add-payment-pay" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-payment-pay') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-payment-pay">
                                                Add Payment Pay
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-payment-pay"
                                                name="role[]" id="view-payment-pay" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-payment-pay') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-payment-pay">
                                                View Payment Pay
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-payment-pay"
                                                name="role[]" id="delete-payment-pay" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'delete-payment-pay') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="delete-payment-pay">
                                                Delete Payment Pay
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-payment-receive"
                                                name="role[]" id="add-payment-receive" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-payment-receive') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-payment-receive">
                                                Add Payment Receive
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-payment-receive"
                                                name="role[]" id="view-payment-receive" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-payment-receive') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-payment-receive">
                                                View Payment Receive
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="delete-payment-receive" name="role[]" id="delete-payment-receive" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'delete-payment-receive') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="delete-payment-receive">
                                                Delete Payment Receive
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-payment-transfer"
                                                name="role[]" id="add-payment-transfer" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'add-payment-transfer') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="add-payment-transfer">
                                                Add Payment Transfer
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="view-payment-transfer" name="role[]" id="view-payment-transfer" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'view-payment-transfer') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="view-payment-transfer">
                                                View Payment Transfer
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="delete-payment-transfer" name="role[]"
                                                id="delete-payment-transfer" <?php foreach ($role_permissions as $key => $value) {
                                                        if ($value['permission'] == 'delete-payment-transfer') {
                                                            echo "checked";
                                                        }
                                                    }
                                                ?> />
                                            <label class="form-check-label" for="delete-payment-transfer">
                                                Delete Payment Transfer
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <input type="submit" name="submit" value="Save Changes" class="btn btn-outline-gray-600 my-3" />
                    </div>
                </div>
            </form>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>

    <?php require('temp/script.temp.php'); ?>
    <script>
    $(document).ready(function() {
        permissions = [
            "role-administrator",
            "role-configuration",
            "role-master-entry",
            "role-property-transactions",
            "role-payment-transactions",
            "others"
        ];

        //! jQuery function to handle the "Select All" checkboxes
        function setupSelectAllCheckbox(containerId) {
            const container = $('#' + containerId);
            const selectAllCheckbox = container.find('input[type="checkbox"]').first();
            const checkboxes = container.find('input[type="checkbox"]').not(selectAllCheckbox);

            selectAllCheckbox.on('change', function() {
                checkboxes.prop('checked', this.checked);
            });

            checkboxes.on('change', function() {
                // Check if all other checkboxes are checked and update the "Select All" checkbox accordingly
                selectAllCheckbox.prop('checked', checkboxes.length === checkboxes.filter(':checked')
                    .length);
            });
        }
        permissions.forEach(e => {
            setupSelectAllCheckbox(e);
        });

        $('form').submit(function(e) {
            e.preventDefault();

            var role_name = $('#roleName').val();
            const checkedCheckboxes = $('form input[type="checkbox"]:checked');
            if (checkedCheckboxes.length < 1) {
                notify("error", "Check At least 5 Permissions ...");
                checkedCheckboxes.removeClass("is-invalid");
                $('form input[type="checkbox"]:not(:checked)').addClass("is-invalid");
                return;
            } else {
                $('form input[type="checkbox"]').removeClass("is-invalid");
                $("form").off('submit').submit();
            }
        });
    });
    </script>
</body>

</html>