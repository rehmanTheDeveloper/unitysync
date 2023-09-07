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

$title = "All Transfers";
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
                        <a href="#">Transactions</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        All Transfers
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">All Transfers</h1>
                <div class="btn-group">
                    <a href="transfer.add.php" class="btn btn-outline-gray-800 d-inline-flex align-items-center">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Transfer
                    </a>
                    <a href="#" class="btn btn-outline-gray-800 d-inline-flex align-items-center">
                        Export
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive py-4">
                <table class="table table-centered table-nowrap mb-4 rounded table-hover" id="datatable">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">#</th>
                            <th class="border-0 text-start">Transfer From.</th>
                            <th class="border-0 text-center">Location / Property</th>
                            <th class="border-0 text-end">Transfer To.</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 3; $i++) { ?>
                        <tr>
                            <td class="fw-bolder">
                                <br />
                                <?=$i+1?>
                            </td>
                            <td class="text-start">
                                <b>Abdul Rehman</b><br>
                                <strong>36402-3735735-1</strong><br>
                                <strong>+92 306 436322262</strong>
                            </td>
                            <td class="text-center">
                                <br />
                                <b>B-#43</b> / Plot - #23
                            </td>
                            <td class="text-end">
                                <b>Ali Abdullah</b><br>
                                <strong>36402-3735735-1</strong><br>
                                <strong>+92 306 436322262</strong>
                            </td>
                            <td>
                                <br />
                                <div class="float-end me-4">
                                    <a data-bs-toggle="tooltip" data-bs-original-title="View Property"
                                        aria-label="View Property" href="property.view.php">
                                        <svg class="icon icon-xs text-gray-700" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <a data-bs-toggle="tooltip" data-bs-original-title="Print" aria-label="Print"
                                        href="transfer.print.php">
                                        <svg lass="icon icon-xs text-gray-700" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
                                                clip-rule="evenodd"></path>
                                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
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