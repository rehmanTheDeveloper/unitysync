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
$conn = conn("localhost", "root", "", "communiSync");             #
####################### Database Connection #######################

$accounts = [];

$query = "SELECT * FROM `accounts` WHERE `project_id` = '".$_SESSION['project']."' ORDER BY `id` DESC;";
$allAcc = fetch_Data($conn, $query);

if (!empty($allAcc)) {
    foreach ($allAcc as $key => $Acc) {
        $query = "SELECT * FROM `".$Acc['type']."` WHERE `acc_id` = '".$Acc['acc_id']."' AND `project_id` = '".$_SESSION['project']."';";
        $accounts[$key] = mysqli_fetch_assoc(mysqli_query($conn, $query));
        $accounts[$key]['category'] = $Acc['type'];
    }
} else {
    $accounts = [];
}

// echo "<pre>";
// print_r($accounts);
// exit();

$title = "All Accounts";
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
                        <a href="#">Master Entry</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Accounts
                    </li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between w-100 flex-wrap align-items-center">
                <h1 class="h4 mb-0">All Accounts</h1>
                <div class="btn-group">
                    <?php
                    ################################ Role Validation ################################
                    if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") === true) {
                    ################################ Role Validation ################################
                    ?>
                    <a href="account.config.php" class="btn btn-outline-gray-800 d-inline-flex align-items-center">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Account
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php
        ################################ Role Validation ################################
        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-account") === true) {
        ################################ Role Validation ################################
        ?>
        <div class="card">
            <div class="table-responsive py-4">
                <table class="table table-flush table-hover" id="datatable">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0">#</th>
                            <th class="border-0">Acc. ID</th>
                            <th class="border-0">Name</th>
                            <th class="border-0 text-end">Account Group</th>
                            <th class="border-0 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($accounts)) {
                        foreach ($accounts as $key => $account) { ?>
                        <tr>
                            <td class="fw-bolder">
                                <?=$key+1?>
                            </td>
                            <td><?=$account['acc_id']?></td>
                            <td><?=$account['name']?></td>
                            <td class="text-end text-capitalize"><?=$account['category']?></td>
                            <td style="column-gap: 15px;" class="d-flex justify-content-end align-items-center">
                            <?php
                            ################################ Role Validation ################################
                            if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-account, edit-account, delete-account") === true) {
                            ################################ Role Validation ################################
                            ?>
                                <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                                        </path>
                                    </svg>
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1" style="">
                                    <?php
                                    ################################ Role Validation ################################
                                    if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-account") === true) {
                                    ################################ Role Validation ################################
                                    ?>
                                    <a class="dropdown-item d-flex align-items-center" href="account.edit.php">
                                        <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                            </path>
                                            <path fill-rule="evenodd"
                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <?php } ?>
                                    <a class="dropdown-item d-flex align-items-center" href="account.view.php?id=<?=$account['acc_id']?>">
                                        <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        View
                                    </a>
                                </div>
                                <?php
                                ################################ Role Validation ################################
                                if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-account") === true) {
                                ################################ Role Validation ################################
                                ?>
                                <div class="btn-group">
                                    <button class="btn btn-link dropdown-toggle dropdown-toggle-split m-0 p-0"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        title="Delete">
                                        <svg class="icon icon-xs text-danger" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                        style="">
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <svg class="icon icon-xs dropdown-icon text-success me-2"
                                                fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Yes
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" data-bs-dismiss="dropdown">
                                            <svg class="icon icon-xs dropdown-icon text-danger me-2" fill="currentColor"
                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <i class="fa-solid fa-circle-xmark fs-5"></i>
                                            No
                                        </a>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td class="text-center border-bottom" colspan="5">No Account Created ...</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include('temp/footer.temp.php'); ?>
        <?php } else { ?>
        <div style="height: 80vh;" class="position-relative">
            <img id="accessDenied" class="position-absolute start-0 end-0 top-0 bottom-0 m-auto"
                src="assets/img/access-blocked.png" alt="" height="460" />
        </div>
        <?php } ?>
    </main>

    <?php include('temp/script.temp.php'); ?>
    <script>
    <?php if (isset($_GET['message'])) { ?>
        <?php if ($_GET['message'] == 'add_true') { ?>
            notify("success", "Account Created Successfully ...");
        <?php } elseif ($_GET['message'] == 'add_false') { ?>
            notify("error", "Something's Wrong, Report Error ...");
        <?php } elseif ($_GET['message'] == 'account_add_not_allow') { ?>
            notify("error", "You are currently not Allowed to Add Account ...");
        <?php } elseif ($_GET['message'] == 'account_view_not_allow') { ?>
            notify("error", "You are currently not Allowed to Add Account ...");
        <?php } elseif ($_GET['message'] == 'not_found') { ?>
            notify("error", "No Account Found ...");
        <?php } ?>
    <?php } ?>
    </script>
</body>

</html>