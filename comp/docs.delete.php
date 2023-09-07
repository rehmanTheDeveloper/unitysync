<?php

session_start();
###################### Login & License Validation ######################
require("../temp/validate.login.temp.php"); #
$license_path = "../licenses/" . $_SESSION['license_username'] . "/license.json"; #
require("../auth/license.validate.functions.php"); #
require("../temp/validate.license.temp.php"); #
###################### Login & License Validation ######################

######################## Database Connection #######################
require("../auth/config.php"); #
require("../auth/functions.php"); #
$conn = conn("localhost", "root", "", "unitySync"); #
######################## Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") != true) {
    header("Location: ../user.all.php?m=not_allowed");
    exit();
}
################################ Role Validation ################################

if (empty($_POST['i'])) {
    header("Location: ../account.all.php?m=false");
    exit();
}

$query = "SELECT `acc_id` FROM `document` WHERE `id` = '".encryptor("decrypt", $_POST['i'])."';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));

$refined_doc_id = encryptor("decrypt", $_POST['i']);

// echo "<pre>";
// print_r($result);

$query = "DELETE FROM `document` WHERE `id` = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $refined_doc_id);
if ($stmt->execute()) {
    header("Location: ../docs.view.php?m=delete_true&i=".encryptor("encrypt", $result['acc_id']));
    exit();
}