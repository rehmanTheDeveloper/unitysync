<?php

session_start();
###################### Login & License Validation ######################
require("../temp/validate.login.temp.php");                            #
$license_path = "../license/".$_SESSION['license_username']."/license.json";  #
require("../auth/license.validate.functions.php");                     #
require("../temp/validate.license.temp.php");                          #
###################### Login & License Validation ######################

######################## Database Connection #######################
require("../auth/config.php");                                     #
require("../auth/functions.php");                                  #
$conn = conn("localhost", "root", "", "unitySync");              #
######################## Database Connection #######################

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

$query = "SELECT `f_name`,`id`,`s_name` FROM `users` WHERE `id` = '".$_GET['i']."' AND `project_id` = '".$_SESSION['project']."';";
$user = mysqli_fetch_assoc(mysqli_query($conn, $query));

$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "User &quot;".$user['f_name']." ".$user['s_name']."&quot; with ID &quot;".$user['id']."&quot; has been Deleted.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);


$query = "DELETE FROM `users` WHERE `id` = ? AND `project_id` = '".$_SESSION['project']."';";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_GET['i']);
if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../user.all.php?m=deleted_true");
    exit();
} else {
    $stmt->close();
    header("Location: ../user.all.php?m=false");
    exit();
}

?>