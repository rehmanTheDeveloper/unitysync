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

$title = "Property - Plot #234";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
</head>

<body>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 p-4" id="propertyDetails">
                <div class="d-flex justify-content-between pb-4 pb-md-5 mb-4 mb-md-5 border-bottom border-light">
                    <img class="image-md" src="assets/img/brand/light.svg" height="30" width="30"
                        alt="Rocket Logo">
                    <div>
                        <h4>Pine Valley</h4>
                        <ul class="list-group simple-list">
                            <li class="list-group-item fw-normal">Lorem, ipsum dolor.</li>
                            <li class="list-group-item fw-normal">Pakpattan, Pakistan</li>
                            <li class="list-group-item fw-normal">
                                <a class="fw-bold text-primary" href="#">
                                    loremipsum@example.com
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mb-6 d-flex align-items-center justify-content-center">
                    <h2 class="h1 mb-0">Invoice #00123</h2>
                    <!-- <span class="badge badge-lg bg-success ms-4">Paid</span> -->
                </div>
                <div class="row justify-content-between mb-4 mb-md-5">
                    <div class="col-sm">
                        <h5>Purchaser Information:</h5>
                        <div>
                            <ul class="list-group simple-list">
                                <li class="list-group-item fw-normal">Ali Abdullah - 36402-42643663-1</li>
                                <li class="list-group-item fw-normal">+92 345 674333583</li>
                                <li class="list-group-item fw-normal">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                </li>
                                <li class="list-group-item fw-normal">
                                    <a class="fw-bold text-primary" href="#">
                                        example@person.com
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm col-lg-4">
                        <dl class="row text-sm-right">
                            <dt class="col-6">
                                <strong>Invoice No.</strong>
                            </dt>
                            <dd class="col-6">
                                00123
                            </dd>
                            <dt class="col-6">
                                <strong>Date Issued:</strong>
                            </dt>
                            <dd class="col-6">
                                31/03/2020
                            </dd>
                            <dt class="col-6">
                                <strong>Date Due:</strong>
                            </dt>
                            <dd class="col-6">
                                30/04/2020
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="bg-light border-top">
                                    <tr>
                                        <th scope="row" class="border-0">Property</th>
                                        <th scope="row" class="border-0">Total Insts.</th>
                                        <th scope="row" class="border-0">Amount Per Inst.</th>
                                        <th scope="row" class="border-0 text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="fw-bold h6">Plot - #234</th>
                                        <td>36 Inst.</td>
                                        <td>Rs. 15,270</td>
                                        <td class="text-end">Rs. 550,000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between text-right mb-4 py-4">
                            <div class="mt-4 d-flex align-items-end">
                                <h5>Sign. </h5>___________________
                            </div>
                            <div class="mt-4">
                                <table class="table table-clear">
                                    <tbody>
                                        <tr>
                                            <td class="left"><strong>Subtotal</strong></td>
                                            <td class="right">Rs. 550,000</td>
                                        </tr>
                                        <tr>
                                            <td class="left"><strong>Discount (3%)</strong></td>
                                            <td class="right">Rs. 45,833</td>
                                        </tr>
                                        <!-- <tr>
                                            <td class="left"><strong>VAT (10%)</strong></td>
                                            <td class="right">$679,76</td>
                                        </tr> -->
                                        <tr>
                                            <td class="left"><strong>Total</strong></td>
                                            <td class="right"><strong>Rs. 504,166</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- <h4>Payments to:</h4><span>payment@volt.com</span> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('temp/script.temp.php'); ?>
    <script>
    $(document).ready(function() {
        // Print the page automatically when it loads
        window.print();
    });

    // Handle the onafterprint event
    $(window).on('afterprint', function() {
        // Return to the previous page after printing or canceling
        window.history.back();
    });
    </script>
</body>

</html>