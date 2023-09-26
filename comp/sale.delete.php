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
######################## Database Connection #######################

header("Location: ../sale.all.php?m=not_found");

#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################
#################################### Page is Pending ####################################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user") != true) {
    header("Location: ../user.all.php?m=not_allowed");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['i'])) {
    header("Location: ../user.all.php?m=false");
    exit();
}

$query = "SELECT `type` FROM `sales` WHERE `sale_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$sale = mysqli_fetch_assoc(mysqli_query($conn, $query));

if (empty($sale)) {
    header("Location: ../sale.all.php?m=not_found");
    exit();
}

$query = "DELETE FROM `sale_".$sale['type']."` WHERE `sale_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
// $conn->query($query);
// echo $query;

$query = "DELETE FROM `ledger` WHERE `sale_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
// $conn->query($query);
// echo $query;

// Activity Record
$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "Sale &quot;".encryptor("decrypt", $_GET['i'])."&quot; has been Deleted.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
// activity($conn, $db_activity);
// Activity Record
print_r($db_activity);