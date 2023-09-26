<?php

session_start();
###################### Login & License Validation ######################
require("../temp/validate.login.temp.php"); #
$license_path = "../license/" . $_SESSION['license_username'] . "/license.json"; #
require("../auth/license.validate.functions.php"); #
require("../temp/validate.license.temp.php"); #
###################### Login & License Validation ######################

######################## Database Connection #######################
require("../auth/config.php"); #
require("../auth/functions.php"); #
$conn = conn("localhost", "root", "", "unitySync"); #
$DB_Connection = new DB("localhost", "unitySync", "root", ""); #
$PDO_conn = $DB_Connection->getConnection(); #
######################## Database Connection #######################

require "../object/ledger.php";

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: ../account.all.php?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################



$voucher = encryptor("decrypt", $_GET['i']);
echo $voucher;

$query = "DELETE FROM `payment` WHERE `voucher_id` = ? AND `project_id` = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $voucher, $_SESSION['project']);
if ($stmt->execute()) {
    header("Location: ../payment.paid.php?m=delete_true");
    exit();
} else {
    header("Location: ../payment.paid.php?m=delete_false");
    exit();
}