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
$conn = conn("localhost", "root", "", "unitySync");               #
####################### Database Connection #######################

$query = "SELECT `sqft_per_marla` FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project_details = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `customer` WHERE `project_id` = '".$_SESSION['project']."';";
$customers = fetch_Data($conn, $query);

$query = "SELECT * FROM `bank` WHERE `project_id` = '".$_SESSION['project']."';";
$banks = fetch_Data($conn, $query);

$query = "SELECT * FROM `plot` WHERE `project_id` = '".$_SESSION['project']."';";
$plots = fetch_Data($conn, $query);

// $query = "SELECT * FROM `flat` WHERE `project_id` = '".$_SESSION['project']."';";
// $flats = fetch_Data($conn, $query);

$query = "SELECT `sale_id`,`type` FROM `sales` WHERE `project_id` = '".$_SESSION['project']."';";
$all_sales = fetch_Data($conn, $query);

if (!empty($all_sales)) {
    foreach ($all_sales as $key => $sale) {
        $query = "SELECT `pty_id` FROM `sale_".$sale['type']."` WHERE `sale_id` = '".$sale['sale_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $property = mysqli_fetch_assoc(mysqli_query($conn, $query));

        foreach ($plots as $plot_key => $plot) {
            if ($property['pty_id'] == $plot['pty_id']) {
                unset($plots[$plot_key]);
            }
        }
        // foreach ($flats as $flat_key => $flat) {
        //     if ($property['pty_id'] == $flat['pty_id']) {
        //         unset($flats[$flat_key]);
        //     }
        // }
    }
}

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
                        <form class="row" action="comp/sale.add.php" method="post">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Sale Date</label>
                                    <input type="date" class="form-control comma" id="date" name="date"
                                        data-bs-toggle="tooltip" data-bs-original-title="mm/dd/yyyy"
                                        aria-describedby="date" />
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="customer">Select Customer</label>
                                    <?php if (!empty($customers)) { ?>
                                    <select class="form-select" name="customer" id="customer" required>
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
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="">Select Plot</label>
                                    <!-- <div class="d-flex justify-content-between py-1">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input mb-0" type="radio" name="propertyType"
                                                value="plot" id="selectPlot" checked />
                                            <label class="form-check-label mb-0" for="selectPlot">Select Plot
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
                                                id="selectFlat" value="flat" />
                                            <label class="form-check-label mb-0" for="selectFlat">Select Flat
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
                                    </div> -->
                                    <div class="plotSection">
                                        <?php if (!empty($plots)) { ?>
                                        <select class="form-select" name="plot" id="plot">
                                            <option value="" selected>Select Plot</option>
                                            <?php foreach ($plots as $key => $property) { ?>
                                            <option value="<?=$property['pty_id']?>">Plot
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
                                    <!-- <div style="display: none;" class="flatSection">
                                        <?php if (!empty($flats) && FALSE) { ?>
                                        <select class="form-select" name="flat" id="flat">
                                            <option value="" selected>Select Flat</option>
                                            <?php foreach ($flats as $key => $property) { ?>
                                            <option value="<?=$property['pty_id']?>">Flat
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
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="paymentType" class="form-label">Payment Type</label>
                                    <select class="form-select" name="paymentType" id="paymentType" required>
                                        <option value="installment" selected>Installments</option>
                                        <option value="net_cash">Net Cash</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 installmentSection">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="mb-3">
                                            <label for="instPropertyPrice" class="form-label">Property Price</label>
                                            <input type="text" class="form-control comma" id="instPropertyPrice"
                                                name="instPropertyPrice" aria-describedby="instPropertyPrice" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label d-flex justify-content-between">
                                                <span>Advance Payment</span>
                                                <span>Recievable Account</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control comma" id="advancePayment"
                                                    name="advancePayment" aria-describedby="advancePayment" />
                                                <select class="form-select" name="instBankAccount" id="instBankAccount">
                                                    <option value="cash" selected>Cash</option>
                                                    <?php if (!empty($banks)) { 
                                                    foreach ($banks as $key => $account) { ?>
                                                    <option value="<?=$account['acc_id']?>"><?=$account['name']?>,
                                                        <?=$account['number']?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="instRemarks" class="form-label">Remarks</label>
                                            <input type="text" class="form-control" id="instRemarks"
                                                name="instRemarks" aria-describedby="instRemarks" />
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="mb-3">
                                            <label for="instBalance" class="form-label">Balance</label>
                                            <input type="text" class="form-control bg-white" id="instBalance"
                                                name="instBalance" aria-describedby="instBalance" readonly />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="totalInstallments" class="form-label">Total Installments</label>
                                            <input type="number" class="form-control" id="totalInstallments"
                                                name="totalInstallments" aria-describedby="totalInstallments" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="instDate" class="form-label">
                                                Select Installment Date
                                                <svg class="icon icon-xs ms-2" data-bs-toggle="tooltip"
                                                    data-bs-original-title="Change Installment Date If you want ..."
                                                    fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </label>
                                            <input type="date" class="form-control" id="instDate" name="instDate"
                                                aria-describedby="instDate" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 net_cashSection" style="display: none;">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <div class="mb-3">
                                            <label for="propertyPrice" class="form-label">Property Price</label>
                                            <input type="text" class="form-control comma" id="netCashPropertyPrice"
                                                name="netCashPropertyPrice" aria-describedby="propertyPrice" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="netAmount" class="form-label">Net Amount</label>
                                            <input type="text" class="form-control comma" id="netAmount"
                                                name="netAmount" aria-describedby="netAmount" />
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label for="netCashBankAccount" class="form-label">Account</label>
                                            <select class="form-select" name="netCashBankAccount"
                                                id="netCashBankAccount">
                                                <option value="" selected>Select Account</option>
                                                <option value="cash">Cash</option>
                                                <?php if (!empty($banks)) { 
                                                    foreach ($banks as $key => $account) { ?>
                                                <option value="<?=$account['acc_id']?>"><?=$account['name']?>,
                                                    <?=$account['number']?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="netCashRemarks" class="form-label">Remarks</label>
                                            <input type="text" class="form-control" id="netCashRemarks"
                                                name="netCashRemarks" aria-describedby="netCashRemarks" />
                                        </div>
                                    </div>
                                </div>
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
        const plots = <?=(!empty($plots))?json_encode($plots):[]?>;
        const flats = []<?php //(!empty($flats))?json_encode($flats):[]?>;

        // Current Date
        const currentDate = new Date().toISOString().split('T')[0];
        $('#date').val(currentDate);
        $('#instDate').val(currentDate);
        // Current Date

        // $('input[name="propertyType"]').change(function() {
        //     var selectedMethod = $(this).val();
        //     // Hide all sections
        //     $('.plotSection').hide();
        //     $('.flatSection').hide();

        //     // Show the selected section
        //     $('.' + selectedMethod + 'Section').show();
        //     $('.' + selectedMethod + 'Section select').val("");
        // });

        $('#paymentType').change(function() {
            var selectedMethod = $(this).val();
            // Hide all sections
            $('.installmentSection').hide();
            $('.net_cashSection').hide();

            // Show the selected section
            $('.' + selectedMethod + 'Section').show();
            $('.' + selectedMethod + 'Section input').val("");
        });

        property_price("plot");
        property_price("flat");

        function property_price(type) {
            $("#" + type).change(function() {
                if ($(this).val()) {
                    if (type == "plot") {
                        for (var key in plots) {
                            if (plots.hasOwnProperty(key)) {
                                if (plots[key].pty_id === $(this).val()) {
                                    if ($('#paymentType').val() == "installment") {
                                        $("#instPropertyPrice").val(parseInt(plots[key]['rate'])
                                            .toLocaleString());
                                    } else if ($('#paymentType').val() == "net_cash") {
                                        $("#netCashPropertyPrice").val(parseInt(plots[key]['rate'])
                                            .toLocaleString());
                                    }
                                    break;
                                }
                            }
                        }
                    } else if (type == "flat") {
                        for (var key in flats) {
                            if (flats.hasOwnProperty(key)) {
                                if (flats[key].pty_id === $(this).val()) {
                                    if ($('#paymentType').val() == "installment") {
                                        $("#instPropertyPrice").val(parseInt(flats[key]['rate'])
                                            .toLocaleString());
                                    } else if ($('#paymentType').val() == "net_cash") {
                                        $("#netCashPropertyPrice").val(parseInt(flats[key]['rate'])
                                            .toLocaleString());
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
            });
        }

        $("#advancePayment").on("keyup keydown", function() {
            if ($(this).val()) {
                setTimeout(() => {
                    tot_balance();
                }, 300);
            } else {
                $("#instBalance").val("0");
            }
        });

        function tot_balance() {
            // console.log($("#instPropertyPrice").val().replace(/,(?=\d{3})/g, ''));
            // console.log($("#advancePayment").val().replace(/,(?=\d{3})/g, ''));
            var balance = $("#instPropertyPrice").val().replace(/,(?=\d{3})/g, '') - $("#advancePayment").val()
                .replace(/,(?=\d{3})/g, '');
            $("#instBalance").val(balance.toLocaleString());
        }
    });
    </script>
</body>

</html>