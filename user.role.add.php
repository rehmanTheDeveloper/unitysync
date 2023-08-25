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
$conn = conn("localhost", "root", "", "communiSync");                    #
######################## Database Connection #######################


$title = "Add Role";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require('temp/head.temp.php'); ?>
    <style>

    </style>
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
                    <li class="breadcrumb-item active" aria-current="page">Add User Role</li>
                </ol>
            </nav>
            <a class="btn btn-outline-gray-600" href="user.role.all.php">View User Roles</a>
        </div>

        <?php
        ################################ Role Validation ################################
        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user-role") === true) { #
        ################################ Role Validation ################################
        ?>
        <div class="card card-body border-0 shadow mb-4">
            <h2 class="h5 mb-4">General information</h2>
            <form method="POST" action="comp/role.add.php" autocomplete="off">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="roleName">Role Name</label>
                            <input class="form-control" id="roleName" name="roleName" type="text" required />
                            <div class="form-text text-danger" id="roleNameFeedback"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr />
                        <h4 class="text-center">All Permissions</h4>
                        <hr />
                        <div class="row gy-4 py-3">
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
                                            <input class="form-check-input" type="checkbox" value="add-user"
                                                name="role[]" id="add-user" />
                                            <label class="form-check-label" for="add-user">
                                                Add User
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-user"
                                                name="role[]" id="view-user" />
                                            <label class="form-check-label" for="view-user">
                                                View User
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-user"
                                                name="role[]" id="edit-user" />
                                            <label class="form-check-label" for="edit-user">
                                                Edit User
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-user"
                                                name="role[]" id="delete-user" />
                                            <label class="form-check-label" for="delete-user">
                                                Delete User
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-user-role"
                                                name="role[]" id="add-user-role" />
                                            <label class="form-check-label" for="add-user-role">
                                                Add User Roles
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-user-role"
                                                name="role[]" id="view-user-role" />
                                            <label class="form-check-label" for="view-user-role">
                                                View User Roles
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-user-role"
                                                name="role[]" id="edit-user-role" />
                                            <label class="form-check-label" for="edit-user-role">
                                                Edit User Roles
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-user-role"
                                                name="role[]" id="delete-user-role" />
                                            <label class="form-check-label" for="delete-user-role">
                                                Delete User Roles
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
                                                name="role[]" id="add-account" />
                                            <label class="form-check-label" for="add-account">
                                                Add Account
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-account"
                                                name="role[]" id="view-account" />
                                            <label class="form-check-label" for="view-account">
                                                View Account
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-account"
                                                name="role[]" id="edit-account" />
                                            <label class="form-check-label" for="edit-account">
                                                Edit Account
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-account"
                                                name="role[]" id="delete-account" />
                                            <label class="form-check-label" for="delete-account">
                                                Delete Account
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-property"
                                                name="role[]" id="add-property" />
                                            <label class="form-check-label" for="add-property">
                                                Add Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-property"
                                                name="role[]" id="edit-property" />
                                            <label class="form-check-label" for="edit-property">
                                                Edit Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-property"
                                                name="role[]" id="view-property" />
                                            <label class="form-check-label" for="view-property">
                                                View Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-property"
                                                name="role[]" id="delete-property" />
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
                                            <input class="form-check-input" type="checkbox" value="print" name="role[]"
                                                id="print" />
                                            <label class="form-check-label" for="print">
                                                Print/Export
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-ledger"
                                                name="role[]" id="view-ledger" />
                                            <label class="form-check-label" for="view-ledger">
                                                View Ledger
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-activity"
                                                name="role[]" id="view-activity" />
                                            <label class="form-check-label" for="view-activity">
                                                View Activity
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-sm-6 col-12" id="role-property-transactions">
                                <div class="card h-100 shadow">
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
                                                name="role[]" id="add-sale-property" />
                                            <label class="form-check-label" for="add-sale-property">
                                                Add Sale Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-sale-property"
                                                name="role[]" id="view-sale-property" />
                                            <label class="form-check-label" for="view-sale-property">
                                                View Sale Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="edit-sale-property"
                                                name="role[]" id="edit-sale-property" />
                                            <label class="form-check-label" for="edit-sale-property">
                                                Edit Sale Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-sale-property"
                                                name="role[]" id="delete-sale-property" />
                                            <label class="form-check-label" for="delete-sale-property">
                                                Delete Sale Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="add-transfer-property" name="role[]"
                                                id="add-transfer-property" />
                                            <label class="form-check-label" for="add-transfer-property">
                                                Add Transfer Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="view-transfer-property" name="role[]"
                                                id="view-transfer-property" />
                                            <label class="form-check-label" for="view-transfer-property">
                                                View Transfer Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-return-property"
                                                name="role[]" id="add-return-property" />
                                            <label class="form-check-label" for="add-return-property">
                                                Add Return Property
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-return-property"
                                                name="role[]" id="view-return-property" />
                                            <label class="form-check-label" for="view-return-property">
                                                View Return Property
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row py-3">
                            <div class="col-xxl-3 col-xl-4 col-sm-6 col-12" id="role-payment-transactions">
                                <div class="card h-100 shadow">
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
                                                name="role[]" id="add-payment-pay" />
                                            <label class="form-check-label" for="add-payment-pay">
                                                Add Payment Pay
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-payment-pay"
                                                name="role[]" id="view-payment-pay" />
                                            <label class="form-check-label" for="view-payment-pay">
                                                View Payment Pay
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="delete-payment-pay"
                                                name="role[]" id="delete-payment-pay" />
                                            <label class="form-check-label" for="delete-payment-pay">
                                                Delete Payment Pay
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-payment-receive"
                                                name="role[]" id="add-payment-receive" />
                                            <label class="form-check-label" for="add-payment-receive">
                                                Add Payment Receive
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="view-payment-receive"
                                                name="role[]" id="view-payment-receive" />
                                            <label class="form-check-label" for="view-payment-receive">
                                                View Payment Receive
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="delete-payment-receive" name="role[]"
                                                id="delete-payment-receive" />
                                            <label class="form-check-label" for="delete-payment-receive">
                                                Delete Payment Receive
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox" value="add-payment-transfer"
                                                name="role[]" id="add-payment-transfer" />
                                            <label class="form-check-label" for="add-payment-transfer">
                                                Add Payment Transfer
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="view-payment-transfer" name="role[]"
                                                id="view-payment-transfer" />
                                            <label class="form-check-label" for="view-payment-transfer">
                                                View Payment Transfer
                                            </label>
                                        </div>
                                        <div class="form-check ms-4">
                                            <input class="form-check-input" type="checkbox"
                                                value="delete-payment-transfer" name="role[]"
                                                id="delete-payment-transfer" />
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
                        <button type="submit" class="btn btn-outline-gray-600 my-3" name="submit" value="submit">Add</button>
                    </div>
                </div>
            </form>
        </div>
        <?php include('temp/footer.temp.php'); ?>
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
    if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user-role") === true) { #
    ################################ Role Validation ################################
    ?>
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
            }

            $.ajax({
                url: 'comp/role.validate.php',
                type: 'POST',
                data: {
                    role_name: role_name
                },
                success: function(response) {
                    if (response == "valid") {
                        $("#roleNameFeedback").text("");
                        $("#roleName").removeClass("is-invalid");
                        $("#roleName").addClass("is-valid");
                        console.log(response);
                        $("form").off('submit').submit();
                        notify("success", "Click one more time ...");
                    } else {
                        notify("error", "Role Name Exist ...");
                        $("#roleName").removeClass("is-valid");
                        $("#roleName").addClass("is-invalid");
                        console.log(response);
                    }
                },
                error: function() {
                    console.log('An error occurred. Please try again.');
                }
            });
        });
    });
    </script>
    <?php } ?>
</body>

</html>