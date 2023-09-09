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
    header("Location: ../account.all.php?m=account_add_not_allow");
    exit();
}
################################ Role Validation ################################

$type = $conn->query("SELECT `type` FROM `properties` WHERE `pty_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';");
$type = $type->fetch_assoc();

$property = $conn->query("SELECT * FROM `".$type['type']."` WHERE `pty_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';");
$property = $property->fetch_assoc();

echo "<pre>";

$query = "DELETE FROM `properties` WHERE `pty_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';";
$conn->query($query);

// Activity Record
$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = strtoupper($type['type']) . " &quot;#" . $property['number'] . "&quot; with ID &quot;" . encryptor("decrypt", $_GET['i']) . "&quot; has been Deleted.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);
// Activity Record

$query = "DELETE FROM `".$type['type']."` WHERE `pty_id` = '".encryptor("decrypt", $_GET['i'])."' AND `project_id` = '".$_SESSION['project']."';;";
if ($conn->query($query)) {
    header("Location: ../property.all.php?m=delete_true");
    exit();
} else {
    header("Location: ../property.all.php?m=delete_false");
    exit();
}