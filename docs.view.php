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

if (empty($_GET['i'])) {
    header("Location: Accounts?m=not_found");
    exit();
}

$query = "SELECT `id`,`type` FROM `accounts` WHERE `acc_id` = '" . encryptor("decrypt", $_GET['i']) . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (empty($result)) {
    header("Location: Accounts?m=not_found");
    exit();
}

$query = "SELECT * FROM `".$result['type']."` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$account = mysqli_fetch_assoc(mysqli_query($conn, $query));

$query = "SELECT * FROM `document` WHERE `acc_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$documents = fetch_Data($conn, $query);

// echo "<pre>";
// print_r($documents);
// exit();

$title = "Docs - ".$account['name'];

?>
<!DOCTYPE HTML>
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
                    <a data-bs-toggle="modal" data-bs-target="#uploadDoc" class="btn btn-outline-gray-800">
                        Upload Docs.
                    </a>
                    <a href="account.view.php?i=<?=$_GET['i']?>" class="btn btn-outline-gray-800">
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
                                            data-bs-original-title="<?=$refined_document_name[0]?>"><?=substr($document['name'],0,12)?></span>
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
                                <div style="min-height:250px; gap: 20px;"
                                    class="d-flex justify-content-center align-items-center flex-column">
                                    <img class="img-fluid" data-bs-toggle="tooltip"
                                        data-bs-original-title="Click to View" src="uploads/docs/<?=$document['name']?>"
                                        alt="" id="img-<?=$key+1?>" />
                                    <label class="d-flex align-items-center justify-content-between mt-2 w-100"
                                        for="img-<?=$key+1?>">
                                        <span data-bs-toggle="tooltip"
                                            data-bs-original-title="<?=$refined_document_name[0]?>"><?=(!empty(strstr(substr($document['name'],0,18), '-UNI', true)))?strstr(substr($document['name'],0,18), '-UNI', true):substr($document['name'],0,12)?></span>
                                        <div class="btn-group">
                                            <button
                                                class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-0"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z">
                                                    </path>
                                                </svg>
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1"
                                                style="">
                                                <a class="dropdown-item d-flex align-items-center text-success"
                                                    href="uploads/docs/<?=$document['name']?>" download>
                                                    <svg class="dropdown-icon me-2" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Download
                                                </a>
                                                <div role="separator" class="dropdown-divider my-1"></div>
                                                <a class="dropdown-item d-flex align-items-center text-danger deleteBtn"
                                                    data-id="<?=encryptor("encrypt", $document['id'])?>" data-name="<?=$document['name']?>">
                                                    <svg class="dropdown-icon me-2" fill="currentColor"
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
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="uploadDoc" tabindex="-1" aria-labelledby="uploadDoc" style="display: none;"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <form method="POST" enctype="multipart/form-data" action="comp/docs.upload.php" class="card">
                            <div class="modal-header">
                                <h2 class="h6 modal-title">Upload Document</h2>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-2">
                                            <label class="btn btn-outline-primary w-100" for="documentsDocs">Upload
                                                Document</label>
                                            <input class="form-control" type="file" name="docs[]" id="documentsDocs"
                                                multiple hidden />
                                            <input type="hidden" name="acc_id" value="<?=$_GET['i']?>" />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row d-none" id="documentsFileUploadProgress">
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="documentsPercent" disabled>
                                                    0%
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="documentsDataTransferred"
                                                    disabled>
                                                    Total / Loaded
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="documentsMbps" disabled>
                                                    0 Mbps
                                                </button>
                                            </div>
                                            <div class="col-3">
                                                <button class="btn btn-primary w-100" id="documentsTimeLeft" disabled>
                                                    Time Left
                                                </button>
                                            </div>
                                            <div class="col-12 pt-3">
                                                <div class="progress-wrapper">
                                                    <div class="progress progress-xl">
                                                        <div class="progress-bar documents-progress-bar bg-primary"
                                                            role="progressbar" style="width: 0%;" aria-valuenow="25"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 pt-2">
                                                <button class="btn btn-primary w-100" id="documentsCancel" disabled>
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-none" id="documentsFilesTable">
                                            <table class="table table-centered table-nowrap mb-0 rounded">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th class="border-0 rounded-start">#</th>
                                                        <th class="border-0 rounded-end text-end">Doc Name
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-bolder">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link text-gray-600 ms-auto"
                                    data-bs-dismiss="modal">Close
                                </button>
                                <button type="submit" class="btn btn-success text-white">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteDoc" tabindex="-1" role="dialog" aria-labelledby="delete document"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form method="POST" action="comp/docs.delete.php" class="modal-content">
                    <div class="modal-header">
                        <h2 class="h6 modal-title">Delete Document</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete Document ( <span id="selectedDocument"></span> ) ?</p>
                        <input type="hidden" id="doc_id" name="i" value="" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
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
    <?php if ($_GET['m'] == 'add_true') { ?>
    notify("success", "Documents uploaded Successfully ...");
    <?php } elseif ($_GET['m'] == 'add_false') { ?>
    notify("error", "Something's Wrong, Report Error ...");
    <?php } elseif ($_GET['m'] == 'delete_true') { ?>
    notify("success", "Document Deleted Successfully ...");
    <?php } elseif ($_GET['m'] == 'delete_false') { ?>
    notify("error", "Something's Wrong, Report Error ...");
    <?php } } ?>
        var fileUpload = $("#documentsDocs");

        fileUpload.change(function() {
            var all_file_names = "";
            var files = this.files;
            var errorMessages = [];
            var validFiles = [];
            var maxSize = 10000000; // in bytes

            var validFilesArray = Array.from(files).filter(file => {
                var fileName = file.name;
                var fileExtension = fileName.split('.').pop().toLowerCase();
                var fileSize = file.size;

                if (fileSize > maxSize) {
                    // Handle file size exceeding threshold
                    errorMessages.push("File size of '" + fileName + "' is larger than 10 MB.");
                    return false; // Exclude the file from the valid files array
                }

                if (fileExtension !== 'pdf' && fileExtension !== 'jpg' && fileExtension !==
                    'jpeg' &&
                    fileExtension !== 'png') {
                    // Handle invalid extensions
                    errorMessages.push("Invalid extension for file '" + fileName +
                        "'. Use only PDF, JPG, JPEG, or PNG.");
                    return false; // Exclude the file from the valid files array
                }

                return true; // File matches all conditions and is valid
            });

            var validFilesList = new DataTransfer();
            validFilesArray.forEach((file) => {
                validFilesList.items.add(file);
                $("#documentsFileUploadProgress").removeClass('d-none');
                uploadFile(file, 'documents');
                all_file_names += file['name'] + "-<?=$_SESSION['project']?>,";

            });
            var fileInput = $('#documentsDocs')[0]; // Get the DOM element from jQuery object
            fileInput.files = validFilesList.files;

            updateSelectedFilesTable(validFilesArray, 'documents');

            errorMessages.forEach(errorMessage => {
                notify("error", errorMessage);
            });
        });

        function updateSelectedFilesTable(validFiles, type) {
            var table = $('#' + type + 'FilesTable');
            var tableBody = $('#' + type + 'FilesTable tbody');

            // console.log(validFiles);
            table.removeClass('d-none').addClass('d-block');
            tableBody.empty();

            for (var i = 0; i < validFiles.length; i++) {
                var fileNameWithExt = validFiles[i]['name'];
                var fileNameWithoutExt = fileNameWithExt.split('.').slice(0, -1).join('.');
                var fileExt = fileNameWithExt.split('.').pop();

                tableBody.append('<tr><td>' + parseInt(i + 1) +
                    '</td><td class="text-end"><input class="border-0 text-end" type="text" name="file_names[]" value="' +
                    fileNameWithoutExt +
                    '" />.' + fileExt + '</td></tr>');
            }
            $("#" + type + "FileUploadProgress").addClass('d-none');
        }

        function uploadFile(file, type) {
            var formData = new FormData();
            formData.append('file', file);

            var xhr = new XMLHttpRequest();

            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    var percentComplete = ((e.loaded / e.total) * 100);

                    // e.loaded is in bytes, convert it to kb, mb, or gb
                    var mbTotal = Math.floor(e.total / (1024));
                    var mbLoaded = Math.floor(e.loaded / (1024));

                    // calculate data transfer per sec
                    var time = (new Date().getTime() - startTime) / 1000;
                    var bps = e.loaded / time;
                    var Mbps = Math.floor(bps / (1024 * 1024));

                    // calculate remaining time
                    var remTime = (e.total - e.loaded) / bps;
                    var seconds = Math.floor(remTime % 60);
                    var minutes = Math.floor(remTime / 60);

                    // give output
                    $('#' + type + 'DataTransferred').html(`${mbLoaded}/${mbTotal} KBs`);
                    $('#' + type + 'Mbps').html(`${Mbps} Mbps`);
                    $('#' + type + 'TimeLeft').html(`${minutes}:${seconds}s`);
                    $("#" + type + "Percent").html(Math.floor(percentComplete) + '%');
                    $("." + type + "-progress-bar").width(percentComplete + '%');

                    // cancel button only works when the file is uploading
                    if (percentComplete > 0 && percentComplete < 100) {
                        $('#' + type + 'Cancel').prop('disabled', false);
                    } else {
                        $('#' + type + 'Cancel').prop('disabled', true);
                    }
                }
            }, false);

            var startTime = new Date().getTime();

            xhr.open('POST', 'ajax/docs.upload.php', true);
            xhr.send(formData);

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    if (xhr.responseText == "valid") {
                        notify("success", "Document '" + file['name'] + "' Uploaded ...");
                    } else if (xhr.responseText == "invalid") {
                        notify("error", "Document '" + file['name'] + "' not Uploaded ...");
                    }
                    console.log('Response from server:', xhr.responseText);
                } else {
                    console.error('An error occurred:', xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error('An error occurred during the request.');
            };

            // for cancel file transfer
            $('#' + type + 'Cancel').on("click", () => {
                xhr.abort();
                $("#" + type + "Percent").html('Canceled');
                $("." + type + "-progress-bar").width('0%');
            });
        }

        $('.deleteBtn').click(function() {
            var docId = $(this).data('id');
            var docName = $(this).data('name');

            $('#doc_id').val(docId);
            $('#selectedDocument').text(docName);

            console.log(docName);
            console.log(docId);

            $('#deleteDoc').modal('show');
        });
    });
    const gallery = document.getElementById('gallery');
    const viewer = new Viewer(gallery);
    </script>
</body>

</html>