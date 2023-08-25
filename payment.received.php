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
$conn = conn("localhost", "root", "", "communiSync");                   #
####################### Database Connection #######################

$title = "All Receieved Payments";
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
                        Payment Received
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Payment Receive</h1>
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
                        <div class="row">
                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="" class="form-label">Date</label>
                                    <input type="date" class="form-control" name="" id="" />
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label class="my-1 me-2" for="">Source Account</label>
                                    <select class="form-select" id="" name="">
                                        <option value="">Select </option>
                                        <?php for ($i = 0; $i < 5; $i++) { ?>
                                        <option value="2">UBL</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label class="my-1 me-2" for="">Pay To</label>
                                    <select class="form-select" id="" name="">
                                        <option value="">Select </option>
                                        <?php for ($i = 0; $i < 5; $i++) { ?>
                                        <option value="3">Abdul Rehman</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="" class="form-label">Amount</label>
                                    <input type="number" class="form-control" name="" id="" />
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="mb-2">
                                    <label for="" class="form-label">Remarks</label>
                                    <input type="text" class="form-control" name="" id="" />
                                </div>
                            </div>
                            <div class="col-3 pb-2 d-flex align-items-end">
                                <input type="submit" value="Submit" class="btn btn-outline-gray-600 w-100" />
                            </div>
                        </div>
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
                                    <th class="border-0">Source Acc.</th>
                                    <th class="border-0">Payable Acc.</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Remarks</th>
                                    <th class="border-0 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < 10; $i++) { ?>
                                <tr>
                                    <td>
                                        <?=$i+1?>
                                    </td>
                                    <td>24-05-2023</td>
                                    <td>Ali Abdullah</td>
                                    <td>UBL</td>
                                    <td>Rs. 5,000</td>
                                    <td>Lorem ipsum dolor sit amet consectetur adipisicing.</td>
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
                                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                                style="">
                                                <a class="dropdown-item d-flex align-items-center" href="#">
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
                                                    <i class="fa-solid fa-circle-xmark fs-5"></i>
                                                    No
                                                </a>
                                            </div>
                                        </div>
                                    </td>
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