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
$conn = conn("localhost", "root", "", "unitySync");             #
####################### Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: account.all.php?m=account_add_not_allow");
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

$query = "SELECT `id`,`name`,`street`, @delete := '1' AS `delete` FROM `blocks`, (SELECT @delete := 0) AS vars WHERE `project_id` = '".$_SESSION['project']."';";
$blocks = fetch_Data($conn, $query);

// $query = "SELECT `block` FROM `plot` WHERE `project_id` = '".$_SESSION['project']."'";
// $plot_property = fetch_Data($conn, $query);

// if (!empty($plot_property)) {
//     foreach ($plot_property as $property) {
//         foreach ($blocks as $block_key => $block) {
//             if ($property['block'] == $block['id']) {
//                 $blocks[$block_key]['delete'] = 0;
//             }
//         }
//     }
// }

// $query = "SELECT `block` FROM `flat` WHERE `project_id` = '".$_SESSION['project']."'";
// $flat_property = fetch_Data($conn, $query);

// if (!empty($flat_property)) {
//     foreach ($flat_property as $account) {
//         foreach ($blocks as $block_key => $block) {
//             if ($account['block'] == $block['id']) {
//                 $blocks[$block_key]['delete'] = 0;
//             }
//         }
//     }
// }

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
                                <h4 class="text-center mb-0">Blocks</h4>
                                <a data-bs-toggle="modal" data-bs-target="#addBlock"
                                    class="btn btn-outline-primary position-absolute end-0 top-0">
                                    Add
                                </a>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <table class="table table-centered table-nowrap mb-0 rounded">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0 rounded-start">Block</th>
                                                <th class="border-0 rounded-end text-end">Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-bolder">
                                            <tr>
                                                <td class="text-center" colspan="2">
                                                    <span data-bs-toggle="tooltip">Dummy</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" colspan="2">No Block Added ...</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addBlock" tabindex="-1" role="dialog" aria-labelledby="Add Block"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="modal">
                <form method="POST" action="comp/surcharge.add.php" class="modal-content">
                    <div class="modal-header">
                        <h2 class="h6 modal-title">Add Block</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="block" class="form-label d-flex justify-content-between">
                                        <span>Block</span><span>Street</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="block" id="block" class="form-control" placeholder="A, etc." required />
                                        <span class="input-group-text bg-gray-100">-</span>
                                        <input type="text" name="street" id="street" class="form-control text-end" placeholder="23, etc." required />
                                    </div>
                                    <input type="hidden" name="type" value="addBlock" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Add</button>
                    </div>
                </form>
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
        <?php } ?>
        <?php } ?>
    });
    </script>
</body>

</html>