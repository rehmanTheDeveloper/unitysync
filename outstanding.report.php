<?php

session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php"); #
$license_path = "license/" . $_SESSION['license_username'] . "/license.json"; #
require("auth/license.validate.functions.php"); #
require("temp/validate.license.temp.php"); #
#################### Login & License Validation ####################

####################### Database Connection #######################
require("auth/config.php"); #
require("auth/functions.php"); #
$conn = conn("localhost", "root", "", "unitySync"); #
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
####################### Database Connection #######################

include "object/ledger.php";
$ledger_obj = new Ledger($PDO_conn);

$query = "SELECT * FROM `accounts` WHERE `type` != 'bank' AND `type` != 'expense' AND `project_id` = '".$_SESSION['project']."';";
$all_accounts = fetch_Data($conn, $query);

foreach ($all_accounts as $key => $Acc) {
    $query = "SELECT * FROM `".$Acc['type']."` WHERE `acc_id` = '".$Acc['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
    $acc = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $accounts[$key] = $acc;
    $accounts[$key]['type'] = $Acc['type'];
    $accounts[$key]['credit'] = 0;
    $accounts[$key]['debit'] = 0;

    $fetch_ledger = [
        'source' => $acc['acc_id'],
        'pay_to' => $acc['acc_id'],
        'project' => $_SESSION['project']
    ];
    $ledger_history = $ledger_obj->fetch($fetch_ledger);

    if (!empty($ledger_history)) {
        foreach ($ledger_history as $ledger) {
            $accounts[$key]['credit'] += $ledger['credit'];
            $accounts[$key]['debit'] += $ledger['debit'];
        }
    }

    if ($Acc['type'] == 'customer') {
        $query = "SELECT `pty_id`,`sale_id`,`price`,`advance_payment`,`installments` FROM `sale_installment` WHERE `acc_id` = '".$acc['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $sale_installment = mysqli_fetch_assoc(mysqli_query($conn, $query));
        if (!empty($sale_installment)) {
            $accounts[$key]['credit'] += ($sale_installment['price'] - $sale_installment['advance_payment']);
        }
        $query = "SELECT `pty_id`,`sale_id`,`price`,`net_amount` FROM `sale_net_cash` WHERE `acc_id` = '".$acc['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $sale_net_cash = mysqli_fetch_assoc(mysqli_query($conn, $query));
        if (!empty($sale_net_cash)) {
            $accounts[$key]['credit'] += ($sale_net_cash['price']);
        }
    } elseif ($Acc['type'] == 'seller') {
        $query = "SELECT `amount` FROM `area_seller` WHERE `acc_id` = '".$acc['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $area_seller = mysqli_fetch_assoc(mysqli_query($conn, $query));
        if (!empty($area_seller)) {
            $accounts[$key]['debit'] += ($area_seller['amount']);
        }
    }
}

$query = "SELECT * FROM `expense_sub_groups` WHERE `project_id` = '".$_SESSION['project']."';";
$sub_groups = fetch_Data($conn, $query);

// echo "<pre>";
// print_r($accounts);
// print_r($ledger_obj);
// exit();

$title = "Outstanding Reports";
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
                    <li class="breadcrumb-item"><a>Reports</a></li>
                    <li class="breadcrumb-item"><a href="#">Outstandings</a></li>
                </ol>
            </nav>
        </div>

        <?php
        ################################ Role Validation ################################
        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user") === true) {
        ################################ Role Validation ################################
        ?>
        <!-- Datatable -->
        <div class="card border-0 shadow mb-4 pb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table user-table table-hover align-items-center" id="dummyTable">
                        <thead>
                            <tr>
                                <th class="border-bottom">
                                    #
                                </th>
                                <th class="border-bottom">Account</th>
                                <th class="border-bottom text-end">Type</th>
                                <th class="border-bottom text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($accounts)) {
                                $a = 0;
                                $recievable = 0;
                                $payable = 0;
                                    foreach ($accounts as $key => $account) {
                                        $balance = 0;
                                        $balance = $account['credit'] - $account['debit'];
                                        if ($balance > 0) {
                                            $recievable += $balance;
                                        } elseif ($balance < 0) {
                                            $payable += $balance;
                                        }
                                    ?>
                            <tr>
                                <td>
                                    <?= $a + 1 ?>
                                </td>
                                <td>
                                    <a href="account.view.php?i=<?=encryptor("encrypt", $account['acc_id'])?>"
                                        class="d-flex align-items-center">
                                        <img src="uploads/acc-profiles/<?php 
                                        if ($account['type'] != 'bank' && $account['type'] != 'expense') {
                                            echo $account['img'];
                                        }
                                        if ($account['type'] == 'bank') {
                                            echo "bank.png";
                                        }
                                        if ($account['type'] == 'expense') {
                                            echo 'dollar.png';
                                        }
                                        ?>" class="avatar rounded rounded-1 me-3" alt="Avatar">
                                        <div class="d-block">
                                            <span class="fw-bold text-capitalize">
                                                <?= $account['name'] . " - " . $account['type'] ?>
                                            </span>
                                            <div class="small text-gray">
                                                <?php if ($account['type'] != 'bank' && $account['type'] != 'expense') {
                                                    echo cnic_format($account['cnic']);
                                                }
                                                if ($account['type'] == 'bank') {
                                                    echo formatAccountNumber($account['number']);
                                                }
                                                if ($account['type'] == 'expense') {
                                                    foreach ($sub_groups as $group) {
                                                        if ($account['sub_group'] == $group['id']) {
                                                            echo ($group['name']);
                                                        }
                                                    }
                                                    echo " - ".$account['details'];
                                                } ?>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td class="text-end">
                                    <span class="fw-normal">
                                        <?php if ($balance == 0) { ?>
                                            <span class="badge bg-primary">Nil</span>
                                        <?php } elseif ($balance > 0) { ?>
                                            <span class="badge bg-success">Recievable</span>
                                        <?php } elseif ($balance < 0) { ?>
                                            <span class="badge bg-danger">Payable</span>
                                        <?php } ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <?php if ($balance == 0) { ?>
                                    <span class="text-success">
                                        Rs. <?=number_format($balance)?>
                                        <svg class="icon icon-xs me-1" viewBox="0 0 24 24" width="24" height="24"
                                            stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round" class="css-i6dzq1">
                                            <circle cx="12" cy="12" r="4"></circle>
                                            <line x1="1.05" y1="12" x2="7" y2="12"></line>
                                            <line x1="17.01" y1="12" x2="22.96" y2="12"></line>
                                        </svg>
                                    </span>
                                    <?php } elseif ($balance > 0) { ?>
                                    <span class="text-success">
                                        Rs. <?=number_format($balance)?>
                                        <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M14.707 12.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l2.293-2.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <?php } elseif ($balance < 0) { ?>
                                    <span class="text-danger">
                                        Rs. <?=number_format($balance)?>
                                        <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L6.707 7.707a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php $a++;} } else { ?>
                            <tr>
                                <td class="fw-bold text-center" colspan="6">No User has been Added ...</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="col-3 pt-3 float-end">
                        <div class="card card-body text-end">
                            <p class="mb-2 text-danger"><strong>Total Payable:</strong> Rs. <?=number_format(-$payable)?>
                            </p>
                            <p class="mb-0 text-success"><strong>Total Recievable:</strong> Rs.
                                <?=number_format($recievable)?></p>
                        </div>
                    </div>
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
</body>

</html>