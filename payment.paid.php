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
$conn = conn("localhost", "root", "", "unitySync");
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
####################### Database Connection #######################

include "object/ledger.php";
$ledger_obj = new Ledger($PDO_conn);

##################################### Bank Accounts #####################################
$query = "SELECT * FROM `bank` WHERE `project_id` = '".$_SESSION['project']."';";
$banks = fetch_Data($conn, $query);
foreach ($banks as $key => $bank) {

    $banks[$key]['credit'] = 0;
    $banks[$key]['debit'] = 0;
    $banks[$key]['balance'] = 0;
    $fetch_ledger = [
        'source' => $bank['acc_id'],
        'pay_to' => $bank['acc_id'],
        'project' => $_SESSION['project']
    ];
    $ledger_history = $ledger_obj->fetch($fetch_ledger);
    if (!empty($ledger_history)) {
        foreach ($ledger_history as $ledger) {
            $banks[$key]['credit'] += $ledger['credit'];
            $banks[$key]['debit'] += $ledger['debit'];
        }
        $banks[$key]['balance'] = $banks[$key]['credit'] - $banks[$key]['debit'];
    }
}
$banks[count($banks)]['acc_id'] = "colony";
$banks[count($banks)-1]['name'] = "cash";
$banks[count($banks)-1]['type'] = "cash";
$banks[count($banks)-1]['project_id'] = $_SESSION['project'];
$banks[count($banks)-1]['credit'] = 0;
$banks[count($banks)-1]['debit'] = 0;
$banks[count($banks)-1]['balance'] = 0;
$fetch_ledger = [
    'source' => "colony",
    'pay_to' => "colony",
    'project' => $_SESSION['project']
];
$ledger_history = $ledger_obj->fetch($fetch_ledger);
if (!empty($ledger_history)) {
    foreach ($ledger_history as $ledger) {
        $banks[count($banks)-1]['credit'] += $ledger['credit'];
        $banks[count($banks)-1]['debit'] += $ledger['debit'];
    }
    $banks[count($banks)-1]['balance'] = $banks[count($banks)-1]['credit'] - $banks[count($banks)-1]['debit'];
}
##################################### Bank Accounts #####################################

##################################### All Accounts #####################################
$query = "SELECT `type`,`acc_id` FROM `accounts` WHERE `type` != 'bank' AND `project_id` = '".$_SESSION['project']."';";
$accounts = fetch_Data($conn, $query);
foreach ($accounts as $key => $account) {

    $query = "SELECT * FROM `".$account['type']."` WHERE `acc_id` = '".$account['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
    $selected_account = mysqli_fetch_assoc(mysqli_query($conn, $query));
    foreach ($selected_account as $account_key => $account_value) {
        $accounts[$key][$account_key] = $account_value;
    }
    $accounts[$key]['credit'] = 0;
    $accounts[$key]['debit'] = 0;
    $accounts[$key]['balance'] = 0;
    $fetch_ledger = [
        'source' => $account['acc_id'],
        'pay_to' => $account['acc_id'],
        'project' => $_SESSION['project']
    ];
    $ledger_history = $ledger_obj->fetch($fetch_ledger);
    if (!empty($ledger_history)) {
        foreach ($ledger_history as $ledger) {
            $accounts[$key]['credit'] += $ledger['credit'];
            $accounts[$key]['debit'] += $ledger['debit'];
        }
        $accounts[$key]['balance'] = $accounts[$key]['credit'] - $accounts[$key]['debit'];
    }
}
##################################### All Accounts #####################################

$query = "SELECT * FROM `payment` WHERE `type` = 'paid' AND `project_id` = '".$_SESSION['project']."';";
$all_payments = fetch_Data($conn, $query);

if (!empty($all_payments)) {
    foreach ($all_payments as $key => $payment) {
        $fetch_ledger = [
            "v-id" => $payment['voucher_id'],
            "project" => $_SESSION['project']
        ];
        $single_payment = $ledger_obj->fetch($fetch_ledger);
        $payments[$key] = $single_payment[0];
        $payments[$key]['date'] = $payment['date'];
    }
}

// echo "<pre>";
// print_r($banks);
// print_r($accounts);
// print_r($payments);
// exit();

$title = "All Paid Payments";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
</head>

<body>
    <?php include('temp/aside.temp.php'); ?>
    <main class="content">
        <?php include('temp/nav.temp.php'); ?>

        <div class="py-3">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
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
                        <a href="#">Transaction</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Payment Paid
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Payment Pay</h1>
                <div>
                    <a href="#" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                        View All Payments
                    </a>
                </div>
            </div>
        </div>

        <div style="row-gap: 25px;" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bolder">General Information</h6>
                        <form method="POST" action="comp/payment.pay.add.php" class="row">
                            <div class="col-2">
                                <div class="mb-2">
                                    <label for="" class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" id="date" required />
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-2">
                                    <label class="form-label d-flex justify-content-between align-items-center"
                                        for="sourceAccount">
                                        Source Account
                                        <span id="sourceBalance">Rs. 0</span>
                                    </label>
                                    <?php if (!empty($banks)) { ?>
                                    <select class="form-select" id="sourceAccount" name="sourceAccount" required>
                                        <option value="">Select Account</option>
                                        <?php foreach ($banks as $account) { ?>
                                        <option value="<?=$account['acc_id']?>">
                                            <?=($account['acc_id'] != "colony")?$account['name']." - ". formatAccountNumber($account['number']):$account['name']?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                    <?php } else { ?>
                                        <select class="form-select" disabled>
                                            <option value="" selected>No Bank Account Added</option>
                                        </select>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label class="form-label d-flex justify-content-between align-items-center"
                                        for="payTo">
                                        Pay To
                                        <span id="payToBalance">Rs. 0</span>
                                    </label>
                                    <select class="form-select" id="payTo" name="payTo" required>
                                        <option value="">Select </option>
                                        <?php foreach ($accounts as $account) { ?>
                                        <option value="<?=$account['acc_id']?>">
                                            <?=($account['type'] != "expense")?$account['name']." - ". phone_no_format($account['phone_no']):$account['name']." - Expense"?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="text" class="form-control comma" name="amount" id="amount" required />
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="mb-2">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <input type="text" class="form-control" name="remarks" id="remarks" required />
                                </div>
                            </div>
                            <div class="col-3 pb-2 d-flex align-items-end">
                                <input type="submit" value="Submit" class="btn btn-danger w-100" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive py-4">
                        <table class="table table-flush table-hover" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">v-id</th>
                                    <th class="border-0">Source Acc.</th>
                                    <th class="border-0">Payable Acc.</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Remarks</th>
                                    <th class="border-0 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($payments)) { 
                                    foreach ($payments as $key => $payment) { ?>
                                <tr>
                                    <td>
                                        <?=$key+1?>
                                    </td>
                                    <td><?=$payment['date']?></td>
                                    <td><?=$payment['v-id']?></td>
                                    <td class="text-capitalize"><?php 
                                    foreach ($banks as $bank) {
                                        if ($payment['source'] == $bank['acc_id']) {
                                            echo $bank['name'];
                                        }
                                    }
                                    ?></td>
                                    <td class="text-capitalize"><?php 
                                    foreach ($accounts as $account) {
                                        if ($payment['pay_to'] == $account['acc_id']) {
                                            echo $account['name'];
                                        }
                                    }
                                    ?></td>
                                    <td>Rs. <?=(!empty($payment['amount']))?number_format($payment['amount']):"0"?></td>
                                    <td><?=$payment['remarks']?></td>
                                    <td style="column-gap: 15px;" class="d-flex justify-content-end align-items-center">
                                        <div class="btn-group">
                                            <button class="btn btn-link dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                title="Delete">
                                                <svg class="icon icon-xs text-danger" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                                                <a class="dropdown-item d-flex align-items-center" href="comp/payment.pay.delete.php?i=<?=encryptor("encrypt", $payment['v-id'])?>">
                                                    <svg class="icon icon-xs dropdown-icon text-success me-2"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Yes
                                                </a>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    data-bs-dismiss="dropdown">
                                                    <svg class="icon icon-xs dropdown-icon text-danger me-2"
                                                        fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>

                                                    No
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="8">No Payment Added ...</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>

    <script>
    $(function() {
        <?php if (isset($_GET['m'])) { ?>
    <?php if ($_GET['m'] == 'add_true') { ?>
    notify("success", "Payment Pay Action Successful ...");
    <?php } elseif ($_GET['m'] == 'add_false') { ?>
    notify("error", "Something's Wrong, Report Error ...");
    <?php } ?>
    <?php } ?>
        // Current Date
        const currentDate = new Date().toISOString().split('T')[0];
        $('#date').val(currentDate);
        // Current Date
        var banks = <?=(!empty($banks))?json_encode($banks):[]?>;
        console.log(banks);
        var accounts = <?=(!empty($accounts))?json_encode($accounts):[]?>;
        console.log(accounts);

        balance("#sourceAccount", banks, "#sourceBalance");
        balance("#payTo", accounts, "#payToBalance");
    });

    function balance(input,accounts,span) {
        $(input).on("change", function() {
            if ($(this).val()) {
                accounts.forEach(account => {
                    if ($(this).val() == account['acc_id']) {
                        $(span).text("Rs. " + parseInt(account['balance']).toLocaleString());
                    }
                });
            } else {
                $(span).text("Rs. 0");
            }
        });
    }
    </script>
</body>

</html>