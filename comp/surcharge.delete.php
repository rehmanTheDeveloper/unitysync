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
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-account") != true) {
    header("Location: ../Accounts?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

foreach ($_GET as $key => $value) {
    $data[$key] = sanitize($conn, $value);
}
$data['i'] = encryptor("decrypt", $data['i']);

if (empty($data['i'])) {
    header("Location: ../surcharge.php?m=not_found");
    exit();
}

if ($data['type'] == "expenseSubGroup") {

    // TODO: Create a query for existance of expense sub group in account expense

    // echo "<pre>";
    // print_r($data);
    $query = "DELETE FROM `expense_sub_groups` WHERE `id` = ?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $data['i']);
    if ($stmt->execute()) {
        header("Location: ../surcharge.php?m=group_delete_true");
        exit();
    } else {
        header("Location: ../surcharge.php?m=group_delete_false");
        exit();
    }

} elseif ($data['type'] == 'exp') {

}