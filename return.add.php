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
####################### Database Connection #######################

$title = "Add Return";
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
                        Add Return
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Add Return</h1>
                <div>
                    <a href="return.all.php" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                        All Returns
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bolder">General Information</h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="saleCustomer">Select Customer</label>
                                            <select class="form-select" name="saleCustomer" id="saleCustomer">
                                                <option value="" selected>Select Customer</option>
                                                <?php for ($i = 0; $i < 4; $i++) { ?>
                                                <option value="abdul rehman">Abdul Rehman</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 py-3">
                                        <h5 class="text-dark">Profile</h5>
                                        <img class="img-fluid" src="assets/img/profile.png" alt="" srcset="">
                                    </div>
                                    <div class="col-lg-7 py-3">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="saleProperty">Select Property</label>
                                                    <select class="form-select" name="saleProperty" id="saleProperty">
                                                        <option value="" selected>Select Property</option>
                                                        <?php for ($i = 0; $i < 4; $i++) { ?>
                                                        <option value="abdul rehman">Abdul Rehman</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 py-2">
                                                <label for="">Balance Sheet</label>
                                                <div class="card shadow-sm rounded-5">
                                                    <table class="table table-centered table-nowrap mb-0 rounded">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="border-0 rounded-start">Detail</th>
                                                                <th class="border-0 rounded-end"
                                                                    style="text-align: right;">Amount
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="balanceSheet">
                                                            <tr>
                                                                <td>Received Amount</td>
                                                                <td style="text-align: right;">10,00,000</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cancel Fee</td>
                                                                <td style="text-align: right;" class="text-success">
                                                                    -5,000</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Processing Fee</td>
                                                                <td style="text-align: right;" class="text-success">
                                                                    -15,000</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-danger">Pay. Balance</td>
                                                                <td style="text-align: right;" class="text-danger">
                                                                    9,80,000</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <input class="btn btn-outline-gray-600 my-2" type="submit" name="submit"
                                    value="Submit" />
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
    $(function() {

    });
    </script>
</body>

</html>