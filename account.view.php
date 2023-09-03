<?php
session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php");                           #
$license_path = "licenses/".$_SESSION['license_username']."/license.json"; #
require("auth/license.validate.functions.php");                    #
require("temp/validate.license.temp.php");                         #
#################### Login & License Validation ####################

####################### Database Connection #######################
require("auth/config.php");                                       #
require("auth/functions.php");                                    #
$conn = conn("localhost", "root", "", "communiSync");             #
####################### Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-account") != true) {
    header("Location: Accounts?m=account_view_not_allow");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['i'])) {
    header("Location: Accounts?m=not_found");
    exit();
}

$query = "SELECT `type` FROM `accounts` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$type = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `".$type['type']."` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$account = mysqli_fetch_assoc(mysqli_query($conn, $query));
$account['delete'] = 1;

if (empty($account)) {
    header("Location: Accounts?m=not_found");
    exit();
}

if ($type['type'] == 'seller') {
    $query = "SELECT * FROM `area_seller` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $account['delete'] = 0;
    }
} elseif ($type['type'] == 'investor') {
    $query = "SELECT * FROM `area_investor` WHERE `acc_id` IN ('".encryptor("decrypt", $_GET['i'])."') AND `project_id` = '".$_SESSION['project']."';";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $account['delete'] = 0;
    }
}

$query = "SELECT `v-id`,`type`,`remarks`,`credit`,`debit`, @balance := @balance - credit + debit AS balance
FROM `ledger`, (SELECT @balance := 0) AS vars WHERE `source` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$ledger = fetch_Data($conn, $query);

// echo "<pre>";
// print_r($ledger);
// exit();

$title = "Account - ".$account['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
    <?php if (!empty($documents)) { ?>
    <script src="vendor/viewerjs/dist/js/viewer.min.js"></script>
    <link rel="stylesheet" href="vendor/viewerjs/dist/css/viewer.min.css" />
    <style>
    .selected-image {
        height: 500px;
    }

    .selected-image:hover {
        content: "View Image";
    }
    </style>
    <?php } ?>
</head>

<body>
    <?php include('temp/aside.temp.php'); ?>
    <main class="content">
        <?php include('temp/nav.temp.php'); ?>

        <div class="py-3">
            <nav aria-label="breadcrumb" class="d-none d-md-flex justify-content-between align-items-center">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewbox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#">Master Entry</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Account - <?=$account['name']?>
                    </li>
                </ol>
                <div class="btn-group">
                    <button class="btn btn-outline-gray-800 rounded-0 rounded-start dropdown-toggle"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        <?php if (!empty($documents)) { ?>
                        <a class="dropdown-item d-flex align-items-center justify-content-center"
                            href="docs.view.php?i=<?=$_GET['i']?>">
                            Docs
                        </a>
                        <?php } ?>
                        <?php
                        ################################ Role Validation ################################
                        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-account") === true) {
                        ################################ Role Validation ################################
                        ?>
                        <a class="dropdown-item d-flex align-items-center justify-content-center fw-bold text-success"
                            href="account.edit.php?i=<?=$_GET['i']?>">
                            Edit Account
                        </a>
                        <?php } ?>
                        <?php
                        ################################ Role Validation ################################
                        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-account") === true) {
                            if ($account['delete'] == 1) {
                        ################################ Role Validation ################################
                        ?>
                        <div role="separator" class="dropdown-divider my-1"></div>
                        <a class="dropdown-item d-flex align-items-center justify-content-center fw-bold text-danger deleteBtn"
                            data-id="<?=$_GET['i']?>" data-name="<?=$account['name']?>">
                            Delete Account
                        </a>
                        <?php } } ?>
                    </div>
                    <a class="btn btn-outline-gray-800" href="Accounts">Manage Accounts</a>
                </div>
            </nav>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center mb-3"><?=$account['name']?></h2>
                            </div>
                            <div class="col-4 d-flex justify-content-center align-items-center">
                                <img src="<?=(file_exists("uploads/acc-profiles/".$account['img']))?"uploads/acc-profiles/".$account['img']:"uploads/profiles/profile.png"?>"
                                    alt="" srcset="" class="img-fluid rounded rounded-circle" />
                            </div>
                            <div class="col-8">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Account</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <tr>
                                            <td>Type</td>
                                            <td class="text-end text-capitalize"><?=$type['type']?></td>
                                        </tr>
                                        <tr>
                                            <td>CNIC</td>
                                            <td class="text-end"><?=cnic_format($account['cnic'])?></td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td class="text-end"><?="+92 ".phone_no_format($account['phone_no'])?></td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td class="text-end"><?=$account['address']?></td>
                                        </tr>
                                        <tr>
                                            <td>Recieved Amount</td>
                                            <td class="text-success text-end">Rs. <?=(!empty($ledger))?number_format(getSumOfId($ledger, "debit")):"0"?></td>
                                        </tr>
                                        <tr>
                                            <td>Pending Amount</td>
                                            <td class="text-danger text-end">Rs. <?=(!empty($ledger))?number_format(getSumOfId($ledger, "credit") - getSumOfId($ledger, "debit")):"0"?></td>
                                        </tr>
                                        <?php if ($account['balance'] < 0) { ?>
                                        <tr>
                                            <td>Balance</td>
                                            <td class="text-end text-danger">
                                                <?=$arrow_down."Rs. ".number_format($account['balance'])?></td>
                                        </tr>
                                        <?php } else { ?>
                                        <tr>
                                            <td>Balance</td>
                                            <?php 
                                            if (!empty($ledger)) {
                                                if ($ledger[count($ledger)-1]['balance'] == 0) { ?>
                                                    <td class="text-end text-success">
                                                        <?=$arrow_up."Rs. 0"?></td>
                                                <?php } elseif ($ledger[count($ledger)-1]['balance'] > 0) { ?>
                                                    <td class="text-end text-success">
                                                        <?=$arrow_up."Rs. ".number_format($ledger[count($ledger)-1]['balance'])?></td>
                                                <?php } else { ?>
                                                    <td class="text-end text-danger">
                                                        <?=$arrow_down."Rs. ".number_format($ledger[count($ledger)-1]['balance'])?></td>
                                                <?php } 
                                            } else { ?>
                                                <td class="text-end">Rs. 0</td>
                                            <?php } ?>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <?php if ($type['type'] == 'customer') { ?>
                <div class="card">
                    <div class="card-body">
                        <?php if (FALSE/*condition for property existance*/) { ?>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h4>No Property</h4>
                                <a class="btn btn-outline-gray-600 my-3" href="sale.add.php">Purchase Property</a>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <h2 class="mb-3">Property Details</h2>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-2">
                                    <select class="form-select" name="properties" id="properties">
                                        <option value="">Select Property</option>
                                        <option value="pt-321">Flat #321</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Property</th>
                                            <th class="border-0 rounded-end" style="text-align: right;">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold">
                                        <tr>
                                            <td>Type</td>
                                            <td style="text-align: right;">
                                                flat </td>
                                        </tr>
                                        <tr>
                                            <td>No.</td>
                                            <td style="text-align: right;">
                                                # 391 </td>
                                        </tr>
                                        <tr>
                                            <td>Total Insts</td>
                                            <td style="text-align: right;">
                                                24 </td>
                                        </tr>
                                        <tr>
                                            <td>Paid Insts.</td>
                                            <td style="text-align: right;">
                                                0 </td>
                                        </tr>
                                        <tr>
                                            <td>Price</td>
                                            <td style="text-align: right;">
                                                Rs. 3,300,000 </td>
                                        </tr>
                                        <tr>
                                            <td>Paid</td>
                                            <td class="text-success" style="text-align: right;">
                                                Rs. 459,500 </td>
                                        </tr>
                                        <tr>
                                            <td>Pending</td>
                                            <td class="text-danger" style="text-align: right;">
                                                Rs. 2,840,500 </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td class="text-danger" style="text-align: right;">
                                                Under Financing </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <?php if(!empty ($ledger) || TRUE) { ?>
        <div class="card my-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        
                    </div>
                    <div class="col-12">
                        <table class="table table-centered table-nowrap mb-0 rounded" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 rounded-start">#</th>
                                    <th class="border-0 text-center">V-ID</th>
                                    <th class="border-0">Remarks</th>
                                    <th class="border-0">Credit</th>
                                    <th class="border-0 text-end">Debit</th>
                                    <th class="border-0 text-end rounded-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($ledger)){
                                    foreach ($ledger as $key => $led) { ?>
                                <tr>
                                    <td class="fw-bolder">
                                        <?=$key+1?>
                                    </td>
                                    <td><?=$led['v-id']?></td>
                                    <td><?=$led['remarks']?></td>
                                    <?php if (!empty($led['debit'])) { ?>
                                    <td class="text-success"><?=$arrow_up?> Rs. <?=number_format($led['debit'])?></td>
                                    <td></td>
                                    <?php } else { ?>
                                    <td></td>
                                    <td class="text-end text-danger">Rs. <?=number_format($led['credit'])?>
                                        <?=$arrow_down?></td>
                                    <?php } ?>
                                    <?php if ($led['balance'] == 0) { ?>
                                    <td class="fw-bold text-end text-success">
                                        Rs. <?=number_format($led['balance'])?>
                                        <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24"
                                            stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round" class="css-i6dzq1">
                                            <circle cx="12" cy="12" r="4"></circle>
                                            <line x1="1.05" y1="12" x2="7" y2="12"></line>
                                            <line x1="17.01" y1="12" x2="22.96" y2="12"></line>
                                        </svg>

                                    </td>
                                    <?php } elseif ($led['balance'] > 0) { ?>
                                    <td class="fw-bold text-end text-success">
                                        Rs. <?=number_format($led['balance'])?>
                                        <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                            stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                            class="css-i6dzq1">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                            <polyline points="17 6 23 6 23 12"></polyline>
                                        </svg>
                                    </td>
                                    <?php } else { ?>
                                    <td class="fw-bold text-end text-danger">
                                        Rs. <?=number_format($led['balance'])?>
                                        <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                            stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                            class="css-i6dzq1">
                                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                            <polyline points="17 18 23 18 23 12"></polyline>
                                        </svg>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <?php } } else { ?>
                                <tr>
                                    <td class="text-center" colspan="7">No ledger history ...</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php include('temp/footer.temp.php'); ?>
    </main>

    <?php if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") === true) { ?>
    <!-- Modal Content -->
    <div class="modal fade" id="deleteAccount" tabindex="-1" role="dialog" aria-labelledby="delete account"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">Delete User</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete User ( <span id="selectedAccount"></span> ) ?</p>
                    <input type="hidden" id="accountID" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">No</button>
                    <button type="button" id="confirmDeleteBtn" data-target="#confirmationCode"
                        class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmationCode" tabindex="-1" role="dialog" aria-labelledby="delete account"
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

    <?php include('temp/script.temp.php'); ?>
    <script>
    <?php if (isset($_GET['m'])) { ?>
    <?php if ($_GET['m'] == 'edit_true') { ?>
    notify("success", "Account Updated ...");
    <?php } ?>
    <?php } ?>

    // Randomly generate two numbers
    var number1 = Math.floor(Math.random() * 10) + 1;
    var number2 = Math.floor(Math.random() * 10) + 1;
    $('.number1').text(number1);
    $('.number2').text(number2);

    // Show confirmation dialog on delete button click
    $('.deleteBtn').click(function() {
        var accountId = $(this).data('id');
        var userName = $(this).data('name');

        // Set user name in delete confirmation modal
        $('#selectedAccount').text(userName);
        $('#accountID').val(accountId);

        console.log(userName);
        console.log(accountId);

        $('#deleteAccount').modal('show');
    });

    // Show confirmation code dialog on confirm delete button click
    $('#confirmDeleteBtn').click(function() {
        $('#deleteAccount').modal('hide');
        $('#confirmationCode').modal('show');
    });

    // Handle confirmation code submission
    $('#confirmCodeAns').click(function() {
        // Get user input and calculate expected result
        var input = $('#confirmation-input').val();
        var expected = number1 + number2;

        // Check if input is correct
        if (input == expected) {
            var accountId = $('#accountID').val();
            window.location.href = 'comp/account.delete.php?i=' + accountId;
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
    </script>
</body>

</html>