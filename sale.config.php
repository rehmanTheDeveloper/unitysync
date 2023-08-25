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
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="saleCustomer">Select Customer</label>
                                            <select class="form-select" name="saleCustomer" id="saleCustomer">
                                                <option value="" selected>Select Customer</option>
                                                <option value="">Abdul Rehman, 36402-4596230-1</option>
                                                <option value="">Rao Aleem, 36402-4596230-1</option>
                                                <option value="">Ali Abdullah, 36402-4596230-1</option>
                                            </select>
                                            <!-- <select class="form-select bg-white" disabled>
                                                <option value="" selected>No Customer has been Added</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="property">Select Property</label>
                                            <select class="form-select" name="property" id="property">
                                                <option value="" selected>Select Property</option>
                                                <option value=""># 23, 5 Marla</option>
                                                <option value=""># 24, 10 Marla</option>
                                                <option value=""># 25, 15 Marla</option>
                                            </select>
                                            <!-- <select class="form-select bg-white" disabled>
                                                <option value="" selected>No Property has been Added</option>
                                            </select> -->
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="salePaymentType" class="form-label">Payment Type</label>
                                            <select class="form-select" name="salePaymentType" id="salePaymentType">
                                                <option value="credit">Credit</option>
                                                <option value="debit">Debit</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 methodCredit d-none">
                                        <div class="mb-3">
                                            <label for="propertyPrice" class="form-label">Property Price</label>
                                            <input type="text" class="form-control bg-white" id="propertyPrice"
                                                name="propertyPrice" aria-describedby="propertyPrice" readonly />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 methodCredit d-none">
                                        <div class="mb-3">
                                            <label for="advancePayment" class="form-label">Advance Payment</label>
                                            <input type="text" class="form-control" id="advancePayment"
                                                name="advancePayment" aria-describedby="advancePayment" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 methodCredit d-none saleBalance">
                                        <div class="mb-3">
                                            <label for="saleBalance" class="form-label">Balance</label>
                                            <input type="text" class="form-control bg-white" id="saleBalance"
                                                name="saleBalance" aria-describedby="saleBalance" />
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="row">
                                            <div class="col-lg-12 methodCredit d-none">
                                                <div class="mb-3">
                                                    <label for="saleinsts" class="form-label">Total Installments</label>
                                                    <input type="number" class="form-control" id="saleinsts"
                                                        name="saleinsts" aria-describedby="saleinsts" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 methodCredit d-none">
                                                <div class="mb-3">
                                                    <label for="saleinstdate" class="form-label">Select Installment
                                                        Date</label>
                                                    <input type="date" class="form-control" id="saleinstdate"
                                                        name="saleinstdate" aria-describedby="saleinstdate" />
                                                </div>
                                            </div>
                                            <div class="col-lg-12 methodCredit d-none">
                                                <div class="h-100 d-flex justify-content-center align-items-center">
                                                    <a class="btn btn-outline-gray-600"
                                                        id="inst_button">Installments</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-8 d-none installmentTable">
                                        <div class="card border-0 shadow mb-4">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-centered table-nowrap mb-0 rounded"
                                                        id="installmentsTable">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th class="border-0 rounded-start">#</th>
                                                                <th class="border-0">Installment Date</th>
                                                                <th class="border-0 rounded-end text-end">
                                                                    Amount
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="installmentTable"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none" id="allInstallments"></div>
                            </div>
                            <div class="col-lg-12 text-center submit d-none">
                                <input type="hidden" name="pt_id" id="pt_id" />
                                <input type="hidden" name="accId" id="accId" />
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
    var dummyTable = d.getElementById('installmentsTable');

    $(function() {
        $('#salePaymentType').trigger("change");
    });

    $("#inst_button").on("click", function() {
        var $dateInput = $("#saleinstdate");
        var pt_price = parseInt($('#saleBalance').val());
        var tot_installments = parseInt($('#saleinsts').val());
        var installmentAmount = (pt_price / tot_installments).toFixed(0);
        var table = $('.installmentTable');

        if ($dateInput.val() != '' || tot_installments != 0) {
            var startDate = new Date($dateInput.val());
            var $tableContainer = $("#installmentTable");
            var allInsts = $('#allInstallments');
            $tableContainer.empty();

            if (tot_installments <= 60) {
                table.removeClass('d-none');
                for (let i = 0; i < tot_installments; i++) {
                    const installmentDate = new Date(startDate.getFullYear(), startDate.getMonth() + i,
                        startDate.getDate());
                    $tableContainer.append(`<tr>
                    <td><a href="#" class="text-primary fw-bold">${i + 1}</a></td>
                    <td>${installmentDate.toDateString()}</td>
                    <td style="text-align: right;">Rs. ${installmentAmount}</td>
                </tr>`);
                    $('#allInstallments').append(`<input type="hidden" name="installmentDate${i + 1}" value="${installmentDate.toDateString()}" />
                    <input type="hidden" name="installmentAmount${i + 1}" value="${installmentAmount}" />`);
                }
                if (dummyTable) {
                    const dataTable = new simpleDatatables.DataTable(dummyTable, {
                        searchable: false,
                        // fixedHeight: true
                    });
                }
            } else {
                notify('error', 'Total Installments cannot be greater than 60');
                $('#saleinsts').val("");
                $("#saleinstdate").val("");
            }
        } else {
            table.addClass('d-none');
        }
    });


    $('#salePaymentType').on("change", function() {
        let type = $(this).val();
        let credit = $(".methodCredit");
        $(".submit").removeClass("d-none");
        if (type != "") {
            if (type == "credit") {
                credit.removeClass("d-none");
                $('#saleBalance').attr('readonly', 'readonly');
            } else if (type == "debit") {
                credit.addClass("d-none");
                $(".saleBalance").removeClass("d-none");
                $('#propertyPrice').val('');
                $('#saleBalance').val('');
                $('#saleBalance').removeAttr('readonly');
            }
        } else {
            $(".submit").addClass("d-none");
            credit.addClass("d-none");
        }
    });
    </script>
</body>

</html>