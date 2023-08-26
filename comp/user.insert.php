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
if (validationRole($conn, $_SESSION['project'], $_SESSION['role'], "add-user") != true) {
    header("Location: ../user.all.php?message=user_add_not_allow");
    exit();
}
################################ Role Validation ################################

$data = [];

$query = "SELECT `license`,`country` FROM `users` WHERE `u_id` = '" . $_SESSION['id'] . "' AND `project_id` = '" . $_SESSION['project'] . "';";
$selected_user = mysqli_fetch_assoc(mysqli_query($conn, $query));

foreach ($_POST as $key => $value) {
    $data[$key] = sanitize($conn, $value);
}
$data['license'] = $selected_user['license'];
$data['id'] = voucherId($conn, "users", "u_id", "UI", 3);
$data['project'] = $_SESSION['project'];
$data['country'] = $selected_user['country'];
if (empty($data['status'])) {
    $data['status'] = 0;
}

$phone_format = array(
    "(",
    ")",
    " ",
    "-"
);
$data['phoneNo'] = str_replace($phone_format,"",$data['phoneNo']);
$data['altPhoneNo'] = str_replace($phone_format,"",$data['altPhoneNo']);
$data['email'] = $data['email'].$data['email_format'];

// echo "<pre>";
// print_r($data);
// exit();

$db_activity['date'] = date("d-m-Y", strtotime($created_date));
$db_activity['user_id'] = $_SESSION['id'];
$db_activity['activity'] = "User &quot;" . $data['fName'] . "&quot; has been Created.";
$db_activity['project'] = $_SESSION['project'];
$db_activity['created_date'] = $created_date;
$db_activity['created_by'] = $created_by;
activity($conn, $db_activity);

$query = "INSERT INTO `users`(`u_id`, `license`, `prefix`, `f_name`, `s_name`, `email`, `country`, `phone_no`, `status`, `role`, `username`, `password`, `date_of_birth`, `gender`, `martial_status`, `blood_group`, `project_id`, `created_date`, `created_by`) 
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssssssssssssssss", $data['id'], $data['license'], $data['prefix'], $data['fName'], $data['lName'], $data['email'], $data['country'], $data['phoneNo'], $data['status'], $data['role'], $data['username'], $data['password'], $data['dateOfBirth'], $data['gender'], $data['martialStatus'], $data['bloodGroup'], $data['project'], $created_date, $created_by);
if ($stmt->execute()) {
    header("Location: ../user.all.php?message=add_true");
    exit();
} else {
    header("Location: ../user.all.php?message=add_false");
    exit();
}