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
$conn = conn("localhost", "root", "", "unitySync"); #
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
######################## Database Connection #######################

require "object/ledger.php";
$ledger_obj = new Ledger($PDO_conn);

$query = "SELECT * FROM `sales` WHERE `project_id` = '".$_SESSION['project']."';";
$all_sales = fetch_Data($conn, $query);

$query = "SELECT `name`,`acc_id`,`phone_no` FROM `customer` WHERE `project_id` = '".$_SESSION['project']."';";
$customers = fetch_Data($conn, $query);

if (!empty($all_sales)) {
    foreach ($all_sales as $key => $sale) {
        $query = "SELECT * FROM `sale_".$sale['type']."` WHERE `sale_id` = '".$sale['sale_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $sales[$key] = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $sales[$key]['sale_type'] = $sale['type'];
    }
    foreach ($sales as $key => $sale) {
        $query = "SELECT `type` FROM `properties` WHERE `pty_id` = '".$sale['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $property_type = mysqli_fetch_assoc(mysqli_query($conn, $query));

        $query = "SELECT `number`,`block`,`number`,`sqft`,`category` FROM `".$property_type['type']."` WHERE `pty_id` = '".$sale['pty_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $property = mysqli_fetch_assoc(mysqli_query($conn, $query));

        $query = "SELECT `name`,`street` FROM `blocks` WHERE `id` = '".$property['block']."' AND `project_id` = '".$_SESSION['project']."';";
        $block = mysqli_fetch_assoc(mysqli_query($conn, $query));

        $sales[$key]['property'] = $property['number'];
        $sales[$key]['property_type'] = $property_type['type'];
        $sales[$key]['property_sqft'] = $property['sqft'];
        $sales[$key]['property_category'] = $property['category'];
        $sales[$key]['property_block'] = $block['name'];
        $sales[$key]['property_street'] = $block['street'];
        $sales[$key]['property_status'] = 0;

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
        if (($sale['debit'] - $sale['credit']) != 0) {
            $sales[$key]['property_status'] = 2;
        } elseif (($sale['debit'] - $sale['credit']) == 0) {
            $sales[$key]['property_status'] = 1;
        }
        // print_r($property);
    }
} else {
    $sales = [];
}

$query = "SELECT `sqft_per_marla` FROM `project` WHERE `pro_id` = '".$_SESSION['project']."';";
$project_details = mysqli_fetch_assoc(mysqli_query($conn, $query));

// echo "<pre>";
// print_r($all_sales);
// print_r($sales);
// print_r($customers);
// exit();

$title = "All Sales";
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
                        All Sales
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">All Sales</h1>
                <div class="btn-group">
                    <a href="sale.config.php" class="btn btn-outline-gray-800 d-inline-flex align-items-center">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Sale
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
                            <th class="border-0 text-center">Sale Id</th>
                            <th class="border-0 text-center">Sale Type</th>
                            <th class="border-0 text-center">Purchaser</th>
                            <th class="border-0 text-center">Phone No.</th>
                            <th class="border-0 text-center">Property</th>
                            <th class="border-0 text-center">Area</th>
                            <th class="border-0 text-center">Status</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($sales)) {
                        foreach ($sales as $key => $sale) { ?>
                        <tr>
                            <td class="fw-bolder">
                                <?=$key+1?>
                            </td>
                            <td class="text-center"><?=$sale['sale_id']?></td>
                            <td class="text-center text-capitalize">
                                <?=($sale['sale_type'] == "net_cash")?"net cash":"installment"?></td>
                            <td class="text-center">
                                <?php 
                                    foreach ($customers as $customer) {
                                        if ($customer['acc_id'] == $sale['acc_id']) {
                                            echo $customer['name'];
                                        }
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    foreach ($customers as $customer) {
                                        if ($customer['acc_id'] == $sale['acc_id']) {
                                            echo "+92 ".phone_no_format($customer['phone_no']);
                                        }
                                    }
                                ?>
                            </td>
                            <td class="text-center text-capitalize"><?=$sale['property_type']?> #<?=$sale['property']?>
                                / <?=$sale['property_block']?>-<?=$sale['property_street']?></td>
                            <td class="text-center">
                                <?=floor($sale['property_sqft'] / $project_details['sqft_per_marla'])?> marla -
                                <?=number_format(($sale['property_sqft'] - floor($sale['property_sqft'] / $project_details['sqft_per_marla']) * $project_details['sqft_per_marla']))?>
                                Sqft.</td>
                            <td class="text-capitalize text-center fw-bold">
                                <?php if ($sale['property_status'] == 0) { ?>
                                <span class="badge bg-danger">Unsold</span>
                                <?php } elseif ($sale['property_status'] == 1) { ?>
                                <span class="badge bg-success">Sold</span>
                                <?php } elseif ($sale['property_status'] == 2) { ?>
                                <span class="badge bg-warning">Under Financing</span>
                                <?php } ?>
                            </td>
                            <td style="column-gap: 15px;" class="d-flex justify-content-end align-items-center">
                                <a class="btn p-0" href="#" data-bs-toggle="tooltip" data-bs-original-title="Select Flat to Generate Sale ...">
                                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z"
                                            clip-rule="evenodd"></path>
                                        <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        <?php } } else { ?>
                        <tr>
                            <td class="text-center fw-bold" colspan="9">No Sale Generated ...</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>

    <div class="modal fade" id="deleteSale" tabindex="-1" role="dialog" aria-labelledby="delete sale"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">Delete Sale</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete Sale ( <span id="selectedSale"></span> ) ?</p>
                    <input type="hidden" id="saleID" value="" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">No</button>
                    <button type="button" id="confirmDeleteBtn" data-target="#confirmationCode"
                        class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmationCode" tabindex="-1" role="dialog" aria-labelledby="delete user"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="h6 modal-title">Confirmation Code</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please enter the sum of the following numbers:</p>
                    <p><span class="number1"></span> + <span class="number2"></span> =</p>
                    <div class="mb-3">
                        <input class="form-control" type="text" id="confirmation-input" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="confirmCodeAns" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('temp/script.temp.php'); ?>
    <script>
    <?php if (isset($_GET['m'])) { ?>
    <?php if ($_GET['m'] == 'add_true') { ?>
    notify("success", "Sale Generated Successfully ...");
    <?php } elseif ($_GET['m'] == 'add_false') { ?>
    notify("error", "Something's Wrong, Report Error ...");
    <?php } ?>
    <?php } ?>

    $(function() {
        // Randomly generate two numbers
        var number1 = Math.floor(Math.random() * 10) + 1;
        var number2 = Math.floor(Math.random() * 10) + 1;
        $('.number1').text(number1);
        $('.number2').text(number2);

        // Show confirmation dialog on delete button click
        $('.deleteBtn').click(function() {
            var saleId = $(this).data('id');
            var userName = $(this).data('name');

            // Set user name in delete confirmation modal
            $('#selectedSale').text(userName);
            $('#saleID').val(saleId);

            console.log(userName);
            console.log(saleId);

            $('#deleteSale').modal('show');
        });

        // Show confirmation code dialog on confirm delete button click
        $('#confirmDeleteBtn').click(function() {
            $('#deleteSale').modal('hide');
            $('#confirmationCode').modal('show');
        });

        // Handle confirmation code submission
        $('#confirmCodeAns').click(function() {
            // Get user input and calculate expected result
            var input = $('#confirmation-input').val();
            var expected = number1 + number2;

            // Check if input is correct
            if (input == expected) {
                var saleId = $('#saleID').val();
                window.location.href = 'comp/sale.delete.php?i=' + saleId;
            } else {
                // Show error message and generate new numbers
                notify("error", "The sum is incorrect. Please try again.");
                number1 = Math.floor(Math.random() * 10) + 1;
                number2 = Math.floor(Math.random() * 10) + 1;
                $('.number1').text(number1);
                $('.number2').text(number2);
                $('#confirmation-input').val('');
            }
        });
    });
    </script>
</body>

</html>