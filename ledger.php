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
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
####################### Database Connection #######################

$query = "SELECT * FROM `accounts` WHERE `project_id` = '".$_SESSION['project']."';";
$all_accounts = fetch_Data($conn, $query);

foreach ($all_accounts as $key => $Acc) {
    $query = "SELECT `name`, `acc_id` FROM `".$Acc['type']."` WHERE `acc_id` = '".$Acc['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
    $acc = mysqli_fetch_assoc(mysqli_query($conn, $query));

    $accounts[$key] = $acc;
    $accounts[$key]['type'] = $Acc['type'];
}

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
                        <div class="row">
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label class="form-label" for="account">Account</label>
                                    <select class="form-select" name="account" id="account">
                                        <option value="" selected>Select Account</option>
                                        <?php foreach ($accounts as $key => $account) { ?>
                                        <option value="<?=encryptor("encrypt", $account['acc_id'])?>">
                                            <?=$account['name']." - ".$account['type']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end pb-3">
                                <button class="btn btn-primary w-100" id="checkLedger">Check Ledger</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-centered table-hover table-nowrap mb-0 rounded" id="ledger">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 rounded-start">#</th>
                                    <th class="border-0 text-center">V-ID</th>
                                    <th class="border-0 text-center">Source</th>
                                    <th class="border-0">Remarks</th>
                                    <th class="border-0 text-end">Dr./Cr.</th>
                                    <th class="border-0 text-end rounded-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center fw-bold">No History ...</td>
                                </tr>
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
    var table = document.getElementById("ledger");
    var dataTable = null;
    var account = document.getElementById("account");

    document.getElementById('checkLedger').addEventListener('click', function() {
        var account_id = account.value;
        if (account_id) {
            account.disabled = true;
            table.querySelector('tbody').innerHTML =
                '<tr><td class="text-center" colspan="6"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ms-1">Loading...</span></td></tr>';
            $.ajax({
                url: 'ajax/ledger.php',
                method: 'POST',
                data: {
                    account: account_id
                },
                success: function(response) {
                    // console.log(response);
                    account.disabled = false;
                    if (dataTable) {
                        dataTable.destroy();
                    }
                    table.querySelector('tbody').innerHTML = '';
                    dataTable = new simpleDatatables.DataTable(table);

                    if (response === 'empty') {
                        if (table.querySelector("tbody")) {
                            table.querySelector("tbody").innerHTML = '<tr>' +
                                '<td class="text-center" colspan="6">No ledger history ...</td>' +
                                '</tr>';
                        }
                    } else {
                        table.querySelector('tbody').innerHTML = response;
                    }
                }
            });
        } else {
            if (table.querySelector("tbody")) {
                table.querySelector("tbody").innerHTML = '<tr>' +
                    '<td class="text-center" colspan="6">No ledger history ...</td>' + '</tr>';
            }
        }
    });
    </script>
</body>

</html>