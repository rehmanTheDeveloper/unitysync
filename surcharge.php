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
$conn = conn("localhost", "root", "", "unitySync");             #
####################### Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: Accounts?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

$query = "SELECT `id`,`name`,`remarks`, @delete := '1' AS `delete` FROM `expense_sub_groups`, (SELECT @delete := 0) AS vars WHERE `project_id` = '".$_SESSION['project']."';";
$expense_sub_groups = fetch_Data($conn, $query);

$query = "SELECT `sub_group` FROM `expense` WHERE `project_id` = '".$_SESSION['project']."'";
$expense_accounts = fetch_Data($conn, $query);

if (!empty($expense_accounts)) {
    foreach ($expense_accounts as $account) {
        foreach ($expense_sub_groups as $group_key => $group) {
            if ($account['sub_group'] == $group['id']) {
                $expense_sub_groups[$group_key]['delete'] = 0;
            }
        }
    }
}

// echo "<pre>";
// print_r($expense_sub_groups);
// exit();

$title = "Surcharge";
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
                        <a href="#">Configuration</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Surcharge
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">Surcharge</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 position-relative pb-4 px-2">
                                <h4 class="text-center mb-0">Expense Sub Groups</h4>
                                <a data-bs-toggle="modal" data-bs-target="#addSubGroup"
                                    class="btn btn-outline-primary position-absolute end-0 top-0">
                                    Add
                                </a>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <table class="table table-centered table-nowrap mb-0 rounded">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0 rounded-start">Sub Group</th>
                                                <th class="border-0 rounded-end text-end">Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-bolder">
                                            <?php if (!empty($expense_sub_groups)) {
                                            foreach ($expense_sub_groups as $key => $group) { ?>
                                            <tr>
                                                <td>
                                                    <span data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?=$group['remarks']?>"><?=$group['name']?></span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group">
                                                        <button
                                                            class="btn btn-link dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false" title="Delete">
                                                            <svg class="icon icon-xs <?=($group['delete'] == 1)?"text-danger":""?>" fill="currentColor"
                                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="visually-hidden">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                                            style="">
                                                            <?php if ($group['delete'] == 1) { ?>
                                                            <a class="dropdown-item d-flex align-items-center deleteBtn"
                                                                href="comp/surcharge.delete.php?i=<?=encryptor("encrypt", $group['id'])?>&type=expenseSubGroup">
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
                                                                No
                                                            </a>
                                                            <?php } else { ?>
                                                            <a class="dropdown-item d-flex align-items-center text-gray-700"
                                                                data-bs-dismiss="dropdown">
                                                                <svg class="icon icon-xs dropdown-icon text-gray-400 me-2"
                                                                    fill="currentColor" viewBox="0 0 20 20"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                        clip-rule="evenodd"></path>
                                                                </svg>
                                                                Not Deletable ...
                                                            </a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php } } else { ?>
                                            <tr>
                                                <td class="text-center" colspan="2">No Sub Group Added ...</td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addSubGroup" tabindex="-1" role="dialog" aria-labelledby="Kin Details"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="modal">
                <form method="POST" action="comp/surcharge.add.php" class="modal-content">
                    <div class="modal-header">
                        <h2 class="h6 modal-title">Expense Sub Group</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="expenseSubGroup" class="form-label">Sub Group</label>
                                    <input type="text" name="expenseSubGroup" id="expenseSubGroup" class="form-control"
                                        required />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="expenseSubGroupRemarks" class="form-label">Remarks</label>
                                    <input type="text" name="expenseSubGroupRemarks" id="expenseSubGroupRemarks"
                                        class="form-control" />
                                    <input type="hidden" name="type" value="subGroup" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Add</button>
                    </div>
            </div>
        </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
    </main>

    <?php include('temp/script.temp.php'); ?>
    <script>
    $(function() {
        <?php if (isset($_GET['m'])) { ?>
        <?php if ($_GET['m'] == 'group_add_true') { ?>
        notify("success", "Expense Sub Group Created Successfully ...");
        <?php } elseif ($_GET['m'] == 'group_add_false') { ?>
        notify("error", "Something's Wrong, Report Error ...");
        <?php } elseif ($_GET['m'] == 'group_delete_true') { ?>
        notify("success", "Expense Sub Group Deleted Successfully ...");
        <?php } elseif ($_GET['m'] == 'group_delete_false') { ?>
        notify("error", "Something's Wrong, Report Error ...");
        <?php } elseif ($_GET['m'] == 'group_exist') { ?>
        notify("error", "Expense Sub Group Already Exist ...");
        <?php } ?>
        <?php } ?>
    });
    </script>
</body>

</html>