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

$title = "Add Transfer";

$properties = array(
    array(
        "plot_no" => "219",
        "marla" => "5",
        "amount" => "6700000",
        "paid_amount" => "4000000",
        "remain_amount" => "2700000",
        "id" => "pt-2",
        "acc_id" => 'ac-13'
    ),
    array(
        "plot_no" => "178",
        "marla" => "10",
        "amount" => "13400000",
        "paid_amount" => "6000000",
        "remain_amount" => "7400000",
        "id" => "pt-8",
        "acc_id" => 'ac-13'
    ),
    array(
        "plot_no" => "111",
        "marla" => "5",
        "amount" => "6700000",
        "paid_amount" => "2500000",
        "remain_amount" => "4200000",
        "id" => "pt-4",
        "acc_id" => 'ac-12'
    )
);

$customers = array(
    array(
        "name" => "Abdul Rehman",
        "cnic" => "36402-5685654-2",
        "id" => "ac-12"
    ),
    array(
        "name" => "Ali Abdullah",
        "cnic" => "36402-7954321-2",
        "id" => "ac-13"
    )
);

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
                        Add Transfer
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Add Transfer</h1>
                <div>
                    <a href="transfer.all.php" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                        All Transfers
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow mb-4">
                            <div class="card-body">
                                <h2 class="h4 text-center mb-4">Transfer From</h2>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="trfFromCustomer">Select Customer</label>
                                            <select class="form-select" name="trfFromCustomer" id="trfFromCustomer">
                                                <option value="" selected>Select Customer</option>
                                                <?php for ($i = 0; $i < count($customers); $i++) { ?>
                                                <option value="<?php echo $customers[$i]['id'] ?>">
                                                    <?php echo $customers[$i]['name']. ", ". $customers[$i]['cnic']; ?>
                                                </option>
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
                                                    <label for="trfFromProperty">Select Property</label>
                                                    <select class="form-select bg-white" name="trfFromProperty"
                                                        id="trfFromProperty" disabled>
                                                        <option value="" selected>No Property</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 py-2 d-none">
                                                <label for="">Balance Sheet</label>
                                                <div class="card shadow-sm rounded-5">
                                                    <table class="table table-centered table-nowrap mb-0 rounded">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="border-0 rounded-start">Detail</th>
                                                                <th class="border-0 rounded-end"
                                                                    style="text-align: right;">
                                                                    Amount
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="balanceSheet"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow mb-4">
                            <div class="card-body">
                                <h2 class="h4 text-center mb-4">Transfer To</h2>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="trfToCustomer">Select Customer</label>
                                            <select class="form-select" name="trfToCustomer" id="trfToCustomer">
                                                <option value="" selected>Select Customer</option>
                                                <?php for ($i = 0; $i < count($customers); $i++) { ?>
                                                <option value="<?php echo $customers[$i]['id'] ?>">
                                                    <?php echo $customers[$i]['name']. ", ". $customers[$i]['cnic']; ?>
                                                </option>
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
                                                    <label for="trfCharges">Transfer Charges</label>
                                                    <input type="number" name="trfCharges" id="trfCharges"
                                                        class="form-control" placeholder="15,000" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card card-body border-0 shadow mb-4">
                            <h2 class="h4 text-center">Attach Document</h2>
                            <div class="card p-3 mb-2">
                                <div class="d-flex align-items-center justify-content-center">
                                    <input type="file" name="documentUpload" id="docUpload" multiple hidden />
                                    <label class="btn btn-outline-gray-600" for="docUpload">Upload</label>
                                </div>
                                <table class="table border-0">
                                    <tbody id="docName">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="mb-2">
                            <label for="remarks">Remarks</label>
                            <textarea class="form-control" placeholder="Enter Remarks ..." name="remarks"
                                rows="4"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <input class="btn btn-outline-gray-600 my-2" type="submit" name="submit" value="Submit" />
                        <a href=""></a>
                    </div>
                </div>
            </div>
        </div>

        <?php require('temp/footer.temp.php'); ?>
    </main>

    <?php require('temp/script.temp.php'); ?>
    <script>
    $('#trfFromCustomer').on("change", function() {
        var customers = <?php echo json_encode($customers); ?>,
            properties = <?php echo json_encode($properties); ?>,
            row = '';
        if ($(this).val() != '') {
            for (let i = 0; i < customers.length; i++) {
                if ($(this).val() === customers[i]['id']) {
                    $('#trfFromName').val(customers[i]['name']);
                    $('#trfFromCnic').val(customers[i]['cnic']);
                    $('#trfFromId').val(customers[i]['id']);
                    for (let x = 0; x < properties.length; x++) {
                        if ($(this).val() == properties[x]['acc_id']) {
                            $('#trfFromProperty').text("");
                            $('#trfFromProperty').removeAttr('disabled');
                            row += '<option value="' + properties[x]['id'] + '">' + properties[x]['plot_no'] +
                                ', ' + properties[x]['marla'] + ' Marla' +
                                '</option>';
                            $('#trfFromProperty').append(row);
                        }
                    }
                    $('#trfFromProperty').trigger("change");
                }
            }
        } else {
            $('#trfFromName').val("");
            $('#trfFromCnic').val("");
            $('#trfFromId').val("");
            $('#trfFromProperty').text("");
            row = '<option value="" selected>' + "No Property" + '</option>';
            $('#trfFromProperty').append(row);
            $('#trfFromProperty').attr('disabled', 'disabled');
            $('#balanceSheet').empty();
            $('#balanceSheet').parent().parent().parent().addClass('d-none');
        }
    });

    $('#trfFromProperty').on("change", function() {
        var properties = <?php echo json_encode($properties); ?>,
            row = '';
        for (let i = 0; i < properties.length; i++) {
            if ($(this).val() == properties[i]['id']) {
                $('#balanceSheet').parent().parent().parent().removeClass('d-none');
                bal_sheet_labels = [
                    ["", "Property Price", properties[i]['amount']],
                    ["", "Paid", properties[i]['paid_amount']],
                    ["text-danger", "R. Balance", properties[i]['remain_amount']]
                ];
                for (let x = 0; x < bal_sheet_labels.length; x++) {
                    $('#balanceSheet').empty();
                    row += '<tr> <td class="' + bal_sheet_labels[x][0] + '" >' + bal_sheet_labels[x][1] +
                        '</td>' +
                        '<td style="text-align: right;" >' + bal_sheet_labels[x][2] + '</td></tr>';
                    $('#balanceSheet').append(row);
                }
                $('#propertyId').val(properties[i]['id']);
            }
        }
    });

    $('#trfToCustomer').on("change", function() {
        var customers = <?php echo json_encode($customers); ?>;
        var properties = <?php echo json_encode($properties); ?>;
        if ($(this).val() != '') {
            for (let i = 0; i < customers.length; i++) {
                if ($(this).val() === customers[i]['id']) {
                    $('#trfToName').val(customers[i]['name']);
                    $('#trfToCnic').val(customers[i]['cnic']);
                    $('#trfToId').val(customers[i]['id']);
                }
            }
        } else {
            $('#trfToName').val("");
            $('#trfToCnic').val("");
            $('#trfToId').val("");
        }
    });
    </script>
</body>

</html>