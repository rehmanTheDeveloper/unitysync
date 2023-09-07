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
$conn = conn("localhost", "root", "", "unitySync");              #
######################## Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user-role") != true) {
    header("Location: ../user.role.all.php?m=role_view_not_allow");
    exit();
}
################################ Role Validation ################################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "delete-user-role") != true) {
    header("Location: ../user.role.all.php?m=role_delete_not_allow");
    exit();
}
################################ Role Validation ################################

if (empty($_GET['i'])) {
    header("Location: ../user.role.all.php?m=not_found");
    exit();
}

$query = "SELECT * FROM `users` WHERE `role` = '".$_GET['i']."' AND `project_id` = '".$_SESSION['project']."';";
$user_exist = fetch_Data($conn, $query);
if ($user_exist) {
    header("Location: ../user.role.all.php?m=user_exist");
    exit();
}

$query = "SELECT `name` FROM `roles` WHERE `id` = '".$_GET['i']."';";
$selected_role = mysqli_fetch_assoc(mysqli_query($conn, $query));

$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "Role &quot;".$selected_role['name']."&quot; has been Deleted.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);

$query = "DELETE FROM `roles` WHERE `id` = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_GET['i']);
$stmt->execute();
$stmt->close();

$query = "DELETE FROM `role_permissions` WHERE `role` = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_GET['i']);
if ($stmt->execute()) {
    $stmt->close();
    header("Location: ../user.role.all.php?m=delete_true");
    exit();
} else {
    $stmt->close();
    header("Location: ../user.role.all.php?m=delete_false");
    exit();
}

?>