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
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user-role") != true) {
    header("Location: ../user.role.all.php?m=role_add_not_allow");
    exit();
}
################################ Role Validation ################################

$data = [];
$valid = FALSE;

foreach ($_POST as $key => $value) {
    if (empty($value)) {
        header("Location: ../user.role.add.php?m=field_missing");
        exit();
    }
    if ($key == "role") {
        foreach ($value as $single_index) {
            $data[$key][] = sanitize($conn, $single_index);
        }
    } else {
        $data[$key] = sanitize($conn, $value);
    }
}

$query = "INSERT INTO `roles`(`name`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?);";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $data['roleName'], $_SESSION['project'], $created_date, $created_by);
$stmt->execute();

$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "Role &quot;".$data['roleName']."&quot; has been Created.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);

$query = "SELECT * FROM `roles` WHERE `project_id` = '".$_SESSION['project']."' ORDER BY `id` DESC LIMIT 1;";
$selected_role = mysqli_fetch_assoc(mysqli_query($conn, $query));

foreach ($data['role'] as $key => $value) {
    $query = "INSERT INTO `role_permissions`(`role`, `permission`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $selected_role['id'], $value, $_SESSION['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        $valid = TRUE;
    }
}

if ($valid) {
    header("Location: ../user.role.all.php?m=add_true");
    exit();
} else {
    header("Location: ../user.role.all.php?m=add_false");
    exit();
}
