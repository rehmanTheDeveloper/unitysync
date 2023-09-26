<?php
session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php");                           #
$license_path = "license/".$_SESSION['license_username']."/license.json"; #
require("auth/license.validate.functions.php");                    #
require("temp/validate.license.temp.php");                         #
#################### Login & License Validation ####################

####################### Database Connection #######################
require("auth/config.php"); #
require("auth/functions.php"); #
$conn = conn("localhost", "root", "", "unitySync"); #
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
######################## Database Connection #######################

include "object/ledger.php";
$ledger_obj = new Ledger($PDO_conn);

if (empty($_GET['i'])) {
    header("Location: property.all.php?m=not_found");
    exit();
}

$query = "SELECT `type` FROM `properties` WHERE `pty_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$property_type = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (empty($property_type)) {
    header("Location: property.all.php?m=not_found");
    exit();
}

$query = "SELECT * FROM `".$property_type['type']."` WHERE `pty_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$property = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT `name`, `street` FROM `blocks` WHERE `id` = '".$property['block']."' AND `project_id` = '".$_SESSION['project']."';";
$block = mysqli_fetch_assoc(mysqli_query($conn, $query));
$property['block_name'] = $block['name'];
$property['block_street'] = $block['street'];

$query = "SELECT * FROM `sale_installment` WHERE `pty_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$sale = mysqli_query($conn, $query);
if (mysqli_num_rows($sale) > 0) {
    $property['delete'] = 0;
    $sale = mysqli_fetch_assoc($sale);

    $query = "SELECT `type` FROM `sales` WHERE `sale_id` = '".$sale['sale_id']."' AND `project_id` = '".$_SESSION['project']."';";
    $sale_type = mysqli_fetch_assoc(mysqli_query($conn, $query));
    $sale['type'] = $sale_type['type'];

    $query = "SELECT * FROM `customer` WHERE `acc_id` = '".$sale['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
    $customer = mysqli_fetch_assoc(mysqli_query($conn, $query));
    
    $sale['debit'] = 0;
    $sale['credit'] = 0;
    $fetch_ledger = [
        'type' => "sale",
        'sale_id' => $sale['sale_id'],
        'source' => $sale['acc_id'],
        'pay_to' => $sale['acc_id'],
        'project' => $_SESSION['project']
    ];
    // $property['ledger'] = $fetch_ledger;
    $sale_ledger = $ledger_obj->fetch($fetch_ledger);
    foreach ($sale_ledger as $single_ledger) {
        $sale['debit'] += $single_ledger['debit'];
        $sale['credit'] += $single_ledger['credit'];
    }
    if (($sale['debit'] - $sale['credit']) != 0) {
        $property['status'] = 2;
    } elseif (($sale['debit'] - $sale['credit']) == 0) {
        $property['status'] = 1;
    }
} else {
    $sale = [];
}

if (empty($sale)) {
    $query = "SELECT * FROM `sale_net_cash` WHERE `pty_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
    $sale = mysqli_query($conn, $query);
    if (mysqli_num_rows($sale) > 0) {
        $property['delete'] = 0;
        $sale = mysqli_fetch_assoc($sale);

        $query = "SELECT `type` FROM `sales` WHERE `sale_id` = '".$sale['sale_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $sale_type = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $sale['type'] = $sale_type['type'];

        $query = "SELECT * FROM `customer` WHERE `acc_id` = '".$sale['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $customer = mysqli_fetch_assoc(mysqli_query($conn, $query));
        
        $sale['debit'] = 0;
        $sale['credit'] = 0;
        $fetch_ledger = [
            'type' => "sale",
            'sale_id' => $sale['sale_id'],
            'source' => $sale['acc_id'],
            'pay_to' => $sale['acc_id'],
            'project' => $_SESSION['project']
        ];
        // $property['ledger'] = $fetch_ledger;
        $sale_ledger = $ledger_obj->fetch($fetch_ledger);
        foreach ($sale_ledger as $single_ledger) {
            $sale['debit'] += $single_ledger['debit'];
            $sale['credit'] += $single_ledger['credit'];
        }
        if (($sale['debit'] - $sale['credit']) != 0) {
            $property['status'] = 2;
        } elseif (($sale['debit'] - $sale['credit']) == 0) {
            $property['status'] = 1;
        }
    } else {
        $sale = [];
    }
}

// echo "<pre>";
// print_r($property);
// print_r($sale);
// print_r($customer);
// print_r($sale_ledger);
// exit();

$query = "SELECT `sqft_per_marla` FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project_details = mysqli_fetch_assoc(mysqli_query($conn, $query));

$title = "Property - ". strtoupper(substr($property_type['type'],0,1)).substr($property_type['type'],1)." #". $property['pty_id'];
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
                        <a href="#">Transaction</a>
                    </li>
                    <li class="breadcrumb-item text-capitalize active" aria-current="page">
                        property - <?=$property_type['type']?> #<?=$property['pty_id']?>
                    </li>
                </ol>
                <div class="btn-group">
                    <a href="sale.print.php" class="btn btn-outline-gray-800">
                        Print Voucher
                    </a>
                    <?php if (!empty($sale)) { ?>
                    <a href="account.view.php?i=<?=encryptor("encrypt", $sale['acc_id'])?>"
                        class="btn btn-outline-gray-800">
                        Customer Details
                    </a>
                    <?php } ?>
                    <a href="property.all.php" class="btn btn-outline-gray-800">
                        All Properties
                    </a>
                </div>
            </nav>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center text-capitalize mb-3"><?=$property_type['type']?> -
                                    #<?=$property['pty_id']?></h2>
                            </div>
                            <div class="col-12">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Label</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <tr>
                                            <td>Property</td>
                                            <td class="text-end text-capitalize">
                                                <?=$property_type['type'] . " #" . $property['number']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Block</td>
                                            <td class="text-end">
                                                <?=$property['block_name'] . "-" . $property['block_street']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Plot Dimension</td>
                                            <td class="text-end">
                                                <span data-bs-toggle="tooltip" data-bs-original-title="Width * Length">
                                                    <?=$property['width']?> x
                                                    <?=$property['length']?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Area</td>
                                            <td class="text-end">
                                                <?=floor($property['sqft'] / $project_details['sqft_per_marla'])?>
                                                marla -
                                                <?=number_format(($property['sqft'] - floor($property['sqft'] / $project_details['sqft_per_marla']) * $project_details['sqft_per_marla']))?>
                                                Sqft.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Category</td>
                                            <td class="text-end text-capitalize">
                                                <?=$property['category']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Rate</td>
                                            <td class="text-end text-capitalize">
                                                Rs. <?=number_format($property['rate'])?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (!empty($sale)) { ?>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center text-capitalize mb-3">Sale Details</h2>
                            </div>
                            <div class="col-12">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Label</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <tr>
                                            <td>Type</td>
                                            <td class="text-end text-capitalize">
                                                <?=($sale['type'] == "net_cash")?"net cash":"installment"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sale Id</td>
                                            <td class="text-end">
                                                <?=$sale['sale_id']?>
                                            </td>
                                        </tr>
                                        <?php if ($sale['type'] == "installment") { ?>
                                        <tr>
                                            <td>Installment Amount</td>
                                            <td class="text-end">
                                                Rs.
                                                <?=number_format(($sale['price'] - $sale['advance_payment']) / $sale['installments'])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Installments</td>
                                            <td class="text-end">
                                                <?=$sale['installments']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Paid Installments</td>
                                            <td class="text-end">
                                                <?=number_format(($sale['debit'] - $sale['advance_payment']) / (($sale['price'] - $sale['advance_payment']) / $sale['installments']))?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr>
                                            <td>Total Amount</td>
                                            <td class="text-end">
                                                Rs. <?=number_format($sale['price'])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Paid Amount</td>
                                            <td class="text-end text-success">
                                                Rs. <?=number_format($sale['debit'])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pending Amount</td>
                                            <td class="text-end text-danger">
                                                Rs.
                                                <?=number_format($sale['price'] - $sale['debit'])?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center text-capitalize mb-3">Owner Details</h2>
                            </div>
                            <div class="col-12">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Label</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <tr>
                                            <td>Owner</td>
                                            <td class="text-end">
                                                <?=$customer['name']?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td class="text-end">
                                                +92 <?=phone_no_format($customer['phone_no'])?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CNIC</td>
                                            <td class="text-end"><?=cnic_format($customer['cnic'])?></td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td class="text-end"><?=$customer['address']?></td>
                                        </tr>
                                        <tr>
                                            <td>Location</td>
                                            <td class="text-end text-capitalize">
                                                <?=$customer['city'].", ".$customer['province'].", ".$customer['country']?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if(TRUE) { ?>
            <div class="card my-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-centered table-nowrap mb-0 rounded" id="datatable">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0 rounded-start">#</th>
                                        <th class="border-0">Transfered From</th>
                                        <th class="border-0 text-end rounded-end">Transfered To</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold">
                                    <tr>
                                        <td>1</td>
                                        <td class="text-start">
                                            <span class="fw-bolder">Abdul Rehman</span><br />
                                            <span>+92 306 436322262</span><br />
                                            <span>Block B - Street #243 - #23</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bolder">Ali Ahmad</span><br />
                                            <span>+92 306 436322262</span><br />
                                            <span>Block B - Street #243 - #23</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="text-start">
                                            <span class="fw-bolder">Ali Ahmad</span><br />
                                            <span>+92 306 436322262</span><br />
                                            <span>Block B - Street #243 - #23</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bolder">Ali Abdullah</span><br />
                                            <span>+92 306 436322262</span><br />
                                            <span>Block B - Street #243 - #23</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>
    <script>
    $(function() {
        $("#back").on("click", function() {
            window.history.back();
        });
    });
    var optionsPieChart = {
        series: [<?=$property['credit']?>,
            <?=$property['debit'] - $property['credit']?>
        ],
        chart: {
            type: 'pie',
            height: 360,
        },
        theme: {
            monochrome: {
                enabled: true,
                color: '#1F2937',
            }
        },
        labels: ['Paid', 'Unpaid'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        tooltip: {
            fillSeriesColor: false,
            onDatasetHover: {
                highlightDataSeries: false,
            },
            theme: 'light',
            style: {
                fontSize: '12px',
                fontFamily: 'Montserrat',
            },
            y: {
                formatter: function(val) {
                    return "Rs. " + val.toLocaleString()
                }
            }
        }
    };

    var pieChartEl = document.getElementById('totalAmount');
    if (pieChartEl) {
        var pieChart = new ApexCharts(pieChartEl, optionsPieChart);
        pieChart.render();
    }
    </script>
</body>

</html>