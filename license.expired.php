<?php

session_start();
#################### Login & License Validation ####################
require("temp/validate.login.temp.php");                           #
$license_path = "licenses/".$_SESSION['license_username']."/license.json"; #
require("auth/license.validate.functions.php");                    #
#################### Login & License Validation ####################

$license_json = json_decode(file_get_contents($license_path), true);

$title = "License | Expired";
if (validateLicenseKey($license_json['key'], $license_json['product']) != "expired" || $license_json['status'] != "2") {
    header("Location: Dashboard");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('temp/head.temp.php'); ?>
</head>

<body>

    <div class="row vh-100 justify-content-center align-items-center">
        <div class="col-xl-5 col-lg-7 col-md-10 col-12 px-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-center" id="countdown"></h4>
                    <div class="text-center">
                        <img class="img-fluid" src="assets/img/expired-license.png" alt="" />
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text text-gray-900">License Key</span>
                        <input type="text" class="form-control" value="<?= $license_json['key'] ?>" disabled />
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text text-gray-900">Registered Date</span>
                        <input type="text" class="form-control" value="<?= $license_json['registered_date'] ?>"
                            disabled />
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text text-gray-900">Expiration Date</span>
                        <input type="text" class="form-control" value="<?= $license_json['expiration_date'] ?>"
                            disabled />
                    </div>
                    <div class="mb-3 text-center">
                        <a href="auth/logout.php" class="btn btn-primary fs-5">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>