<?php
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
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
####################### Database Connection #######################

include "object/ledger.php";
$ledger_obj = new Ledger($PDO_conn);

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-account") != true) {
    header("Location: account.all.php?m=account_view_not_allow");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['i'])) {
    header("Location: account.all.php?m=not_found");
    exit();
}

$project_details = mysqli_fetch_assoc(mysqli_query($conn, "SELECT `sqft_per_marla` FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';"));

$query = "SELECT `type` FROM `accounts` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$type = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `".$type['type']."` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$account = mysqli_fetch_assoc(mysqli_query($conn, $query));
$account['delete'] = 1;

if (empty($account)) {
    header("Location: account.all.php?m=not_found");
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

$fetch_ledger = [
    'source' => encryptor("decrypt", $_GET['i']),
    'pay_to' => encryptor("decrypt", $_GET['i']),
    'project' => $_SESSION['project']
];
$ledger = $ledger_obj->fetch($fetch_ledger);

// echo "<pre>";
// print_r($ledger);
// exit();

if ($type['type'] == 'expense') {
    $query = "SELECT * FROM `expense_sub_groups` WHERE `id` = '".$account['sub_group']."' AND `project_id` = '".$_SESSION['project']."';";
    $expense_sub_groups = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $account['sub_group'] = $expense_sub_groups['name'];
}

if ($type['type'] == 'customer') {
    $query = "SELECT `pty_id`,`sale_id`,`price`,`advance_payment`,`installments` FROM `sale_installment` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
    $sale_installment = fetch_Data($conn, $query);
    $a = 0;
    if (!empty($sale_installment)) {
        foreach ($sale_installment as $sale) {
            $sale['debit'] = 0;
            $sale['credit'] = 0;
            $fetch_ledger = [
                'type' => "sale",
                'sale_id' => $sale['sale_id'],
                'source' => encryptor("decrypt", $_GET['i']),
                'pay_to' => encryptor("decrypt", $_GET['i']),
                'project' => $_SESSION['project']
            ];
            // $properties[$key]['ledger'] = $fetch_ledger;
            $sale_ledger = $ledger_obj->fetch($fetch_ledger);
            foreach ($sale_ledger as $single_ledger) {
                $sale['debit'] += $single_ledger['debit'];
                $sale['credit'] += $single_ledger['credit'];
            }
            // print_r($sale_ledger);
            $query = "SELECT `type` FROM `properties` WHERE `pty_id` = '".$sale['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
            $property_type = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $query = "SELECT * FROM `".$property_type['type']."` WHERE `pty_id` = '".$sale['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
            $properties[$a] = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $properties[$a]['type'] = $property_type['type'];
            $properties[$a]['sale_type'] = "installment";
            $properties[$a]['rate'] = $sale['price'];
            $properties[$a]['advance_payment'] = intval($sale['advance_payment']);
            $properties[$a]['installments'] = intval($sale['installments']);
            $properties[$a]['credit'] = $sale['credit'];
            $properties[$a]['debit'] = $sale['debit'];
            $a++;
        }
    }
    $query = "SELECT `pty_id`,`sale_id`,`price`,`net_amount` FROM `sale_net_cash` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
    $sale_net_cash = fetch_Data($conn, $query);
    if (!empty($sale_net_cash)) {
        foreach ($sale_net_cash as $sale) {
            $sale['debit'] = 0;
            $sale['credit'] = 0;
            $fetch_ledger = [
                'type' => "sale",
                'sale_id' => $sale['sale_id'],
                'source' => encryptor("decrypt", $_GET['i']),
                'pay_to' => encryptor("decrypt", $_GET['i']),
                'project' => $_SESSION['project']
            ];
            $sale_ledger = $ledger_obj->fetch($fetch_ledger);
            foreach ($sale_ledger as $single_ledger) {
                $sale['debit'] += $single_ledger['debit'];
                $sale['credit'] += $single_ledger['credit'];
            }
            // print_r($sale_ledger);
            $query = "SELECT `type` FROM `properties` WHERE `pty_id` = '".$sale['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
            $property_type = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $query = "SELECT * FROM `".$property_type['type']."` WHERE `pty_id` = '".$sale['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
            $properties[$a] = mysqli_fetch_assoc(mysqli_query($conn, $query));
            $properties[$a]['type'] = $property_type['type'];
            $properties[$a]['sale_type'] = 'net_cash';
            $properties[$a]['credit'] = $sale['credit'];
            $properties[$a]['debit'] = $sale['debit'];
            $a++;
        }
    }
    if (empty($sale_installment) && empty($sale_net_cash)) {
        $properties = [];
    }
}

// echo "<pre>";
// print_r($properties);
// exit();

$title = "Account - ".$account['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
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
                        <a class="dropdown-item d-flex align-items-center justify-content-center"
                            href="docs.view.php?i=<?=$_GET['i']?>">
                            Docs
                        </a>
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
                    <a class="btn btn-outline-gray-800" href="account.all.php">Manage Accounts</a>
                </div>
            </nav>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 position-relative">
                                <h2 class="text-center mb-3"><?=$account['name']?></h2>
                                <?php if ($type['type'] == 'seller' || $type['type'] == 'investor' || $type['type'] == 'staff' || $type['type'] == 'customer') { ?>
                                <a data-bs-toggle="modal" data-bs-target="#otherDetails"
                                    class="btn btn-outline-primary position-absolute end-0 top-0">
                                    Other Details
                                </a>
                                <?php } ?>
                            </div>
                            <?php if ($type['type'] == 'seller' || $type['type'] == 'investor' || $type['type'] == 'staff' || $type['type'] == 'customer') { ?>
                            <div class="col-4 d-flex justify-content-center align-items-center">
                                <img src="<?=(file_exists("uploads/acc-profiles/".$account['img']))?"uploads/acc-profiles/".$account['img']:"uploads/profiles/profile.png"?>"
                                    alt="" srcset="" class="img-fluid rounded rounded-circle" />
                            </div>
                            <?php } ?>
                            <div
                                class="<?=($type['type'] == 'seller' || $type['type'] == 'investor' || $type['type'] == 'staff' || $type['type'] == 'customer')?"col-8":"col-12"?>">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Account</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <?php if ($type['type'] == 'seller' || $type['type'] == 'investor' || $type['type'] == 'staff' || $type['type'] == 'customer') { ?>
                                        <tr>
                                            <td>Father Name</td>
                                            <td class="text-end text-capitalize"><?=$account['father_name']?></td>
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
                                            <td>Location</td>
                                            <td class="text-end text-capitalize">
                                                <?=$account['city'].", ".$account['province'].", ".$account['country']?>
                                            </td>
                                        </tr>
                                        <?php } elseif ($type['type'] == 'bank') { ?>
                                        <tr>
                                            <td>Other Details</td>
                                            <td class="text-end text-capitalize"><?=$account['details']?></td>
                                        </tr>
                                        <tr>
                                            <td>Account Number</td>
                                            <td class="text-end"><?=formatAccountNumber($account['number'])?></td>
                                        </tr>
                                        <tr>
                                            <td>Account Branch</td>
                                            <td class="text-end text-capitalize"><?=$account['branch']?></td>
                                        </tr>
                                        <?php } elseif ($type['type'] == 'expense') { ?>
                                        <tr>
                                            <td>Other Details</td>
                                            <td class="text-end"><?=$account['details']?></td>
                                        </tr>
                                        <tr>
                                            <td>Sub Group</td>
                                            <td class="text-end"><?=$account['sub_group']?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td>Credit Amount</td>
                                            <td class="text-success text-end">Rs.
                                                <?=(!empty($ledger))?number_format(getSumOfId($ledger, "credit")):"0"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Debit Amount</td>
                                            <td class="text-danger text-end">Rs.
                                                <?=(!empty($ledger))?number_format(getSumOfId($ledger, "debit")):"0"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Balance</td>
                                            <?php 
                                            if (!empty($ledger)) {
                                                if ($ledger[count($ledger)-1]['balance'] == 0) { ?>
                                            <td class="text-end text-success">
                                                <?=$arrow_up."Rs. 0"?></td>
                                            <?php } elseif ($ledger[count($ledger)-1]['balance'] > 0) { ?>
                                            <td class="text-end text-success">
                                                <?=$arrow_up."Rs. ".number_format($ledger[count($ledger)-1]['balance'])?>
                                            </td>
                                            <?php } else { ?>
                                            <td class="text-end text-danger">
                                                <?=$arrow_down."Rs. ".number_format($ledger[count($ledger)-1]['balance'])?>
                                            </td>
                                            <?php } 
                                            } else { ?>
                                            <td class="text-end">Rs. 0</td>
                                            <?php } ?>
                                        </tr>
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
                        <?php if (empty($properties)) { ?>
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
                                    <select class="form-select" name="selectProperty" id="selectProperty">
                                        <?php foreach ($properties as $key => $property) { ?>
                                        <option value="<?=$property['pty_id']?>"><?=strtoupper($property['type'])?>
                                            #<?=$property['number']?>
                                        </option>
                                        <?php } ?>
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
                                            <td id="propertyType" class="text-capitalize text-end"></td>
                                        </tr>
                                        <tr>
                                            <td>No.</td>
                                            <td class="text-end" id="propertyNumber"></td>
                                        </tr>
                                        <tr>
                                            <td>Purchase Type</td>
                                            <td class="text-end text-capitalize fw-bold" id="propertySaleType"></td>
                                        </tr>
                                        <tr>
                                            <td>Total Insts</td>
                                            <td class="text-end" id="propertyInstallments"></td>
                                        </tr>
                                        <tr>
                                            <td>Paid Insts.</td>
                                            <td class="text-end" id="propertyPaidInstallments"></td>
                                        </tr>
                                        <tr>
                                            <td>Installment Amount</td>
                                            <td class="text-end" id="propertyInstallmentAmount"></td>
                                        </tr>
                                        <tr>
                                            <td>Price</td>
                                            <td class="text-end" id="propertyPrice"></td>
                                        </tr>
                                        <tr>
                                            <td>Paid</td>
                                            <td id="propertyPaidAmount" class="text-success text-end"></td>
                                        </tr>
                                        <tr>
                                            <td>Pending</td>
                                            <td id="propertyRemainingAmount" class="text-danger text-end"></td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td class="text-capitalize text-end">
                                                <span style="font-size: 13px;" class="badge" id="propertyStatus"></span>
                                            </td>
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

        <?php if(!empty($ledger)) { ?>
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
                                    <th class="border-0 text-center">Type</th>
                                    <th class="border-0 text-center">Source</th>
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
                                    <td class="text-capitalize"><?=$led['type']?></td>
                                    <td><?=$led['account']?></td>
                                    <td><?=$led['remarks']?></td>
                                    <?php if (!empty($led['credit'])) { ?>
                                    <td class="text-success"><?=$arrow_up?> Rs. <?=number_format($led['credit'])?></td>
                                    <td></td>
                                    <?php } else { ?>
                                    <td></td>
                                    <td class="text-end text-danger">Rs. <?=number_format($led['debit'])?>
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
                                        <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24"
                                            stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round" class="css-i6dzq1">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                            <polyline points="17 6 23 6 23 12"></polyline>
                                        </svg>
                                    </td>
                                    <?php } else { ?>
                                    <td class="fw-bold text-end text-danger">
                                        Rs. <?=number_format($led['balance'])?>
                                        <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24"
                                            stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round" class="css-i6dzq1">
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

    <?php if ($type['type'] == 'seller' || $type['type'] == 'investor' || $type['type'] == 'staff' || $type['type'] == 'customer') { ?>
    <div class="modal fade" id="otherDetails" tabindex="-1" role="dialog" aria-labelledby="Kin Details"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">Other Details</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-centered table-nowrap mb-0 rounded">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0 rounded-start">Other</th>
                                        <th class="border-0 rounded-end text-end">Details
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bolder">
                                    <tr>
                                        <td>Email</td>
                                        <td class="text-end"><?=$account['email']?></td>
                                    </tr>
                                    <tr>
                                        <td>Whatsapp No.</td>
                                        <td class="text-end"><?="+92 ".phone_no_format($account['whts_no'])?></td>
                                    </tr>
                                    <?php if ($type['type'] == 'customer') { ?>
                                    <tr>
                                        <td>Kin Name</td>
                                        <td class="text-end text-capitalize"><?=$account['kin_name']?></td>
                                    </tr>
                                    <tr>
                                        <td>Kin Relationship</td>
                                        <td class="text-end text-capitalize"><?=$account['kin_relation']?></td>
                                    </tr>
                                    <tr>
                                        <td>Kin CNIC</td>
                                        <td class="text-end"><?=cnic_format($account['kin_cnic'])?></td>
                                    </tr>
                                    <tr>
                                        <!-- Referrer -->
                                        <td>Guranter Name</td>
                                        <td class="text-end text-capitalize"><?=$account['guranter_name']?></td>
                                    </tr>
                                    <tr>
                                        <td>Guranter CNIC</td>
                                        <td class="text-end"><?=cnic_format($account['guranter_cnic'])?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

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

    <?php if (count($properties) >= 1) { ?>
    var properties = <?=(!empty($properties)?json_encode($properties):json_encode([]))?>;

    $("#selectProperty").change(function() {
        // console.log($(this).val());
        if ($(this).val()) {
            for (var key in properties) {
                if (properties.hasOwnProperty(key)) {
                    if (properties[key].pty_id === $(this).val()) {
                        var property = properties[key];
                        // $("#instPropertyPrice").val(parseInt(properties[key]['rate'])
                        //     .toLocaleString());

                        var installment_amount = (property.rate - property.advance_payment) / property.installments;
                        var paid_installments = (property.debit - property.advance_payment) / ((property.rate - property.advance_payment) / property.installments);
                        $("#propertyType").text(property.type);
                        $("#propertyNumber").text("#" + property.number);
                        $("#propertySaleType").text(property.sale_type == 'net_cash'?"net cash":"Installments");
                        if (property.sale_type == "installment") {
                            $("#propertyInstallments").parent().removeClass("d-none");
                            $("#propertyPaidInstallments").parent().removeClass("d-none");
                            $("#propertyInstallmentAmount").parent().removeClass("d-none");
                            $("#propertyInstallments").text(property.installments);
                            $("#propertyPaidInstallments").text(Math.floor(paid_installments));
                            $("#propertyInstallmentAmount").text("Rs. " + parseInt(installment_amount).toLocaleString());
                        } 
                        else if (property.sale_type == "net_cash") {
                            $("#propertyInstallments").parent().addClass("d-none");
                            $("#propertyPaidInstallments").parent().addClass("d-none");
                            $("#propertyInstallmentAmount").parent().addClass("d-none");
                        }
                        $("#propertyPrice").text("Rs. " + parseInt(property.rate).toLocaleString());
                        $("#propertyPaidAmount").text("Rs. " + parseInt(property.debit).toLocaleString());
                        $("#propertyRemainingAmount").text("Rs. " + parseInt(property.rate - property.debit).toLocaleString());
                        if (property.sale_type == "installment") {
                            if ((property.rate - property.debit) == 0) {
                                $("#propertyStatus").addClass("bg-success");
                                $("#propertyStatus").text("purchased");
                            } else {
                                $("#propertyStatus").addClass("bg-warning");
                                $("#propertyStatus").text("under financing");
                            }
                        } else if (property.sale_type == "net_cash") {
                            if ((property.rate - property.debit) == 0) {
                                $("#propertyStatus").removeClass("bg-warning");
                                $("#propertyStatus").addClass("bg-success");
                                $("#propertyStatus").text("purchased");
                            } else {
                                $("#propertyStatus").removeClass("bg-success");
                                $("#propertyStatus").addClass("bg-warning");
                                $("#propertyStatus").text("under financing");
                            }
                        }
                        break;
                    }
                }
            }
        }
    });
    $("#selectProperty").trigger("change");
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