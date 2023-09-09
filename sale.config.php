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

$query = "SELECT `sqft_per_marla` FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project_details = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `customer` WHERE `project_id` = '".$_SESSION['project']."';";
$customers = fetch_Data($conn, $query);

$query = "SELECT * FROM `plot` WHERE `project_id` = '".$_SESSION['project']."';";
$plots = fetch_Data($conn, $query);

$query = "SELECT * FROM `flat` WHERE `project_id` = '".$_SESSION['project']."';";
$flats = fetch_Data($conn, $query);

// echo "<pre>";
// print_r($plots);
// print_r($flats);
// exit();

$title = "Add Sale";
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
                        Add Sale
                    </li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Add Sale</h1>
                <div class="btn-group">
                    <a href="sale.all.php" class="btn btn-outline-gray-800 d-inline-flex align-items-center">
                        All Sales
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="fw-bolder">General Information</h6>
                        <form class="row" action="" method="post">
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="customer">Select Customer</label>
                                    <?php if (!empty($customers)) { ?>
                                    <select class="form-select" name="customer" id="customer">
                                        <option value="" selected>Select Customer</option>
                                        <?php foreach ($customers as $customer) { ?>
                                        <option value="<?=$customer['acc_id']?>"><?=$customer['name']?>,
                                            <?=cnic_format($customer['cnic'])?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } else { ?>
                                    <select class="form-select" disabled data-bs-toggle="tooltip"
                                        data-bs-original-title="No Customer Available ...">
                                        <option value="" selected>No Customer has been Added</option>
                                    </select>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between py-1">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input mb-0" type="radio" name="propertyType"
                                                value="plot" id="plot" checked />
                                            <label class="form-check-label mb-0" for="plot">Select Plot
                                                <svg class="icon icon-xs ms-2" data-bs-toggle="tooltip"
                                                    data-bs-original-title="Select Plot to Generate Sale ..."
                                                    fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </label>
                                        </div>
                                        <div class="form-check mb-0">
                                            <input class="form-check-input mb-0" type="radio" name="propertyType"
                                                id="flat" value="flat" />
                                            <label class="form-check-label mb-0" for="flat">Select Flat
                                                <svg class="icon icon-xs ms-2" data-bs-toggle="tooltip"
                                                    data-bs-original-title="Select Flat to Generate Sale ..."
                                                    fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="plotSection">
                                        <?php if (!empty($plots)) { ?>
                                        <select class="form-select property" name="property">
                                            <?php foreach ($plots as $key => $property) { ?>
                                            <option value="<?=encryptor("encrypt", $property['pty_id'])?>">Plot
                                                #<?=$property['number']?>,
                                                <?=number_format($property['sqft'] / $project_details['sqft_per_marla'],2)?>
                                                Marlas</option>
                                            <?php  ?>
                                            <?php } ?>
                                        </select>
                                        <?php } else { ?>
                                        <select class="form-select" disabled data-bs-toggle="tooltip"
                                            data-bs-original-title="No Plot Available ...">
                                            <option value="" selected>No Property has been Added</option>
                                        </select>
                                        <?php } ?>
                                    </div>
                                    <div style="display: none;" class="flatSection">
                                        <?php if (!empty($flats)) { ?>
                                        <select class="form-select property" name="property">
                                            <?php foreach ($flats as $key => $property) { ?>
                                            <option value="<?=encryptor("encrypt", $property['pty_id'])?>">Flat
                                                #<?=$property['number']?>,
                                                <?=number_format($property['sqft'] / $project_details['sqft_per_marla'],2)?>
                                                Marlas</option>
                                            <?php  ?>
                                            <?php } ?>
                                        </select>
                                        <?php } else { ?>
                                        <select class="form-select" disabled data-bs-toggle="tooltip"
                                            data-bs-original-title="No Flat Available ...">
                                            <option value="" selected>No Property has been Added</option>
                                        </select>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="paymentType" class="form-label">Payment Type</label>
                                    <select class="form-select" name="paymentType" id="paymentType">
                                        <option value="installment">Installments</option>
                                        <option value="netCash">Net Cash</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 installmentSection" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="propertyPrice" class="form-label">Property Price</label>
                                            <input type="text" class="form-control bg-white" id="propertyPrice"
                                                name="propertyPrice" aria-describedby="propertyPrice" readonly />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="advancePayment" class="form-label">Advance Payment</label>
                                            <input type="text" class="form-control" id="advancePayment"
                                                name="advancePayment" aria-describedby="advancePayment" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 saleBalance">
                                        <div class="mb-3">
                                            <label for="saleBalance" class="form-label">Balance</label>
                                            <input type="text" class="form-control bg-white" id="saleBalance"
                                                name="saleBalance" aria-describedby="saleBalance" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="saleinsts" class="form-label">Total Installments</label>
                                            <input type="number" class="form-control" id="saleinsts" name="saleinsts"
                                                aria-describedby="saleinsts" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="saleinstdate" class="form-label">Select Installment
                                                Date</label>
                                            <input type="date" class="form-control" id="saleinstdate"
                                                name="saleinstdate" aria-describedby="saleinstdate" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 netCashSection" style="display: none;">

                            </div>
                            <div class="col-lg-12 text-center">
                                <input class="btn btn-outline-gray-600 my-3" type="submit" name="submit"
                                    value="submit" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>
    <script>
    $(function() {
        $('#paymentType').trigger("change");
    });

    $('input[name="propertyType"]').change(function() {
        var selectedMethod = $(this).val();
        // Hide all sections
        $('.plotSection').hide();
        $('.flatSection').hide();

        // Show the selected section
        $('.' + selectedMethod + 'Section').show();
        $('.' + selectedMethod + 'Section').val("");
    });

    $('#paymentType').change(function() {
        var selectedMethod = $(this).val();
        // Hide all sections
        $('.installmentSection').hide();
        $('.netCashSection').hide();

        // Show the selected section
        $('.' + selectedMethod + 'Section').show();
        $('.' + selectedMethod + 'Section input').val("");
    });
    </script>
</body>

</html>