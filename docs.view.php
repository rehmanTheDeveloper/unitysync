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

if (empty($_GET['id'])) {
    header("Location: account.all.php?message=not_found");
    exit();
}

$query = "SELECT `id`,`type` FROM `accounts` WHERE `acc_id` = '" . $_GET['id'] . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));
if (empty($result)) {
    header("Location: account.all.php?message=not_found");
    exit();
}

$query = "SELECT * FROM `".$result['type']."` WHERE `acc_id` = '".$_GET['id']."' AND `project_id` = '".$_SESSION['project']."';";
$account = mysqli_fetch_assoc(mysqli_query($conn, $query));

// echo "<pre>";
// print_r($account);
// exit();

$query = "SELECT * FROM `document` WHERE `acc_id` = '".$_GET['id']."' AND `project_id` = '".$_SESSION['project']."';";
$documents = fetch_Data($conn, $query);

$title = "Docs - ".$account['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
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
                        Docs - <?=$account['name']?>
                    </li>
                </ol>
                <div class="btn-group">
                    <a id="back" class="btn btn-outline-gray-800">
                        Back
                    </a>
                </div>
            </nav>
        </div>

        <div style="row-gap: 20px;" class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-4">Gallery</h5>
                        <?php if (!empty($documents)) { ?>
                        <div style="row-gap: 15px;" class="row" id="gallery">
                            <?php foreach ($documents as $key => $document) { 
                                $refined_document_name = explode(".",$document['name']);?>
                            <div class="col-2">
                                <?php if ($refined_document_name[1] == "pdf") { ?>
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <img data-bs-toggle="tooltip" data-bs-original-title="Download to View"
                                        style="height: 200px;" src="uploads/docs/pdf-icon.png" alt="" />
                                    <label class="d-flex align-items-center justify-content-between mt-2 w-75">
                                        <span data-bs-toggle="tooltip"
                                            data-bs-original-title="<?=$refined_document_name[0]?>"><?=substr($document['name'],0,12)." ..."?></span>
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
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <img data-bs-toggle="tooltip" data-bs-original-title="Click to View"
                                        style="height: 200px;" src="uploads/docs/<?=$document['name']?>" alt=""
                                        id="img-<?=$key+1?>" />
                                    <label class="d-flex align-items-center justify-content-between mt-2 w-75"
                                        for="img-<?=$key+1?>">
                                        <span data-bs-toggle="tooltip"
                                            data-bs-original-title="Lorem ipsum dolor sit, amet consectetur adipisicing elit. Est, magni.">Img
                                            <?=$key+1?></span>
                                        <div class="btn-group">
                                            <button class="btn p-1" data-bs-toggle="tooltip"
                                                data-bs-original-title="Edit">
                                                <svg class="icon icon-xs text-teritary" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                    </path>
                                                    <path fill-rule="evenodd"
                                                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                            <button class="btn p-1" data-bs-toggle="tooltip"
                                                data-bs-original-title="Delete">
                                                <svg class="icon icon-xs text-danger" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="modal fade" id="viewDoc" tabindex="-1" aria-labelledby="viewDoc" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card">
                            <div class="modal-header">
                                <h2 class="h6 modal-title">Click Document to View</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 text-center" id="gallery">
                                        <img class="selected-image"
                                            src="https://wallpaperaccess.com/download/4k-phone-95166" alt=""
                                            srcset="" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link text-gray-600 ms-auto"
                                    data-bs-dismiss="modal">Close
                                </button>
                                <button class="btn btn-success text-white" data-bs-dismiss="modal">Okay</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <?php include('temp/footer.temp.php'); ?>
    </main>
    <?php include('temp/script.temp.php'); ?>
    <script>
    $(function() {
        $("#back").on("click", function() {
            window.history.back();
        });
    });
    const gallery = document.getElementById('gallery');
    const viewer = new Viewer(gallery);
    </script>
</body>

</html>