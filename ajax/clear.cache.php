<?php

session_start();
#################### Login & License Validation ####################
require("../temp/validate.login.temp.php"); #
$license_path = "../licenses/" . $_SESSION['license_username'] . "/license.json"; #
require("../auth/license.validate.functions.php"); #
require("../temp/validate.license.temp.php"); #
#################### Login & License Validation ####################

######################## Database Connection #######################
require("../auth/config.php"); #
require("../auth/functions.php"); #
$conn = conn("localhost", "root", "", "unitySync"); #
######################## Database Connection #######################

$directory = "../uploads/cache/";
$files = glob($directory . "*-".$_SESSION['project'],GLOB_BRACE);
foreach ($files as $key => $file) {
    unlink($file);
}
echo "valid";