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

// echo "<pre>";
// print_r($_SESSION);
// exit();

$query = "SELECT * FROM `roles` WHERE `project_id` = '".$_SESSION['project']."' AND `name` != 'super-admin';";
$roles = fetch_Data($conn, $query);

$query = "SELECT * FROM `users` WHERE `username` = '".$_SESSION['username']."' AND `project_id` = '".$_SESSION['project']."';";
$user = mysqli_fetch_assoc(mysqli_query($conn, $query));

$prefixes = array(
    "Mr.",
    "Mrs."
);
$genders = array(
    "male",
    "female",
    "not confirm"
);
$martial_status = array(
    "single",
    "married",
    "engaged"
);


$title = "Profile - ".$user['f_name'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
    <style type="text/css">
    .preview {
        overflow: hidden;
        width: 160px;
        height: 160px;
        margin: 10px;
        border: 1px solid red;
    }
    </style>
</head>

<body>

    <?php include('temp/aside.temp.php'); ?>
    <main class="content">

        <?php include('temp/nav.temp.php'); ?>
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-3">
            <nav aria-label="breadcrumb" class="d-none d-md-flex align-items-center">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent mb-0">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">My Profile</a></li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">General information</h2>
                    <form method="POST" action="comp/user.edit.php">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="prefix">Prefix</label>
                                    <select name="prefix" id="prefix" class="form-select">
                                        <?php foreach ($prefixes as $value) {
                                            if ($user['prefix'] == $value) { ?>
                                        <option value="<?=$value?>" selected><?=$value?></option>
                                        <?php } else { ?>
                                        <option value="<?=$value?>"><?=$value?></option>
                                        <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="mb-3">
                                    <label for="fName">First Name</label>
                                    <input class="form-control" id="fName" name="fName" type="text"
                                        value="<?=$user['f_name']?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="lName">Last Name</label>
                                    <input class="form-control" id="lName" name="lName" type="text"
                                        value="<?=$user['s_name']?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input class="form-control" id="email" name="email" type="text"
                                        value="<?=$user['email']?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phoneNo">Contact No.</label>
                                    <input class="form-control" id="phoneNo" name="phoneNo" type="text"
                                        value="<?=$user['phone_no']?>" />
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="gender">Gender</label>
                                    <select class="form-select" name="gender" id="gender">
                                        <?php foreach ($genders as $gender) { 
                                    if ($user['gender'] == $gender) {?>
                                        <option value="<?=$gender?>" selected><?=$gender?></option>
                                        <?php } else { ?>
                                        <option value="<?=$gender?>"><?=$gender?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="dateOfBirth">Birthday</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <svg class="icon icon-xs" fill="currentColor" viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </span>
                                        <input data-datepicker class="form-control" id="dateOfBirth" name="dateOfBirth"
                                            type="text" placeholder="dd/mm/yyyy" value="<?=$user['date_of_birth']?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="martialStatus">Martial Status</label>
                                    <select class="form-select" name="martialStatus" id="martialStatus">
                                        <?php foreach ($martial_status as $status) { 
                                    if ($user['martial_status'] == $status) {?>
                                        <option value="<?=$status?>" selected><?=$status?></option>
                                        <?php } else { ?>
                                        <option value="<?=$status?>"><?=$status?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="bloodGroup">Blood Group</label>
                                    <input type="text" name="bloodGroup" id="bloodGroup" class="form-control"
                                        value="<?=$user['blood_group']?>" />
                                </div>
                            </div>
                        </div>
                        <h2 class="h5 my-4">Roles and Permission</h2>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="role">Role <span
                                            class="text-danger">(<b>Unchangeable</b>)</span></label>
                                    <select id="role" class="form-select bg-white">
                                        <?php if ($_SESSION['role'] != 'super-admin') {
                                        foreach ($roles as $role) {
                                            if ($user['role'] == $role['id']) { ?>
                                        <option value="<?=$role['id']?>" selected><?=$role['name']?></option>
                                        <?php } } } else { ?>
                                        <option selected>Super Admin</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="username">Username <span
                                            class="text-danger">(<b>Unchangeable</b>)</span></label>
                                    <input class="form-control bg-white" id="username" type="text"
                                        value="<?=$user['username']?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password">Password</label>
                                    <input class="form-control" id="password" name="password" type="password"
                                        value="<?=$user['password']?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input class="form-control" id="confirm_password" name="confirm_password"
                                        type="password" value="<?=$user['password']?>" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <input type="hidden" name="id" value="<?=$_SESSION['id']?>" />
                            <input type="hidden" name="page" value="settings.php" />
                            <button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">
                                Save all
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                    <h2 class="h5 mb-4">Alerts & Notifications</h2>
                    <ul class="list-group list-group-flush">
                        <li
                            class="list-group-item d-flex align-items-center justify-content-between px-0 border-bottom">
                            <div>
                                <h3 class="h6 mb-1">Company News</h3>
                                <p class="small pe-4">Get Rocket news, announcements, and product updates</p>
                            </div>
                            <div>
                                <div class="form-check form-switch"><input class="form-check-input" type="checkbox"
                                        id="user-notification-1"> <label class="form-check-label"
                                        for="user-notification-1"></label></div>
                            </div>
                        </li>
                        <li
                            class="list-group-item d-flex align-items-center justify-content-between px-0 border-bottom">
                            <div>
                                <h3 class="h6 mb-1">Account Activity</h3>
                                <p class="small pe-4">Get important notifications about you or activity you've missed
                                </p>
                            </div>
                            <div>
                                <div class="form-check form-switch"><input class="form-check-input" type="checkbox"
                                        id="user-notification-2" checked="checked"> <label class="form-check-label"
                                        for="user-notification-2"></label></div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                            <div>
                                <h3 class="h6 mb-1">Meetups Near You</h3>
                                <p class="small pe-4">Get an email when a Dribbble Meetup is posted close to my location
                                </p>
                            </div>
                            <div>
                                <div class="form-check form-switch"><input class="form-check-input" type="checkbox"
                                        id="user-notification-3" checked="checked"> <label class="form-check-label"
                                        for="user-notification-3"></label></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow border-0 text-center p-0">
                            <div class="profile-cover rounded-top"
                                data-background="https://images.hdqwalls.com/wallpapers/city-rain-blur-bokeh-effect-7w.jpg">
                            </div>
                            <div class="card-body pb-5"><img src="uploads/profiles/<?=$user['img']?>"
                                    class="avatar-xl rounded-circle mx-auto mt-n7 mb-4"
                                    alt="<?=$user['f_name']?> Portrait">
                                <h4 class="h3"><?=$user['f_name']." ".$user['s_name']?></h4>
                                <h5 class="fw-normal text-capitalize">
                                    <?php if ($user['role'] != 'super-admin') {
                                    foreach ($roles as $value) {
                                        if ($user['role'] == $value['id']) {
                                            echo $value['name'];
                                        }
                                    }
                                } else {
                                    echo $user['role'];
                                } ?>
                                </h5>
                                <p class="text-gray mb-4 text-capitalize"><?=$user['country']?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-body border-0 shadow mb-4">
                            <h2 class="h5 mb-4">Select profile photo</h2>
                            <div class="d-flex align-items-center">
                                <div class="file-field">
                                    <div class="d-flex justify-content-xl-center ms-xl-3">
                                        <div class="d-flex">
                                            <svg class="icon text-gray-500 me-2" fill="currentColor" viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <input id="profileUpload" type="file" value="" />
                                            <div class="d-md-block text-left">
                                                <div class="fw-normal text-dark mb-1">Choose Image</div>
                                                <div class="text-gray small">JPG, JPEG or PNG. Max size of 1MB.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if($_SESSION['role'] == "super-admin") { ?>
                    <div class="col-12 mb-4">
                        <div class="card shadow border-0 p-0">
                            <div class="card-body pb-5">
                                <h4 class="h3 text-center">License</h4>

                                <table class="table table-centered table-nowrap mb-0 rounded">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="border-0 rounded-start">Label</th>
                                            <th class="border-0 rounded-end text-end">Details
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Key</td>
                                            <td class="fw-bold text-end"><?=$license_json['key']?></td>
                                        </tr>
                                        <tr>
                                            <td>Validity</td>
                                            <td class="fw-bold text-end"><?=$license_json['validity']?> Months</td>
                                        </tr>
                                        <tr>
                                            <td>Registration Date</td>
                                            <td class="fw-bold text-end">
                                                <?=date("d M, Y | h:i a", strtotime($license_json['registered_date']))?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Expiration Date</td>
                                            <td class="fw-bold text-end">
                                                <?=date("d M, Y | h:i a", strtotime($license_json['expiration_date']))?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Remaining Time</td>
                                            <td class="fw-bold text-end 
                                            <?php if ($totalTimeInMinutes < $sevenDaysInMinutes) { ?>
                                                text-danger
                                            <?php } else { ?>
                                                text-success
                                            <?php } ?>
                                            " id="licenseValidity">
                                                <?=remainingTime($license_json['expiration_date'])?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php include('temp/footer.temp.php'); ?>
    </main>

    <div class="modal fade" id="image-modal" tabindex="-1" aria-labelledby="image-modal" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-primary modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close theme-settings-close fs-6 ms-auto" data-bs-dismiss="modal"
                    aria-label="Close"></button>
                <div class="modal-header mx-auto">
                    <p class="lead mb-0 text-white">Crop Profile Image</p>
                </div>
                <div class="modal-body pt-0">
                    <div class="row">
                        <div class="col-md-8">
                            <img class="img-fluid" id="image-preview" />
                        </div>
                        <div class="col-md-4">
                            <div class="mx-auto preview"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center pt-0 pb-3">
                    <button type="button" id="cropAndUpload" class="btn btn-sm btn-white text-tertiary">
                        Crop & Upload
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php include('temp/script.temp.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
    $(function() {
        setInterval(() => {
            $("#licenseValidity").load("ajax/license.validity.php");
        }, 3000);
        <?php if (isset($_GET['message'])) { ?>

        <?php if ($_GET['message'] == 'pass_not_match') { ?>
        notify("error", "Password Doesn't Match ...");
        <?php } elseif ($_GET['message'] == 'edit_true') { ?>
        notify("success", "Successfully Changed Profile ...");
        <?php } elseif ($_GET['message'] == 'edit_false') { ?>
        notify("error", "Something's Wrong, Report Error ...");
        <?php } ?>

        <?php } ?>
        const fileUpload = $("#profileUpload");

        fileUpload.change(function() {
            var file = this.files[0];
            if (file) {
                var fileSize = file.size; // in bytes
                var maxSize = 1000000; // in bytes

                var fileName = this.value.split('\\').pop(); // get file name without path
                var fileExtension = fileName.split('.').pop().toLowerCase(); // get file extension
                if (fileExtension !== 'jpeg' && fileExtension !== 'jpg' && fileExtension !== 'png') {
                    notify("error",
                        "You are using the wrong extension. Please use only JPEG, JPG, or PNG.");
                    $(this).val(null); // reset the input
                    return;
                }
                if (fileSize > maxSize) {
                    notify("error", "The size of the file is larger than 1 MB.");
                    $(this).val(null); // reset the input
                    return;
                }

                var image = document.getElementById('image-preview');
                var done = function(url) {
                    image.src = url;
                    $("#image-modal").modal('show');
                };

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }

                $("#image-modal").on('shown.bs.modal', function() {
                    cropper = new Cropper(image, {
                        aspectRatio: 1,
                        viewMode: 3,
                        preview: '.preview'
                    });
                }).on('hidden.bs.modal', function() {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }
                });
            }
        });

        $('#cropAndUpload').on('click', function() {
            canvas = cropper.getCroppedCanvas({
                width: 600,
                height: 600,
            });

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    uploadFile(base64data);
                };
            });
        });

        $("#image-modal").on('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        function uploadFile(file) {
            var formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: 'ajax/img.upload.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == "invalid") {
                        notify("error", "Something's Wrong ...");
                    } else if (response == "valid") {
                        notify("success", "Image Uploaded Successfully ...");
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function() {
                    console.log('An error occurred. Please try again.');
                }
            });
        }
    });
    </script>
</body>

</html>