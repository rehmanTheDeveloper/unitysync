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
$conn = conn("localhost", "root", "", "pine-valley");             #
####################### Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-account") != true) {
    header("Location: account.all.php?message=account_view_not_allow");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['id'])) {
    header("Location: account.all.php?message=not_found");
    exit();
}

$query = "SELECT `type` FROM `accounts` WHERE `acc_id` = '".$_GET['id']."' AND `project_id` = '".$_SESSION['project']."';";
$type = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `".$type['type']."` WHERE `acc_id` = '".$_GET['id']."' AND `project_id` = '".$_SESSION['project']."';";
$account = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `document` WHERE `acc_id` = '".$_GET['id']."' AND `project_id` = '".$_SESSION['project']."';";
$documents = fetch_Data($conn, $query);

// echo "<pre>";
// print_r($account);
// exit();

$title = "Account - ".$account['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
    <?php if (!empty($documents)) { ?>
    <script src="vendor/viewerjs/dist/js/viewer.min.js"></script>
    <link rel="stylesheet" href="vendor/viewerjs/dist/css/viewer.min.css" />
    <style>
    .selected-image {
        height: 500px;
    }

    .selected-image:hover {
        content: "View Image";
    }
    </style>
    <?php } ?>
</head>

<body>
    <?php include('temp/aside.temp.php'); ?>
    <main class="content">
        <?php include('temp/nav.temp.php'); ?>

        <div class="py-3">
            <nav aria-label="breadcrumb" class="d-none d-md-flex justify-content-between align-items-center">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
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
                        Account - <?=$account['name']?>
                    </li>
                </ol>
                <div class="btn-group">
                    <button class="btn btn-outline-gray-800 rounded-0 rounded-start dropdown-toggle"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z">
                            </path>
                        </svg>
                        Other Options
                        <svg class="icon icon-xs ms-1" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1" style="">
                        <?php if (!empty($documents)) { ?>
                        <a class="dropdown-item d-flex align-items-center justify-content-center" data-bs-toggle="modal"
                            data-bs-target="#documents">
                            Docs
                        </a>
                        <?php } ?>
                        <?php
                        ################################ Role Validation ################################
                        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-account") === true) {
                        ################################ Role Validation ################################
                        ?>
                        <a class="dropdown-item d-flex align-items-center justify-content-center fw-bold text-success"
                            href="account.edit.php?id=<?=$account['acc_id']?>">
                            Edit Account
                        </a>
                        <?php } ?>
                        <?php
                        ################################ Role Validation ################################
                        if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-account") === true) {
                        ################################ Role Validation ################################
                        ?>
                        <div role="separator" class="dropdown-divider my-1"></div>
                        <a class="dropdown-item d-flex align-items-center justify-content-center fw-bold text-danger deleteBtn"
                            data-id="<?=$account['acc_id']?>" data-name="<?=$account['name']?>">
                            Delete Account
                        </a>
                        <?php } ?>
                    </div>
                    <a class="btn btn-outline-gray-800" href="account.all.php">Manage Accounts</a>
                </div>
            </nav>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center mb-3"><?=$account['name']?></h2>
                            </div>
                            <div class="col-4 d-flex justify-content-center align-items-center">
                                <?php if (!empty($account['img']) && file_exists("uploads/acc-profiles/".$account['img'])) { ?>
                                <img src="uploads/acc-profiles/<?=$account['img']?>" alt="" srcset=""
                                    class="img-fluid rounded rounded-circle" />
                                <?php } else { ?>
                                <img src="uploads/profiles/profile.png" alt="" srcset=""
                                    class="img-fluid rounded rounded-circle" />
                                <?php } ?>
                            </div>
                            <div class="col-8">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Account</th>
                                            <th class="border-0 rounded-end text-end">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bolder">
                                        <tr>
                                            <td>Type</td>
                                            <td class="text-end text-capitalize"><?=$type['type']?></td>
                                        </tr>
                                        <tr>
                                            <td>CNIC</td>
                                            <td class="text-end"><?=cnic_format($account['cnic'])?></td>
                                        </tr>
                                        <tr>
                                            <td>Phone No.</td>
                                            <td class="text-end"><?="+92 ".phone_no_format($account['phone_no'])?></td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td class="text-end"><?=$account['address']?></td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount</td>
                                            <td class="text-end">Rs. 34,000</td>
                                        </tr>
                                        <tr>
                                            <td>Paid Amount</td>
                                            <td class="text-success text-end">Rs. 12,000</td>
                                        </tr>
                                        <tr>
                                            <td>Pending Amount</td>
                                            <td class="text-danger text-end">Rs. 545,000</td>
                                        </tr>
                                        <?php if ($account['balance'] < 0) { ?>
                                        <tr>
                                            <td>Balance</td>
                                            <td class="text-end text-danger">
                                                <?=$arrow_down."Rs. ".number_format($account['balance'])?></td>
                                        </tr>
                                        <?php } else { ?>
                                        <tr>
                                            <td>Balance</td>
                                            <td class="text-end text-success">
                                                <?=$arrow_up."Rs. ".number_format($account['balance'])?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <?php if ($type['type'] == 'customer') { ?>
                <div class="card">
                    <div class="card-body">
                        <?php if (FALSE/*condition for property existance*/) { ?>
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h4>No Property</h4>
                                <a class="btn btn-outline-gray-600 my-3" href="sale.add.php">Purchase Property</a>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="row align-items-center">
                            <div class="col-lg-12">
                                <h2 class="mb-3">Property Details</h2>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-2">
                                    <select class="form-select" name="properties" id="properties">
                                        <option value="">Select Property</option>
                                        <option value="pt-321">Flat #321</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Property</th>
                                            <th class="border-0 rounded-end" style="text-align: right;">Detail
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold">
                                        <tr>
                                            <td>Type</td>
                                            <td style="text-align: right;">
                                                flat </td>
                                        </tr>
                                        <tr>
                                            <td>No.</td>
                                            <td style="text-align: right;">
                                                # 391 </td>
                                        </tr>
                                        <tr>
                                            <td>Total Insts</td>
                                            <td style="text-align: right;">
                                                24 </td>
                                        </tr>
                                        <tr>
                                            <td>Paid Insts.</td>
                                            <td style="text-align: right;">
                                                0 </td>
                                        </tr>
                                        <tr>
                                            <td>Price</td>
                                            <td style="text-align: right;">
                                                Rs. 3,300,000 </td>
                                        </tr>
                                        <tr>
                                            <td>Paid</td>
                                            <td class="text-success" style="text-align: right;">
                                                Rs. 459,500 </td>
                                        </tr>
                                        <tr>
                                            <td>Pending</td>
                                            <td class="text-danger" style="text-align: right;">
                                                Rs. 2,840,500 </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td class="text-danger" style="text-align: right;">
                                                Under Financing </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <?php if(!empty ($ledger) || TRUE) { ?>
        <div class="card my-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-centered table-nowrap mb-0 rounded" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0 rounded-start">#</th>
                                    <th class="border-0 text-center">V-ID</th>
                                    <th class="border-0 text-center">Type</th>
                                    <th class="border-0 text-center">Source</th>
                                    <th class="border-0">Remarks</th>
                                    <th class="border-0 text-end">Credit</th>
                                    <th class="border-0 text-end">Debit</th>
                                    <th class="border-0 text-end rounded-end">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold">
                                <tr>
                                    <td class="text-center" colspan="8">No Details ...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php if (!empty($documents)) { ?>
        <div class="modal fade" id="documents" tabindex="-1" aria-labelledby="documents" style="display: none;"
            aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h2 class="h5 modal-title mb-0">Documents</h2>
                    </div>
                    <div class="modal-body">
                        <div style="row-gap: 15px;" class="row" id="gallery">
                            <?php foreach ($documents as $key => $document) { 
                                $refined_document_name = explode(".",$document['name']);?>
                            <div class="col-2">
                                <?php if ($refined_document_name[1] == "pdf") { ?>
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <img data-bs-toggle="tooltip" class="img-fluid"
                                        data-bs-original-title="Download to View" style="max-height: 200px;"
                                        src="uploads/docs/pdf-icon.png" alt="" />
                                    <label class="d-flex align-items-center justify-content-between mt-2 w-75">
                                        <span data-bs-toggle="tooltip"
                                            data-bs-original-title="<?=$refined_document_name[0]?>"><?=substr($refined_document_name[0],0,12)." ..."?></span>
                                        <div class="btn-group">
                                            <a class="btn p-1" data-bs-toggle="tooltip"
                                                data-bs-original-title="Download PDF"
                                                href="uploads/docs/<?=$document['name']?>" download>
                                                <svg class="icon icon-xs text-teritary"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </label>
                                </div>
                                <?php } else { ?>
                                <div class="d-flex justify-content-between align-items-center flex-column h-100">
                                    <div class="document-thumbnail h-100 d-flex align-items-center w-100"
                                        style="background-image: url('uploads/docs/<?=$document['name']?>'); background-repeat: no-repeat; background-size: cover; background-position: center; border-radius: 50%;"
                                        data-bs-toggle="tooltip" data-bs-original-title="Click to View" id="thumbnail-<?=$key+1?>">
                                        <img  src="uploads/docs/<?=$document['name']?>"
                                            id="img-<?=$key+1?>" hidden />
                                    </div>
                                    <label class="d-flex align-items-center justify-content-between mt-2"
                                        for="img-<?=$key+1?>">
                                        <span data-bs-toggle="tooltip"
                                            data-bs-original-title="<?=$refined_document_name[0]?>"><?=substr($refined_document_name[0],0,12)." ..."?></span>
                                        <div class="dropdown">
                                            <button class="btn p-1 dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-start mt-2 py-1" style="">
                                                <a class="dropdown-item d-flex align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Download Image"
                                                    href="uploads/docs/<?=$document['name']?>" download>
                                                    <svg class="dropdown-icon text-primary me-2" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Download
                                                </a>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Edit" href="#">
                                                    <svg class="dropdown-icon text-warning me-2" fill="currentColor"
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
                                                <div role="separator" class="dropdown-divider my-1"></div>
                                                <a class="dropdown-item d-flex align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-original-title="Delete" href="#">
                                                    <svg class="dropdown-icon text-danger me-2" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Delete
                                                </a>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Done</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>
    <script>
    $(function() {
        <?php if (!empty($documents)) { ?>
        const gallery = document.getElementById('gallery');
        const viewer = new Viewer(gallery);

        // Add a click event handler to each thumbnail
        $('.document-thumbnail').on('click', function() {
            const thumbnailId = $(this).attr('id');
            const imgId = thumbnailId.replace('thumbnail-', 'img-');
            console.log(imgId);
            viewer.show(document.getElementById(imgId));
        });
        <?php } ?>
    });
    </script>
</body>

</html>