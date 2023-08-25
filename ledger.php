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
$conn = conn("localhost", "root", "", "pine-valley");                   #
####################### Database Connection #######################

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
                                    <th class="border-0 text-center">Type</th>
                                    <th class="border-0 text-center">Source</th>
                                    <th class="border-0">Remarks</th>
                                    <th class="border-0 text-end">Credit</th>
                                    <th class="border-0 text-end">Debit</th>
                                    <th class="border-0 text-end rounded-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(TRUE){
                                    for ($i=0; $i < 10; $i++) { ?>
                                <tr>
                                    <td class="fw-bolder">
                                        <?=$i+1?>
                                    </td>
                                    <td>PR-3</td>
                                    <td>Payment Recieved</td>
                                    <td class="fw-bold text-center">Ali Abdullah</td>
                                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, nesciunt!</td>
                                    <td class="text-center text-success"><?=$arrow_up?> Rs. 45,000,000</td>
                                    <td></td>
                                    <td class="fw-bold text-end">Rs. 80,000</td>
                                </tr>
                                <tr>
                                    <td class="fw-bolder">
                                        <?=$i+1?>
                                    </td>
                                    <td>PR-3</td>
                                    <td>Payment Recieved</td>
                                    <td class="fw-bold text-center">Ali Abdullah</td>
                                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, nesciunt!</td>
                                    <td></td>
                                    <td class="text-center text-danger"><?=$arrow_down?>Rs. 45,000,000</td>
                                    <td class="fw-bold text-end">Rs. 80,000</td>
                                </tr>
                                <?php } } ?>
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