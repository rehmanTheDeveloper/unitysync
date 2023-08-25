<?php

session_start();
###################### Login & License Validation ######################
require("../temp/validate.login.temp.php");                            #
$license_path = "../licenses/".$_SESSION['license_username']."/license.json";  #
require("../auth/license.validate.functions.php");                     #
require("../temp/validate.license.temp.php");                          #
###################### Login & License Validation ######################

######################## Database Connection #######################
require("../auth/config.php");                                     #
require("../auth/functions.php");                                  #
$conn = conn("localhost", "root", "", "pine-valley");              #
######################## Database Connection #######################

$query = "SELECT * FROM `roles` WHERE `name` = ? AND `project_id` = '".$_SESSION['project']."';";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_POST['role_name']);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows >= 1) {
    echo "invalid";
} else {
    echo "valid";
}