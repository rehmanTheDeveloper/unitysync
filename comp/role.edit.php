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
$conn = conn("localhost", "root", "", "communiSync");              #
######################## Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "view-user-role") != true) {
    header("Location: ../user.role.all.php?m=role_view_not_allow");
    exit();
}
################################ Role Validation ################################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user-role") != true) {
    header("Location: ../user.role.all.php?m=role_edit_not_allow");
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

if ($data['roleName'] == 'super-admin') {
    header("Location: ../user.role.edit.php?id=".$data['id']."&m=not_found");
    exit();
}

$query = "UPDATE `roles` SET `name`=? WHERE `id` = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $data['roleName'], $data['id']);
$stmt->execute();
$stmt->close();

$query = "SELECT `name` FROM `roles` WHERE `id` = '".$data['id']."';";
$selected_role = mysqli_fetch_assoc(mysqli_query($conn, $query));

$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "Some Changings has been made in Role &quot;".$selected_role['name']."&quot;.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);

$query = "DELETE FROM `role_permissions` WHERE `role` = ?;";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $data['id']);
$stmt->execute();
$stmt->close();

foreach ($data['role'] as $key => $value) {
    $query = "INSERT INTO `role_permissions`(`role`, `permission`, `project_id`, `created_date`, `created_by`) VALUES (?,?,?,?,?);";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $data['id'], $value, $_SESSION['project'], $created_date, $created_by);
    if ($stmt->execute()) {
        $valid = TRUE;
    }
    $stmt->close();
}

if ($valid) {
    header("Location: ../user.role.all.php?m=edit_true");
    exit();
} else {
    header("Location: ../user.role.all.php?m=edit_false");
    exit();
}
