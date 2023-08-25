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
$conn = conn("localhost", "root", "", "communiSync"); #
######################## Database Connection #######################

################################ Role Validation ################################
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "edit-user") != true) {
    header("Location: ../user.all.php?message=user_edit_not_allow");
    exit();
}
################################ Role Validation ################################

if (isset($_GET['id']) && empty($_GET['id'])) {
    header("Location: ../user.all.php");
    exit();
}
if (isset($_POST['id']) && empty($_POST['id'])) {
    header("Location: ../user.all.php");
    exit();
}

$query = "SELECT * FROM `users` WHERE `u_id` = '";
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $query .= $_GET['id'];
} elseif (isset($_POST['id']) && !empty($_POST['id'])) {
    $query .= $_POST['id'];
}
$query .= "' AND `project_id` = '" . $_SESSION['project'] . "';";
$selected_user = mysqli_fetch_assoc(mysqli_query($conn, $query));
if (empty($selected_user)) {
    header("Location: ../user.all.php?message=not_found");
    exit();
}

if (isset($_GET['status']) && $_GET['status'] == "1") {
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = "User &quot;" . $selected_user['f_name'] . "&quot; Status has been changed to Active.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);

    $status = '1';
    $query = "UPDATE `users` SET `status`=? WHERE `u_id` =? AND `project_id` = '" . $_SESSION['project'] . "';";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $status, $_GET['id']);
    if ($stmt->execute()) {
        header("Location: ../user.all.php?message=status_updated_true");
        exit();
    } else {
        header("Location: ../user.all.php?message=status_updated_false");
        exit();
    }
} elseif (isset($_GET['status']) && $_GET['status'] == "0") {
    $db_activity['date'] = date("d-m-Y", strtotime($created_date));
    $db_activity['user_id'] = $_SESSION['id'];
    $db_activity['activity'] = "User &quot;" . $selected_user['f_name'] . "&quot; Status has been changed to Inactive.";
    $db_activity['project'] = $_SESSION['project'];
    $db_activity['created_date'] = $created_date;
    $db_activity['created_by'] = $created_by;
    activity($conn, $db_activity);

    $status = '0';
    $query = "UPDATE `users` SET `status`=? WHERE `u_id` =? AND `project_id` = '" . $_SESSION['project'] . "';";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $status, $_GET['id']);
    if ($stmt->execute()) {
        header("Location: ../user.all.php?message=status_updated_true");
        exit();
    } else {
        header("Location: ../user.all.php?message=status_updated_false");
        exit();
    }
} else {

    $data = [];
    $valid = FALSE;
    foreach ($_POST as $key => $value) {
        $data[$key] = sanitize($conn, $value);
    }

    if ($data['password'] !== $data['confirm_password']) {
        if (empty($data['page'])) {
            $location = "Location: ../user.edit.php?id=";
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $location .= $_GET['id'];
            } elseif (isset($_POST['id']) && !empty($_POST['id'])) {
                $location .= $_POST['id'];
            }
            $location .= "&message=pass_not_match";
            header($location);
            exit();
        } else {
            header("Location: ../".$data['page']."?message=pass_not_match");
            exit();
        }
    }

    if (empty($data['page'])) {
        $db_activity['date'] = date("d-m-Y", strtotime($created_date));
        $db_activity['user_id'] = $_SESSION['id'];
        $db_activity['activity'] = "User &quot;" . $selected_user['f_name'] . "&quot; has been Modified.";
        $db_activity['project'] = $_SESSION['project'];
        $db_activity['created_date'] = $created_date;
        $db_activity['created_by'] = $created_by;
        activity($conn, $db_activity);
    } else {
        $db_activity['date'] = date("d-m-Y", strtotime($created_date));
        $db_activity['user_id'] = $_SESSION['id'];
        $db_activity['activity'] = $selected_user['f_name'] . " modified His Profile.";
        $db_activity['project'] = $_SESSION['project'];
        $db_activity['created_date'] = $created_date;
        $db_activity['created_by'] = $created_by;
        activity($conn, $db_activity);
    }

    if (empty($data['page'])) {
        $query = "UPDATE `users` SET `prefix`=?, `f_name`=?, `s_name`=?, `email`=?, `phone_no`=?, `status`=?, `role`=?, `password`=?, `date_of_birth`=?, `gender`=?, `martial_status`=?, `blood_group`=? WHERE `u_id` =? AND `project_id` = '" . $_SESSION['project'] . "';";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssssss", $data['prefix'], $data['fName'], $data['lName'], $data['email'], $data['phoneNo'], $data['status'], $data['role'], $data['password'], $data['dateOfBirth'], $data['gender'], $data['martialStatus'], $data['bloodGroup'], $data['id']);
        if ($stmt->execute()) {
            header("Location: ../user.all.php?message=edit_true");
            exit();
        } else {
            $location = "Location: ../user.edit.php?id=";
            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $location .= $_GET['id'];
            } elseif (isset($_POST['id']) && !empty($_POST['id'])) {
                $location .= $_POST['id'];
            }
            $location .= "&message=edit_false";
            header($location);
            exit();
        }
    } else {
        // print_r($data);
        $query = "UPDATE `users` SET `prefix`=?, `f_name`=?, `s_name`=?, `email`=?, `phone_no`=?, `password`=?, `date_of_birth`=?, `gender`=?, `martial_status`=?, `blood_group`=? WHERE `u_id` =? AND `project_id` = '" . $_SESSION['project'] . "';";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssss", $data['prefix'], $data['fName'], $data['lName'], $data['email'], $data['phoneNo'], $data['password'], $data['dateOfBirth'], $data['gender'], $data['martialStatus'], $data['bloodGroup'], $data['id']);
        if ($stmt->execute()) {
            header("Location: ../".$data['page']."?message=edit_true");
            exit();
        } else {
            header("Location: ../".$data['page']."?message=edit_false");
            exit();
        }
    }

}

?>