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
$conn = conn("localhost", "root", "", "unitySync");                   #
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
####################### Database Connection #######################

include "object/ledger.php";

$colony_validation = FALSE;
$accounts = [];
$query = "SELECT * FROM `customer` WHERE `project_id` = '".$_SESSION['project']."';";
$customers = fetch_Data($conn, $query);

$query = "SELECT * FROM `bank` WHERE `project_id` = '".$_SESSION['project']."';";
$banks = fetch_Data($conn, $query);

$fetch_ledger = [
    'type' => "sale",
    'project' => $_SESSION['project']
];
$ledger_obj = new Ledger($PDO_conn);
$ledger = $ledger_obj->fetch($fetch_ledger);

if (!empty($customers) && !empty($banks)) {
    $a = 0;
    foreach ($customers as $customer) {
        $accounts[] = $customer;
        $accounts[$a]['type'] = "customer";
        $a++;
    }
    foreach ($banks as $bank) {
        $accounts[] = $bank;
        $accounts[$a]['type'] = "bank";
        $a++;
    }
}

$query = "SELECT * FROM `sales` WHERE `project_id` = '".$_SESSION['project']."';";
$sql = mysqli_query($conn, $query);
$all_sales = fetch_Data($conn, $query);
$total_sales['total'] = 0;
$total_sales['complete'] = 0;
$total_sales['total_amount'] = 0;
$total_sales['recieved_amount'] = 0;

if (!empty($all_sales)) {
    foreach ($all_sales as $key => $sale) {
        $query = "SELECT * FROM `sale_".$sale['type']."` WHERE `sale_id` = '".$sale['sale_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $sales[$key] = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $sales[$key]['sale_type'] = $sale['type'];
    }
    foreach ($sales as $key => $sale) {
        $sale['debit'] = 0;
        $sale['credit'] = $sales[$key]['price'];
        $fetch_ledger = [
            'type' => "sale",
            'sale_id' => $sale['sale_id'],
            'source' => $sale['acc_id'],
            'pay_to' => $sale['acc_id'],
            'project' => $_SESSION['project']
        ];
        $sale_ledger = $ledger_obj->fetch($fetch_ledger);
        foreach ($sale_ledger as $single_ledger) {
            $sale['debit'] += $single_ledger['debit'];
            $sale['credit'] += $single_ledger['credit'];
        }
        if (($sale['debit'] - $sale['credit']) == 0) {
            $total_sales['total'] += count($all_sales);
            $total_sales['complete'] += 1;
        }
        $total_sales['recieved_amount'] += $sale['debit'];
        $total_sales['total_amount'] += $sales[$key]['price'];
        // print_r($property);
    }
    $total_sales['pending'] = $total_sales['total'] - $total_sales['complete'];
}

// echo "<pre>";
// print_r($accounts);
// print_r($ledger);
// print_r($sales);
// print_r($total_sales);
// exit();

$title = "Sale Ledger";
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
                        <a href="#">Reports</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Sale Ledger
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Sale Ledger</h1>
                <!-- <div class="btn-group">
                    <a href="#" class="btn btn-outline-gray-800 d-inline-flex align-items-center">
                        Export
                    </a>
                </div> -->
            </div>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-5 col-md-7">
                                <div class="card card-body mb-3">
                                    <span class="me-4">Pending Amount: <strong class="text-danger">Rs.
                                            <?=number_format($total_sales['total_amount']-$total_sales['recieved_amount'])?></strong></span>
                                    <span class="me-4">Completed Sales: <strong
                                            class="text-success"><?=$total_sales['complete']?></strong></span>
                                    <span class="me-4">Pending Sales: <strong
                                            class="text-warning"><?=$total_sales['pending']?></strong></span>
                                    <span class="me-4">Recieved Amount: <strong class="text-success">Rs.
                                            <?=number_format($total_sales['recieved_amount'])?></strong></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <table class="table table-centered table-hover table-nowrap mb-0 rounded"
                                    id="datatable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">#</th>
                                            <th class="border-0 text-center">V-ID</th>
                                            <th class="border-0 text-center">Source</th>
                                            <th class="border-0 text-center">Pay To</th>
                                            <th class="border-0">Sale Id</th>
                                            <th class="border-0">Remarks</th>
                                            <th class="border-0 text-end rounded-end">Amount</th>
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
                                            <td class="fw-bold text-center text-capitalize">
                                                <?php foreach ($accounts as $account) {
                                            if ($led['source'] == $account['acc_id']) { ?>
                                                <a
                                                    href="account.view.php?i=<?=encryptor("encrypt", $account['acc_id'])?>">
                                                    <?=$account['type']." - ".$account['name']?>
                                                </a>
                                                <?php $colony_validation = TRUE; 
                                            }
                                        }
                                        if ($colony_validation == FALSE) {
                                            echo "Colony";
                                        }
                                        $colony_validation = FALSE;
                                        ?>
                                            </td>
                                            <td class="fw-bold text-center text-capitalize">
                                                <?php foreach ($accounts as $account) {
                                            if ($led['pay_to'] == $account['acc_id']) { ?>
                                                <a
                                                    href="account.view.php?i=<?=encryptor("encrypt", $account['acc_id'])?>">
                                                    <?=$account['type']." - ".$account['name']?>
                                                </a>
                                                <?php $colony_validation = TRUE; 
                                                }
                                        }
                                        if ($colony_validation == FALSE) {
                                            echo "Colony";
                                        }
                                        $colony_validation = FALSE;
                                        ?>
                                            </td>
                                            <td>
                                                <?php foreach ($sales as $sale) {
                                                    if ($sale['sale_id'] == $led['sale_id']) { ?>
                                                <a href="property.view.php?i=<?=encryptor("encrypt", $sale['pty_id'])?>"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-original-title="Click to View <?="Property #" . $sale['pty_id']?> Details ..."><?=$led['sale_id'] . " - Property #" . $sale['pty_id']?></a>
                                                <?php } } ?>
                                            </td>
                                            <td>
                                                <span data-bs-toggle="tooltip"
                                                    data-bs-original-title="<?=$led['remarks']?>">
                                                    <?php 
                                            if (strlen($led['remarks']) > 63) {
                                                echo substr($led['remarks'],0,45)."...";
                                            } else {
                                                echo $led['remarks'];
                                            } ?>
                                                </span>
                                            </td>
                                            <td class="fw-bold text-end text-success">Rs.
                                                <?=number_format($led['amount'])?>
                                                <?=$arrow_up?></td>
                                        </tr>
                                        <?php } } else { ?>
                                        <tr>
                                            <td class="text-center" colspan="7">No ledger history ...</td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 d-flex justify-content-end">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>

    <script>
    var dummyTable = d.getElementById('datatable');
    if (dummyTable) {
        const dataTable = new simpleDatatables.DataTable(dummyTable, {
            // searchable: false,
            // fixedHeight: true
        });
    }
    </script>
</body>

</html>