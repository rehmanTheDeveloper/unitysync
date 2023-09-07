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
$conn = conn("localhost", "root", "", "unitySync");                   #
####################### Database Connection #######################

$query = "SELECT `v-id`,`type`,`source`,`remarks`,`credit`,`debit`, @balance := @balance - debit + credit AS balance
FROM `ledger`, (SELECT @balance := 0) AS vars WHERE `project_id` = '".$_SESSION['project']."';";
$ledger = fetch_Data($conn, $query);

// echo "<pre>";
// print_r($ledger);
// exit();

$title = "Ledger";

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
                        Ledger
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Ledger</h1>
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
                        <table class="table table-centered table-hover table-nowrap mb-0 rounded" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 rounded-start">#</th>
                                    <th class="border-0 text-center">V-ID</th>
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
                                    <td class="fw-bold text-center"><?=$led['source']?></td>
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